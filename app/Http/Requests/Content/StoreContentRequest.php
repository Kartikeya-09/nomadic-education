<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['pdf', 'video', 'audio', 'image', 'text'])],
            'class_id' => ['nullable', 'string', 'max:50'],
            'lesson_id' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file_url' => ['nullable', 'string', 'max:1000'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
