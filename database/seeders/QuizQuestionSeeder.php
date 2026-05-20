<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class QuizQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'subject' => 'Mathematics',
                'question' => 'What is 1/2 + 1/4?',
                'options' => ['1/4', '1/2', '3/4', '1'],
                'correct_index' => 2,
                'explanation' => 'Find a common denominator of 4, then add 2/4 + 1/4 = 3/4.',
            ],
            [
                'subject' => 'Mathematics',
                'question' => 'Which fraction is equivalent to 2/4?',
                'options' => ['1/2', '1/4', '3/4', '2/3'],
                'correct_index' => 0,
                'explanation' => 'Simplify 2/4 by dividing numerator and denominator by 2.',
            ],
            [
                'subject' => 'Science',
                'question' => 'Which state of matter has a fixed shape?',
                'options' => ['Solid', 'Liquid', 'Gas', 'Plasma'],
                'correct_index' => 0,
                'explanation' => 'Solids keep their shape unless a force is applied.',
            ],
            [
                'subject' => 'Science',
                'question' => 'Water changes to gas at which process?',
                'options' => ['Condensation', 'Evaporation', 'Freezing', 'Melting'],
                'correct_index' => 1,
                'explanation' => 'Evaporation turns liquid water into water vapor.',
            ],
            [
                'subject' => 'Language',
                'question' => 'Choose the correct sentence in English.',
                'options' => ['He go to school.', 'He goes to school.', 'He going school.', 'He gone school.'],
                'correct_index' => 1,
                'explanation' => 'Third-person singular uses “goes”.',
            ],
            [
                'subject' => 'Language',
                'question' => 'Urdu: “Mein school jata hoon” means:',
                'options' => ['I go to school.', 'I eat at school.', 'I sleep at school.', 'I teach at school.'],
                'correct_index' => 0,
                'explanation' => 'It translates to “I go to school.”',
            ],
            [
                'subject' => 'Social Studies',
                'question' => 'Nomadic communities are best described as:',
                'options' => ['People who never move', 'People who move seasonally', 'People who live in cities', 'People who live only by the sea'],
                'correct_index' => 1,
                'explanation' => 'Nomads often move with seasons for grazing and resources.',
            ],
            [
                'subject' => 'Social Studies',
                'question' => 'Why do Himalayan nomads move camps?',
                'options' => ['For tourism', 'For better grazing land', 'For school holidays', 'For festivals only'],
                'correct_index' => 1,
                'explanation' => 'They move to find pasture for livestock.',
            ],
            [
                'subject' => 'Mathematics',
                'question' => 'What is 3/5 of 10?',
                'options' => ['3', '5', '6', '8'],
                'correct_index' => 2,
                'explanation' => '3/5 of 10 is (3 * 10) / 5 = 6.',
            ],
            [
                'subject' => 'Science',
                'question' => 'Which is an example of a liquid?',
                'options' => ['Ice', 'Water', 'Steam', 'Rock'],
                'correct_index' => 1,
                'explanation' => 'Water is a liquid at room temperature.',
            ],
        ];

        foreach (SchoolClass::all() as $class) {
            foreach ($questions as $question) {
                QuizQuestion::create([
                    'class_id' => (string) $class->getKey(),
                    'subject' => $question['subject'],
                    'question' => $question['question'],
                    'options' => $question['options'],
                    'correct_index' => $question['correct_index'],
                    'explanation' => $question['explanation'],
                ]);
            }
        }
    }
}
