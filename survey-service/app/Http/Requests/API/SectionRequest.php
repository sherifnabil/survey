<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'survey_id' => ['required', 'integer', 'exists:surveys,id'],
            'order' => ['nullable', 'integer'],
            'questions' => ['nullable', 'array'],
            'questions.*.title' => ['required', 'string'],
            'questions.*.id' => ['sometimes', 'nullable', 'integer', 'exists:questions,id'],
        ];
    }
}
