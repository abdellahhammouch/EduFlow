<?php

namespace App\Repositories;

use App\Interfaces\Repositories\PaymentRepositoryInterface;
use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function findById(int $paymentId): ?Payment
    {
        return Payment::query()->find($paymentId);
    }

    public function findByIntentId(string $paymentIntentId): ?Payment
    {
        return Payment::query()
            ->where('stripe_payment_intent_id', $paymentIntentId)
            ->first();
    }

    public function create(array $data): Payment
    {
        return Payment::query()->create($data);
    }

    public function update(Payment $payment, array $data): bool
    {
        return $payment->update($data);
    }
}
