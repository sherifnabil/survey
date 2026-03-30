<?php

namespace App\Actions\Option;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Section\OptionFilterDTO;
use App\Models\Option;

class ListAction implements Action
{
  public function execute(DTO $dto): array
  {
    /** 
     * @var OptionFilterDTO $dto
     * The query is built using the filters and columns specified in the DTO. 
     * The data is then paginated using page-based pagination,
     */
    $meta = $dto->toArray()['meta'];
    $perPage = $meta['perPage']  ?? config('response-settings.per_page');

    $paginator = Option::query()
      ->filters($dto->toArray()['filters'])
      ->select($dto->columns);

    if ($perPage == -1) $perPage = $paginator->count();

    $paginator = $paginator->paginate($perPage, $meta['page']);

    return [
      'paginator' => $paginator,
      'data' => $paginator->items(),
    ];
  }
}
