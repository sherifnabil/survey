<?php

namespace App\Actions\Response;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Response\ResponseDTO;
use App\Models\Response;
use Illuminate\Support\Facades\DB;

class CreateAction implements Action
{
  public function execute(DTO $dto): Response
  {
    /** @var ResponseDTO $dto */
    return DB::transaction(function () use ($dto) { // use database transaction to ensure data integrity.
      $response = Response::create([
        'survey_id' => $dto->survey_id,
        'user_id' => $dto->user_id,
      ]);

      $this->createAnswersThroughSections($dto->sections, $response);

      return $response->load(['user', 'survey']);
    });
  }

  private function createAnswersThroughSections(array $sections, Response $response): void
  {
    foreach ($sections as $sectionDto) {
      $this->createAnswers($response, $sectionDto->questions);
    }
  }

  private function createAnswers(Response $response, array $questions): void
  {
    foreach ($questions as $questionDTO) {
      $response->answers()->create([
        'question_id' => $questionDTO->question_id,
        'option_id' => $questionDTO->option_id,
      ]);
    }
  }
}
