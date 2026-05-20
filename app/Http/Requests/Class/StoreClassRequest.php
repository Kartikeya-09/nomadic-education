<?php

namespace App\Http\Requests\Class;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'school_id' => ['required', 'string', 'max:50'],
            'teacher_id' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['required', 'string', 'max:20'],
            'section' => ['nullable', 'string', 'max:20'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
