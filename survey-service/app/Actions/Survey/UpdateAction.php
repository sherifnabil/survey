<?php

namespace App\Actions\Survey;

use App\Actions\Action;
use App\DTOs\DTO;
use App\Models\Concrens\SyncRelation;
use App\Models\Section;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;

class UpdateAction implements Action
{
  public function execute(DTO $dto): Survey
  {
    // use database transaction to ensure data integrity.
    return DB::transaction(function () use ($dto) {
      $survey = Survey::findOrFail($dto->id);
      $survey->update([
        'name' => $dto->name,
        'description' => $dto->description,
        'active' => $dto->active,
      ]);

      $this->syncSections($survey, $dto->sections);
      $this->syncOptions($survey, $dto->options);
      return $survey->refresh()->load(['sections.questions', 'options']);
    });
  }

  private function syncSections(Survey $survey, $newSections): void
  {
    // Get the maximum order value among existing sections for the survey
    $order = Section::where('survey_id', $survey->id)->get('order')->max('order') + 1;

    SyncRelation::sync(
      relation: $survey->sections(),
      items: $newSections,
      updateCallback: function ($section, $dto) use (&$order) {
        $order++;
        $section->update([
          'name' => $dto->name,
          'order' => $dto->order ?? $order,
        ]);

        $this->syncQuestions($section, $dto->questions);
      },
      createCallback: function ($relation, $dto) use (&$order) {
        $section = $relation->create([
          'name' => $dto->name,
          'order' => $order++,
        ]);

        $this->createQuestions($section, $dto->questions);
        return $section;
      }
    );
  }

  private function syncQuestions(Section $section, $newQuestions): void
  {
    SyncRelation::sync(
      relation: $section->questions(),
      items: $newQuestions,
      updateCallback: function ($question, $dto, $index) {
        $question->update([
          'title' => $dto->title,
          'order' => $dto->order ?? ($index + 1),
        ]);
      },
      createCallback: function ($relation, $dto, $index) {
        return $relation->create([
          'title' => $dto->title,
          'order' => $dto->order ?? ($index + 1),
        ]);
      }
    );
  }

  private function createQuestions(Section $section, $questions): void
  {
    foreach ($questions as $index => $questionDTO) {
      $section->questions()->create([
        'title' => $questionDTO->title,
        'order' => $questionDTO->order ?? ($index + 1),
      ]);
    }
  }

  private function syncOptions(Survey $survey, $newOptions): void
  {
    SyncRelation::sync(
      relation: $survey->options(),
      items: $newOptions,
      updateCallback: function ($option, $dto) {
        $option->update([
          'name' => $dto->name,
          'value' => $dto->value,
          'type' => $dto->type,
        ]);
      },
      createCallback: function ($relation, $dto) {
        return $relation->create([
          'name' => $dto->name,
          'value' => $dto->value,
          'type' => $dto->type,
        ]);
      }
    );
  }
}
