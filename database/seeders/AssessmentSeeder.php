<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SchoolClass::all() as $class) {
            for ($i = 0; $i < 2; $i++) {
                Assessment::create([
                    'title' => $class->name . ' Test ' . ($i + 1),
                    'class_id' => (string) $class->getKey(),
                    'teacher_id' => $class->teacher_id,
                    'date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                    'max_score' => fake()->randomElement([25, 50, 100]),
                    'description' => fake()->sentence(10),
                ]);
            }
        }
    }
}
