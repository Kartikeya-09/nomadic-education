<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'string', 'max:50'],
            'teacher_id' => ['required', 'string', 'max:50'],
            'date' => ['required', 'date'],
            'topic' => ['nullable', 'string', 'max:255'],
            'objectives' => ['nullable', 'string', 'max:1000'],
            'materials' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
