<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teacher = $this->route('teacher');
        $teacherId = $teacher ? $teacher->getKey() : null;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($teacherId)],
            'password' => ['sometimes', 'string', 'min:8'],
            'phone' => ['sometimes', 'string', 'max:30'],
            'subject_specialization' => ['nullable', 'string', 'max:100'],
            'qualification' => ['nullable', 'string', 'max:100'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'current_camp_location' => ['nullable', 'string', 'max:255'],
            'assigned_community' => ['nullable', 'string', 'max:100'],
            'is_volunteer' => ['sometimes', 'boolean'],
            'preferred_language' => ['nullable', 'string', 'max:50'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
