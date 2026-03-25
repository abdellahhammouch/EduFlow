<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => User::factory()->student(),
            'course_id' => Course::factory(),
            'stripe_payment_intent_id' => 'pi_' . Str::lower(Str::random(24)),
            'amount' => fake()->randomFloat(2, 150, 1800),
            'currency' => 'usd',
            'status' => 'succeeded',
            'paid_at' => now(),
        ];
    }
}
