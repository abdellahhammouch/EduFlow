<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseGroup;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
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
            'payment_id' => Payment::factory(),
            'course_group_id' => CourseGroup::factory(),
            'status' => 'active',
            'enrolled_at' => now(),
            'withdrawn_at' => null,
        ];
    }
}
