<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'class_id' => ['sometimes', 'string', 'max:50'],
            'teacher_id' => ['sometimes', 'string', 'max:50'],
            'date' => ['sometimes', 'date'],
            'topic' => ['nullable', 'string', 'max:255'],
            'objectives' => ['nullable', 'string', 'max:1000'],
            'materials' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
