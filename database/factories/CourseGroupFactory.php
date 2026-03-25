<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseGroup>
 */
class CourseGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'group_number' => 1,
        ];
    }
}
