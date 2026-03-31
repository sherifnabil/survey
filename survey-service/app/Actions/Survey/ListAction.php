<?php

namespace App\Actions\Survey;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Survey\SurveyFilterDTO;
use App\Models\Survey;

class ListAction implements Action
{
  public function execute(DTO $dto): array
  {
    /** 
     * @var SurveyFilterDTO $dto
     *
     *  The query is built using the filters and columns specified in the DTO. 
     *  The total count of records matching the filters is retrieved before applying pagination. 
     *  The data is then paginated using cursor pagination,
     *  and both the paginated data and total count are returned in the response. 
     */
    $query = Survey::query()
      ->filters($dto->filters)
      ->select($dto->columns);

    $total = $query->count();

    $data = $query
      ->cursorPaginate($dto->meta['perPage']);

    return [
      'data' => $data,
      'total' => $total,
    ];
  }
}
