<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Seed courses for each teacher.
     */
    public function run(): void
    {
        $teachers = User::query()->where('role', 'teacher')->get();
        $domains = Domain::query()->get();

        foreach ($teachers as $teacher) {
            foreach (range(1, 3) as $index) {
                $domain = $domains->random();

                Course::factory()->create([
                    'teacher_id' => $teacher->id,
                    'domain_id' => $domain->id,
                    'title' => "{$domain->name} Course {$index} by {$teacher->id}",
                    'price' => fake()->randomFloat(2, 200, 1200),
                ]);
            }
        }
    }
}
