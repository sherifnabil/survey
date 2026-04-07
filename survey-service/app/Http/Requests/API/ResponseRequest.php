<?php

namespace App\Http\Requests\API;

use App\Models\Option;
use Illuminate\Foundation\Http\FormRequest;

class ResponseRequest extends FormRequest
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
        $optionsIdsString = Option::where('survey_id', $this->survey_id)->get('id')->pluck('id')->join(',');

        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'survey_id' => ['required', 'integer', 'exists:surveys,id'],
            'sections' => ['required', 'array'],
            'sections.*.id' => ['nullable', 'integer'],
            'sections.*.section_id' => ['required', 'integer', 'exists:sections,id'],
            'sections.*.questions' => ['required', 'array'],
            'sections.*.questions.*.id' => ['nullable', 'integer'],
            'sections.*.questions.*.question_id' => ['required', 'integer', 'exists:questions,id'],
            'sections.*.questions.*.option_id' => ['nullable', 'integer', 'in:' . $optionsIdsString],
        ];
    }
}
