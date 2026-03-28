<?php

namespace App\Actions\Survey;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Survey\SurveyFilterDTO;
use App\Models\Survey;
use Illuminate\Pagination\LengthAwarePaginator;

class ListAction implements Action
{
  public function execute(DTO $dto): LengthAwarePaginator
  {
    /** @var SurveyFilterDTO $dto */
    return Survey::query()
      ->filters($dto->toArray()['filters'])
      ->select($dto->columns)
      ->paginate($dto->toArray()['meta']['perPage'], $dto->toArray()['meta']['page']);
  }
}
