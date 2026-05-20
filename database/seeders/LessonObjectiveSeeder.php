<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LessonObjectiveSeeder extends Seeder
{
    public function run(): void
    {
        $classId = '6a034c96691db6daf1010b0e';

        $lessons = Lesson::where('class_id', $classId)->get();
        foreach ($lessons as $lesson) {
            $topic = trim((string) ($lesson->topic ?? ''));
            $lesson->objectives = $this->buildObjective($topic);
            $lesson->save();
        }
    }

    private function buildObjective(string $topic): string
    {
        if ($topic === '') {
            return 'Understand the main ideas, practice with examples, and explain the concept clearly.';
        }

        $topicLower = Str::of($topic)->lower()->toString();

        if (Str::contains($topicLower, ['fraction'])) {
            return 'Understand fractions as parts of a whole, compare and order fractions, and solve real-life fraction problems.';
        }

        if (Str::contains($topicLower, ['history', 'heritage', 'local'])) {
            return 'Identify key local events and cultural landmarks, explain their significance, and connect the past to present life.';
        }

        if (Str::contains($topicLower, ['science', 'biology', 'chemistry', 'physics'])) {
            return 'Describe the core scientific ideas, practice observation, and apply the scientific method to simple experiments.';
        }

        if (Str::contains($topicLower, ['reading', 'literature', 'story'])) {
            return 'Build reading fluency and comprehension, identify main ideas, and summarize key points from passages.';
        }

        if (Str::contains($topicLower, ['math', 'algebra', 'geometry', 'number'])) {
            return 'Strengthen foundational math skills, solve practice problems, and explain the reasoning behind each step.';
        }

        return 'Explain the main ideas of ' . $topic . ', practice with examples, and show understanding through short reflections.';
    }
}
