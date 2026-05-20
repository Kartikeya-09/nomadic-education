<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'class_id',
        'subject',
        'question',
        'options',
        'correct_index',
        'explanation',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'correct_index' => 'integer',
        ];
    }
}
