<?php

namespace App\Services;

use App\Interfaces\Repositories\EnrollmentRepositoryInterface;
use App\Interfaces\Repositories\PaymentRepositoryInterface;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LogicException;

class EnrollmentService
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly PaymentRepositoryInterface $payments,
        private readonly GroupAssignmentService $groupAssignment,
    ) {
    }

    public function create(array $data): Enrollment
    {
        return DB::transaction(function () use ($data): Enrollment {
            $existingEnrollment = $this->enrollments->findByStudentAndCourse(
                $data['student_id'],
                $data['course_id'],
            );

            if ($existingEnrollment !== null) {
                throw new LogicException('The student is already linked to this course.');
            }

            $payment = $this->validatePayment(
                $data['payment_id'],
                $data['student_id'],
                $data['course_id'],
            );

            $group = $this->groupAssignment->assignToCourse($data['course_id']);

            return $this->enrollments->create([
                'student_id' => $data['student_id'],
                'course_id' => $data['course_id'],
                'payment_id' => $payment->id,
                'course_group_id' => $group->id,
                'status' => $data['status'] ?? 'active',
                'enrolled_at' => $data['enrolled_at'] ?? $payment->paid_at ?? now(),
            ]);
        });
    }

    public function listByCourse(int $courseId): Collection
    {
        return $this->enrollments->listByCourse($courseId);
    }

    /**
     * Ensure the payment is valid for the authenticated student and course.
     */
    private function validatePayment(int $paymentId, int $studentId, int $courseId): Payment
    {
        $payment = $this->payments->findById($paymentId);

        if ($payment === null) {
            throw new LogicException('Payment not found.');
        }

        if ((int) $payment->student_id !== $studentId) {
            throw new LogicException('This payment does not belong to the authenticated student.');
        }

        if ((int) $payment->course_id !== $courseId) {
            throw new LogicException('This payment is not linked to the selected course.');
        }

        if ($payment->status !== 'succeeded') {
            throw new LogicException('Only succeeded payments can be used for enrollment.');
        }

        if ($payment->enrollment()->exists()) {
            throw new LogicException('This payment has already been used for an enrollment.');
        }

        return $payment;
    }
}
