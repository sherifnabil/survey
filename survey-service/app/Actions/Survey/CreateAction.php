<?php

namespace App\Actions\Survey;

use App\Actions\Action;
use App\DTOs\DTO;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;

class CreateAction implements Action
{
  public function execute(DTO $dto): Survey
  {
    // use database transaction to ensure data integrity.
    $survey = DB::transaction(function () use ($dto) {
      $survey = Survey::create([
        'name' => $dto->name,
        'description' => $dto->description,
        'active' => $dto->active,
      ]);

      // Create sections and questions
      foreach ($dto->sections as $index => $sectionData) {
        $section = $survey->sections()->create([
          'name' => $sectionData['name'] ?? '',
          'order' => $index + 1, // Ensure order starts from 1 and is sequential
        ]);

        // Create questions for the section with proper ordering
        $questions = collect($sectionData['questions'] ?? [])->map(
          fn($question, $index) => array_merge($question, ['order' => $index + 1])
        )->toArray();
        $section->questions()->createMany($questions);
      }

      // Create survey options
      $survey->options()->createMany($dto->options);
      return $survey;
    });
    return $survey;
  }
}
