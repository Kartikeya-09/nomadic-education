<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $studentId = $student ? $student->getKey() : null;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($studentId)],
            'password' => ['sometimes', 'string', 'min:8'],
            'class_id' => ['nullable', 'string', 'max:50'],
            'dob' => ['nullable', 'date'],
            'guardian_name' => ['sometimes', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'community_tribe' => ['sometimes', 'string', 'max:100'],
            'current_camp_location' => ['nullable', 'string', 'max:255'],
            'preferred_language' => ['nullable', 'string', 'max:50'],
            'enrollment_date' => ['sometimes', 'date'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
