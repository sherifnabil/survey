<?php

namespace App\Actions\Response;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Response\ResponseDTO;
use App\Models\Concrens\SyncRelation;
use App\Models\Response;
use Illuminate\Support\Facades\DB;

class UpdateAction implements Action
{
  public function execute(DTO $dto): Response
  {
    /** @var ResponseDTO $dto */
    $response = Response::find($dto->id);

    return DB::transaction(function () use ($dto, $response) {
      // Only update answers – survey_id and user_id are considered immutable.
      $this->syncAnswersThroughSections($dto->sections, $response);

      return $response;
    });
  }

  /**
   * Synchronise answers for all sections.
   */
  private function syncAnswersThroughSections(array $sections, Response $response): void
  {
    foreach ($sections as $sectionDto) {
      $this->syncAnswers($response, $sectionDto->questions);
    }
  }

  /**
   * Synchronise answers for a single section.
   *
   * @param array $questions
   * @return void
   */
  private function syncAnswers(Response $response, array $questions): void
  {
    SyncRelation::sync(
      $response->answers(),
      $questions,
      function ($answer, $dto, $index) {
        $answer->update([
          'question_id' => $dto->question_id,
          'option_id' => $dto->option_id,
        ]);
        return $answer;
      },
      function ($relation, $dto, $index) {
        return $relation->create([
          'question_id' => $dto->question_id,
          'option_id' => $dto->option_id,
        ]);
      }
    );
  }
}
