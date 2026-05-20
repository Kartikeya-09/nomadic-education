<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SchoolSeeder::class,
            ClassSeeder::class,
            StudentSeeder::class,
            LessonSeeder::class,
            ContentSeeder::class,
            AssessmentSeeder::class,
            AttendanceSeeder::class,
            QuizQuestionSeeder::class,
        ]);
    }
}
