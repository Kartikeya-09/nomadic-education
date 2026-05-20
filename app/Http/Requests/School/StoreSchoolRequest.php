<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'school_code' => ['required', 'string', 'max:50', 'unique:schools,school_code'],
            'community_tribe' => ['nullable', 'string', 'max:100'],
            'current_camp_location' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'head_teacher_id' => ['nullable', 'string', 'max:50'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
