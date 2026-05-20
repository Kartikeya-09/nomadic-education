<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
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

        $classes = SchoolClass::all();

        for ($i = 0; $i < 15; $i++) {
            $class = $classes->random();
            $hasEmail = fake()->boolean(60);

            $student = User::create([
                'name' => fake()->name(),
                'email' => $hasEmail ? fake()->unique()->safeEmail() : null,
                'password' => Hash::make('password'),
                'dob' => fake()->dateTimeBetween('-14 years', '-7 years')->format('Y-m-d'),
                'guardian_name' => fake()->name(),
                'guardian_phone' => fake()->numerify('95########'),
                'community_tribe' => fake()->randomElement($tribes),
                'current_camp_location' => fake()->randomElement($locations),
                'preferred_language' => fake()->randomElement($languages),
                'enrollment_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'status' => true,
                'class_id' => (string) $class->getKey(),
            ]);
            $student->assignRole('student');
        }

        $students = User::role('student')->get();
        $parents = User::role('parent')->get();

        foreach ($parents as $parent) {
            $guardianOf = $students->random(rand(1, 3))
                ->pluck('_id')
                ->map(fn ($id) => (string) $id)
                ->values()
                ->all();

            $parent->update([
                'guardian_of' => $guardianOf,
            ]);
        }
    }
}
