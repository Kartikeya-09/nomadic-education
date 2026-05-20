<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();
        $teachers = User::role('teacher')->get();

        $classNames = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6'];
        $sections = ['A', 'B'];

        foreach ($classNames as $index => $name) {
            $school = $schools->random();
            $teacher = $teachers->get($index % max(1, $teachers->count()));

            SchoolClass::create([
                'name' => $name,
                'school_id' => (string) $school->getKey(),
                'teacher_id' => $teacher ? (string) $teacher->getKey() : null,
                'academic_year' => '2026',
                'section' => $sections[$index % count($sections)],
                'status' => true,
            ]);
        }
    }
}
