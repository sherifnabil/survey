<?php

namespace App\Actions\Section;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Section\SectionDTO;
use App\Models\Concrens\SyncRelation;
use App\Models\Section;

class UpdateAction implements Action
{
  public function execute(DTO $dto): Section
  {
    /** @var SectionDTO $dto */
    if ($section = Section::findOrFail($dto->id)) {
      // Prevent updating survey_id and order through this action
      $section->update($dto->toArrayExceptColumns(['survey_id', 'order']));

      $this->syncQuestions($section, $dto->questions);
    }

    return $section->refresh();
  }

  private function syncQuestions(Section $section, $newQuestions): void
  {
    $order = $section->questions()->get('order')->max('order') + 1;

    SyncRelation::sync(
      relation: $section->questions(),
      items: $newQuestions,
      updateCallback: function ($question, $dto) use (&$order) {
        $question->update([
          'title' => $dto->title,
          'order' => $order++,
        ]);
      },
      createCallback: function ($relation, $dto) use (&$order) {
        return $relation->create([
          'title' => $dto->title,
          'order' => $order++,
        ]);
      }
    );
  }
}
