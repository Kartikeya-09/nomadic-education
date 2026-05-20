<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['pdf', 'image', 'audio', 'text'];
        $subjects = ['Mathematics', 'Science', 'Language', 'Social Studies'];
        $languages = ['Gojri', 'Urdu'];

        $videoFiles = [
            '024 Formatting Output.mp4',
            '025 Data Types Size and Limits (1).mp4',
            '025 Data Types Size and Limits.mp4',
            '026 Working with Booleans.mp4',
            '027 Working with Characters and Strings.mp4',
            'video.mp4',
            'video1.mp4',
        ];

        $videoIndex = 0;

        foreach (Lesson::all() as $lesson) {
            Content::where('lesson_id', (string) $lesson->getKey())->delete();

            $videoFile = $videoFiles[$videoIndex % count($videoFiles)];
            $videoIndex++;
            $videoUrl = '/videos/' . rawurlencode($videoFile);

            Content::create([
                'title' => $lesson->title . ' Video',
                'type' => 'video',
                'class_id' => $lesson->class_id,
                'lesson_id' => (string) $lesson->getKey(),
                'subject' => fake()->randomElement($subjects),
                'language' => fake()->randomElement($languages),
                'description' => fake()->sentence(12),
                'file_url' => $videoUrl,
                'status' => true,
            ]);

            Content::create([
                'title' => $lesson->title . ' सामग्री',
                'type' => fake()->randomElement($types),
                'class_id' => $lesson->class_id,
                'lesson_id' => (string) $lesson->getKey(),
                'subject' => fake()->randomElement($subjects),
                'language' => fake()->randomElement($languages),
                'description' => fake()->sentence(12),
                'file_url' => fake()->boolean(70) ? 'https://example.com/files/sample.pdf' : null,
                'status' => true,
            ]);
        }
    }
}
