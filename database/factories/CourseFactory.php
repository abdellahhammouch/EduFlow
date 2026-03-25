<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => User::factory()->teacher(),
            'domain_id' => Domain::factory(),
            'title' => ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->paragraphs(2, true),
            'price' => fake()->randomFloat(2, 150, 1800),
            'status' => 'active',
        ];
    }
}
