<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['present', 'absent', 'late'];
        $teachers = User::role('teacher')->get()->keyBy('_id');
        $classes = SchoolClass::all()->keyBy('_id');

        foreach (User::role('student')->get() as $student) {
            $class = $classes->get($student->class_id);
            $markedBy = $class && $class->teacher_id ? $class->teacher_id : $teachers->keys()->first();

            for ($i = 0; $i < 5; $i++) {
                Attendance::create([
                    'student_id' => (string) $student->getKey(),
                    'class_id' => $class ? (string) $class->getKey() : null,
                    'date' => fake()->dateTimeBetween('-10 days', 'now')->format('Y-m-d'),
                    'status' => fake()->randomElement($statuses),
                    'remarks' => fake()->boolean(40) ? fake()->sentence(6) : null,
                    'marked_by' => $markedBy ? (string) $markedBy : null,
                ]);
            }
        }
    }
}
