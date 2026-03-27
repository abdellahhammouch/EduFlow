<?php

namespace App\Services;

use App\Interfaces\Repositories\CourseRepositoryInterface;
use App\Interfaces\Repositories\EnrollmentRepositoryInterface;
use App\Interfaces\Repositories\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use LogicException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

class StripePaymentService
{
    public function __construct(
        private readonly PaymentRepositoryInterface $payments,
        private readonly CourseRepositoryInterface $courses,
        private readonly EnrollmentRepositoryInterface $enrollments,
    ) {
    }

    /**
     * Create a Stripe PaymentIntent and store a local pending payment.
     *
     * @return array<string, mixed>
     */
    public function createPaymentIntent(int $studentId, int $courseId): array
    {
        $course = $this->courses->findAvailableById($courseId);

        if ($course === null) {
            throw new LogicException('Course not found or unavailable.');
        }

        $existingEnrollment = $this->enrollments->findByStudentAndCourse($studentId, $courseId);

        if ($existingEnrollment !== null) {
            throw new LogicException('You are already linked to this course.');
        }

        $stripePaymentIntent = $this->client()->paymentIntents->create([
            'amount' => $this->toStripeAmount((float) $course->price),
            'currency' => config('services.stripe.currency', 'usd'),
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
            'metadata' => [
                'student_id' => (string) $studentId,
                'course_id' => (string) $courseId,
            ],
        ]);

        $payment = DB::transaction(function () use ($studentId, $courseId, $course, $stripePaymentIntent): Payment {
            return $this->payments->create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'stripe_payment_intent_id' => $stripePaymentIntent->id,
                'amount' => $course->price,
                'currency' => config('services.stripe.currency', 'usd'),
                'status' => 'pending',
                'paid_at' => null,
            ]);
        });

        return [
            'payment_id' => $payment->id,
            'course_id' => $courseId,
            'client_secret' => $stripePaymentIntent->client_secret,
            'payment_intent_id' => $stripePaymentIntent->id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'status' => $payment->status,
        ];
    }

    /**
     * Process a Stripe webhook payload and synchronize the local payment.
     */
    public function handleWebhook(string $payload, ?string $signature): array
    {
        $event = $this->constructEvent($payload, $signature);

        /** @var PaymentIntent|null $intent */
        $intent = $event->data->object;

        if ($intent === null || empty($intent->id)) {
            return [
                'handled' => false,
                'event_type' => $event->type,
                'message' => 'Webhook event did not contain a payment intent.',
            ];
        }

        $payment = $this->payments->findByIntentId($intent->id);

        if ($payment === null) {
            return [
                'handled' => false,
                'event_type' => $event->type,
                'message' => 'No local payment matches this payment intent.',
            ];
        }

        return match ($event->type) {
            'payment_intent.succeeded' => $this->markSucceeded($payment),
            'payment_intent.payment_failed',
            'payment_intent.canceled' => $this->markFailed($payment),
            default => [
                'handled' => false,
                'event_type' => $event->type,
                'message' => 'Event ignored.',
            ],
        };
    }

    /**
     * Convert the course price to the smallest Stripe currency unit.
     */
    private function toStripeAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Build and verify the Stripe event.
     */
    private function constructEvent(string $payload, ?string $signature): object
    {
        $webhookSecret = config('services.stripe.webhook_secret');

        if (! empty($webhookSecret)) {
            try {
                return Webhook::constructEvent($payload, (string) $signature, $webhookSecret);
            } catch (UnexpectedValueException|SignatureVerificationException $exception) {
                throw new LogicException('Invalid Stripe webhook signature.', Response::HTTP_BAD_REQUEST, $exception);
            }
        }

        try {
            return json_decode($payload, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new LogicException('Invalid Stripe webhook payload.', Response::HTTP_BAD_REQUEST, $exception);
        }
    }

    /**
     * Mark a local payment as succeeded.
     *
     * @return array<string, mixed>
     */
    private function markSucceeded(Payment $payment): array
    {
        $this->payments->update($payment, [
            'status' => 'succeeded',
            'paid_at' => now(),
        ]);

        return [
            'handled' => true,
            'event_type' => 'payment_intent.succeeded',
            'payment_id' => $payment->id,
            'status' => 'succeeded',
        ];
    }

    /**
     * Mark a local payment as failed.
     *
     * @return array<string, mixed>
     */
    private function markFailed(Payment $payment): array
    {
        $this->payments->update($payment, [
            'status' => 'failed',
        ]);

        return [
            'handled' => true,
            'event_type' => 'payment_intent.failed',
            'payment_id' => $payment->id,
            'status' => 'failed',
        ];
    }

    /**
     * Build the Stripe client lazily from project config.
     */
    private function client(): StripeClient
    {
        $secret = config('services.stripe.secret');

        if (empty($secret)) {
            throw new LogicException('Stripe secret key is not configured.');
        }

        return new StripeClient((string) $secret);
    }
}
