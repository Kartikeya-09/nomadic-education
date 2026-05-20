<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $topics = ['Fractions', 'Basic Science', 'Reading Practice', 'Local History'];

        foreach (SchoolClass::all() as $class) {
            for ($i = 0; $i < 3; $i++) {
                $topic = fake()->randomElement($topics);
                Lesson::create([
                    'title' => $class->name . ' Lesson ' . ($i + 1),
                    'class_id' => (string) $class->getKey(),
                    'teacher_id' => $class->teacher_id,
                    'date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                    'topic' => $topic,
                    'objectives' => $this->buildObjective($topic),
                    'materials' => fake()->sentence(8),
                    'notes' => $this->buildNotes($topic),
                ]);
            }
        }
    }

    private function buildObjective(string $topic): string
    {
        $topicLower = Str::of($topic)->lower()->toString();

        if (Str::contains($topicLower, ['fraction'])) {
            return 'Understand fractions as parts of a whole, compare and order fractions, and solve simple fraction problems.';
        }

        if (Str::contains($topicLower, ['history', 'heritage', 'local'])) {
            return 'Identify important local events, explain why they matter, and connect history to daily life.';
        }

        if (Str::contains($topicLower, ['science', 'biology', 'chemistry', 'physics'])) {
            return 'Explore basic scientific ideas, practice observation, and explain findings clearly.';
        }

        if (Str::contains($topicLower, ['reading', 'literature', 'story'])) {
            return 'Improve reading fluency, identify main ideas, and answer comprehension questions.';
        }

        if (Str::contains($topicLower, ['math', 'algebra', 'geometry', 'number'])) {
            return 'Strengthen core math skills, solve practice problems, and explain each step.';
        }

        return 'Learn the core ideas of ' . $topic . ' and apply them using real-world examples.';
    }

    private function buildNotes(string $topic): string
    {
        $topicLower = Str::of($topic)->lower()->toString();

        if (Str::contains($topicLower, ['fraction'])) {
            return 'We will model fractions using pictures and number lines, then compare equivalent fractions. Practice includes word problems about sharing food and measuring quantities.';
        }

        if (Str::contains($topicLower, ['history', 'heritage', 'local'])) {
            return 'We will create a simple timeline of local events, identify key places in our community, and discuss how the past shapes our traditions today.';
        }

        if (Str::contains($topicLower, ['science', 'biology', 'chemistry', 'physics'])) {
            return 'We will observe everyday phenomena, make predictions, and record results. Students will explain the cause-and-effect relationships they notice.';
        }

        if (Str::contains($topicLower, ['reading', 'literature', 'story'])) {
            return 'We will read a short passage, highlight new vocabulary, identify the main idea, and answer comprehension questions together.';
        }

        if (Str::contains($topicLower, ['math', 'algebra', 'geometry', 'number'])) {
            return 'We will solve guided practice problems, discuss strategies, and check solutions step-by-step.';
        }

        return 'We will explore ' . $topic . ' through examples, guided discussion, and a short practice activity.';
    }
}
