<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed teachers and students with their interests.
     */
    public function run(): void
    {
        User::factory()->teacher()->create([
            'name' => 'Teacher Demo',
            'email' => 'teacher@example.com',
        ]);

        User::factory()->student()->create([
            'name' => 'Student Demo',
            'email' => 'student@example.com',
        ]);

        User::factory(3)->teacher()->create();
        $students = User::factory(30)->student()->create();
        $domainIds = Domain::query()->pluck('id');

        foreach ($students as $student) {
            $student->interestedDomains()->sync(
                $domainIds->random(random_int(1, min(3, $domainIds->count())))->all(),
            );
        }

        $demoStudent = User::query()->where('email', 'student@example.com')->first();

        if ($demoStudent !== null) {
            $demoStudent->interestedDomains()->sync($domainIds->take(2)->all());
        }
    }
}
