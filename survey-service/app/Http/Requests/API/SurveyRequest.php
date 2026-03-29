<?php

namespace App\Http\Requests\API;

use App\Enums\OptionType;
use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'active' => ['required', 'boolean'],

            // survey sections
            'sections' => ['nullable', 'array'],
            'sections.*.name' => ['required', 'string', 'max:255'],

            // section questions
            'sections.*.questions' => ['nullable', 'array'],
            'sections.*.questions.*.title' => ['required', 'string'],

            // survey options
            'options' => ['nullable', 'array'],
            'options.*.name' => ['required', 'string', 'max:255'],
            'options.*.value' => ['required', 'integer', 'distinct'],
            'options.*.type' => ['required', 'in:' . OptionType::valuesAsString()],

        ];
    }
}
