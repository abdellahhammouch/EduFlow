<?php

namespace App\Interfaces\Repositories;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function findById(int $paymentId): ?Payment;

    public function findByIntentId(string $paymentIntentId): ?Payment;

    public function create(array $data): Payment;

    public function update(Payment $payment, array $data): bool;
}
