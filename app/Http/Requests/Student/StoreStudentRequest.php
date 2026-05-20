<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'class_id' => ['nullable', 'string', 'max:50'],
            'dob' => ['nullable', 'date'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'community_tribe' => ['required', 'string', 'max:100'],
            'current_camp_location' => ['nullable', 'string', 'max:255'],
            'preferred_language' => ['nullable', 'string', 'max:50'],
            'enrollment_date' => ['required', 'date'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
