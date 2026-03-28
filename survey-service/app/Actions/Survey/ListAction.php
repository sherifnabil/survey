<?php

namespace App\Actions\Survey;

use App\DTOs\Survey\SurveyFilterDTO;
use App\Models\Survey;
use Illuminate\Pagination\LengthAwarePaginator;

class ListAction
{
  public function execute(SurveyFilterDTO $dto): LengthAwarePaginator
  {
    return Survey::query()
      ->filters($dto->toArray()['filters'])
      ->select($dto->columns)
      ->paginate($dto->toArray()['meta']['perPage'], $dto->toArray()['meta']['page']);
  }
}
