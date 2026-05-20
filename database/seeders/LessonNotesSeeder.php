<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LessonNotesSeeder extends Seeder
{
    public function run(): void
    {
        $lessons = Lesson::all();

        foreach ($lessons as $lesson) {
            $topic = trim((string) ($lesson->topic ?? ''));
            $lesson->notes = $this->buildNotes($topic);
            $lesson->save();
        }
    }

    private function buildNotes(string $topic): string
    {
        if ($topic === '') {
            return 'We will explore the main ideas through examples, guided discussion, and short practice activities.';
        }

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
