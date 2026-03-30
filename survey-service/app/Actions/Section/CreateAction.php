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
    $section = Section::create($dto->toArray());
    $questions = collect($dto->questions)->map(fn($question, $index) => array_merge($question, ['order' => $index + 1]))->toArray();
    $section->questions()->createMany($questions);
    return $section;
  }
}
