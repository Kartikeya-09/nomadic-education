<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tribes = ['Gujjar', 'Bakarwal'];
        $locations = [
            'Pahalgam Camp',
            'Sonamarg Valley',
            'Kargil Route',
            'Lidder Valley',
            'Gulmarg Camp',
        ];
        $languages = ['Gojri', 'Urdu'];

        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@nomadic.test',
            'password' => Hash::make('password'),
            'preferred_language' => 'Urdu',
            'status' => true,
        ]);
        $admin->assignRole('admin');

        $subjects = ['Mathematics', 'Science', 'Language', 'Social Studies'];
        $qualifications = ['B.Ed', 'M.Ed', 'B.A', 'M.A'];

        for ($i = 0; $i < 3; $i++) {
            $teacher = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'phone' => fake()->numerify('98########'),
                'subject_specialization' => fake()->randomElement($subjects),
                'qualification' => fake()->randomElement($qualifications),
                'experience_years' => fake()->numberBetween(1, 12),
                'current_camp_location' => fake()->randomElement($locations),
                'assigned_community' => fake()->randomElement(['Bakarwal Group A', 'Gujjar Group B', 'Gujjar Group C']),
                'is_volunteer' => fake()->boolean(30),
                'preferred_language' => fake()->randomElement($languages),
                'status' => true,
            ]);
            $teacher->assignRole('teacher');
        }

        for ($i = 0; $i < 5; $i++) {
            $parent = User::create([
                'name' => fake()->name(),
                'email' => fake()->boolean(70) ? fake()->unique()->safeEmail() : null,
                'password' => Hash::make('password'),
                'phone' => fake()->numerify('97########'),
                'guardian_of' => [],
                'community_tribe' => fake()->randomElement($tribes),
                'current_camp_location' => fake()->randomElement($locations),
                'preferred_language' => fake()->randomElement($languages),
                'status' => true,
            ]);
            $parent->assignRole('parent');
        }
    }
}
