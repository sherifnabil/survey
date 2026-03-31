<?php

namespace App\Actions\Section;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Section\SectionDTO;
use App\Models\Section;

class CreateAction implements Action
{
  public function execute(DTO $dto): Section
  {
    /** @var SectionDTO $dto */
    $section = Section::create([
      'name' => $dto->name,
      'order' => $dto->order,
      'survey_id' => $dto->survey_id,
    ]);
    $this->createQuestions($dto->questions, $section);
    return $section;
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
}
