<?php

namespace App\Actions\User;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\User\UserFilterDTO;
use App\Models\User;

class ListAction implements Action
{
  public function execute(DTO $dto): array
  {
    /** 
     * @var UserFilterDTO $dto
     * The query is built using the filters and columns specified in the DTO. 
     * The data is then paginated using page-based pagination,
     */
    $perPage = $dto->meta['perPage']  ?? config('response-settings.per_page');

    $paginator = User::query()
      ->filters($dto->filters)
      ->select($dto->columns);

    if ($perPage == -1) $perPage = $paginator->count();

    $paginator = $paginator->paginate($perPage, $dto->meta['page']);

    return [
      'paginator' => $paginator,
      'data' => $paginator->items(),
    ];
  }
}
