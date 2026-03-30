<?php

namespace App\Http\Requests\API;

use App\Enums\OptionType;
use App\Models\Option;
use Illuminate\Foundation\Http\FormRequest;

class OptionRequest extends FormRequest
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
        $surveyId = $this->route('survey') ?? $this->survey_id ?? null;
        $existingValues = $this->getExistingValues($surveyId);

        return [
            'name' => ['required', 'string', 'max:255'],
            'survey_id' => ['required', 'integer', 'exists:surveys,id'],
            'type' => ['nullable', 'string', 'in:' . OptionType::valuesAsString()],
            'value' => ['nullable', 'integer', 'not_in:' . $existingValues],
        ];
    }

    private function getExistingValues($surveyId): string
    {
        $query = Option::where('survey_id', $surveyId);
        if ($this->route('option')) {
            $query->where('id', '!=', $this->route('option'));
        }
        return $query->pluck('value')->join(',');
    }
}
