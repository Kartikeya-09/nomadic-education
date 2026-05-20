<?php

namespace App\Http\Requests\Class;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'school_id' => ['sometimes', 'string', 'max:50'],
            'teacher_id' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['sometimes', 'string', 'max:20'],
            'section' => ['nullable', 'string', 'max:20'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
