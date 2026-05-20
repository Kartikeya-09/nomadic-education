<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $school = $this->route('school');
        $schoolId = $school ? $school->getKey() : null;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'school_code' => ['sometimes', 'string', 'max:50', Rule::unique('schools', 'school_code')->ignore($schoolId)],
            'community_tribe' => ['nullable', 'string', 'max:100'],
            'current_camp_location' => ['sometimes', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'head_teacher_id' => ['nullable', 'string', 'max:50'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
