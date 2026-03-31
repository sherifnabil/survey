<?php

namespace App\Actions\Survey;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Survey\SurveyDTO;
use App\Models\Section;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;

class CreateAction implements Action
{
  public function execute(DTO $dto): Survey
  {
    /** @var SurveyDTO $dto */
    return DB::transaction(function () use ($dto) { // use database transaction to ensure data integrity.
      $survey = Survey::create([
        'name' => $dto->name,
        'description' => $dto->description,
        'active' => $dto->active,
      ]);

      $this->createSectios($dto->sections, $survey);
      $this->createOptions($dto->options, $survey);

      return $survey;
    });
  }

  private function createSectios(array $sections, Survey $survey): void
  {
    foreach ($sections as $index => $sectionDTO) {
      $section = $survey->sections()->create([
        'name' => $sectionDTO->name,
        'order' => $sectionDTO->order ?? ($index + 1),
      ]);
      $this->createQuestions($sectionDTO->questions, $section);
    }
  }

  private function createQuestions(array $questions, Section $section): void
  {
    foreach ($questions as $index => $questionDTO) {
      $section->questions()->create([
        'title' => $questionDTO->title,
        'order' => $questionDTO->order ?? ($index + 1),
      ]);
    }
  }

  private function createOptions(array $options, Survey $survey): void
  {
    foreach ($options as $optionDTO) {
      $survey->options()->create([
        'name' => $optionDTO->name,
        'value' => $optionDTO->value,
        'type' => $optionDTO->type,
      ]);
    }
  }
}
