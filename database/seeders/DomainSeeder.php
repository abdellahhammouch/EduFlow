<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DomainSeeder extends Seeder
{
    /**
     * Seed the application's database with the supported domains.
     */
    public function run(): void
    {
        $domains = [
            'Web Development',
            'Data Science',
            'Cybersecurity',
            'UI UX Design',
            'Cloud Computing',
            'Mobile Development',
        ];

        foreach ($domains as $name) {
            Domain::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
