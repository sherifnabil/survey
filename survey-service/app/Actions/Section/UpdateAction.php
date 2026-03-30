<?php

namespace App\Actions\Section;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Section\SectionDTO;
use App\Models\Section;

class UpdateAction implements Action
{
  public function execute(DTO $dto): ?Section
  {
    /** @var SectionDTO $dto */
    if ($section = Section::findOrFail($dto->id)) {
      $section->update($dto->toArrayExceptColumns(['survey_id'])); // Prevent updating survey_id through this action

      $section->questions()->delete(); // Delete existing questions
      // Create new questions with proper ordering
      $questions = collect($dto->questions)->map(fn($question, $index) => array_merge($question, ['order' => $index + 1]))->toArray();
      $section->questions()->createMany($questions);
    }

    return $section;
  }
}
