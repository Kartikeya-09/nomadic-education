<?php

namespace App\Http\Requests\Parent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $parent = $this->route('parent');
        $parentId = $parent ? $parent->getKey() : null;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($parentId)],
            'password' => ['sometimes', 'string', 'min:8'],
            'phone' => ['sometimes', 'string', 'max:30'],
            'guardian_of' => ['nullable', 'array'],
            'guardian_of.*' => ['string', 'max:255'],
            'community_tribe' => ['nullable', 'string', 'max:100'],
            'current_camp_location' => ['nullable', 'string', 'max:255'],
            'preferred_language' => ['nullable', 'string', 'max:50'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
