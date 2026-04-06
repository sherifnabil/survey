<?php

namespace App\DTOs\Response;

use App\DTOs\DTO;

readonly class ResponseFilterDTO implements DTO
{
  public function __construct(
    public array $filters = [],
    public array $meta = [],
    public array $columns = ['*'],
  ) {}

  public static function fromRequest(array $data, array $columns = ['*']): self
  {
    return new self(
      filters: [
        'name' => $data['name'] ?? null,
        'type' => $data['type'] ?? null,
        'survey_id' => $data['survey_id'] ?? null,
      ],
      meta: [
        'perPage' => $data['perPage'] ?? config('response-settings.per_page'),
        'page' => $data['page'] ?? config('response-settings.page'),
        'cursor' => $data['cursor'] ?? null,
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
