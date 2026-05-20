<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'role' => ['required', Rule::in(['admin', 'teacher', 'student', 'parent'])],
            'dob' => ['nullable', 'date'],
            'guardian_name' => ['required_if:role,student', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'community_tribe' => ['required_if:role,student', 'string', 'max:100'],
            'current_camp_location' => ['nullable', 'string', 'max:255'],
            'preferred_language' => ['nullable', 'string', 'max:50'],
            'enrollment_date' => ['required_if:role,student', 'date'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
