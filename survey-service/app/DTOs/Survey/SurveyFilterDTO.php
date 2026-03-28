<?php

namespace App\DTOs\Survey;

use App\DTOs\DTO;

readonly class SurveyFilterDTO implements DTO
{
  public function __construct(
    public array $filters = [],
    public array $meta = [],
    public array $columns = ['*'],
  ) {}

  public static function fromRequest(array $data, array $columns = ['*']): self
  {
    $activeStatus = strlen($data['active']) == 0 ? null : filter_var($data['active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

    return new self(
      filters: [
        'name' => $data['name'] ?? null,
        'description' => $data['description'] ?? null,
        'active' => $activeStatus,
      ],
      meta: [
        'perPage' => $data['perPage'] ?? config('app.pagination.perPage'),
        'page' => $data['page'] ?? config('app.pagination.page'),
      ],
      columns: $columns,
    );
  }

  public function toArray(): array
  {
    return [
      'filters' => $this->filters,
      'meta' => $this->meta,
      'columns' => $this->columns,
    ];
  }
}
