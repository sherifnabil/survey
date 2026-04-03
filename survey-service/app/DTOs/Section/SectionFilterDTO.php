<?php

namespace App\DTOs\Section;

use App\DTOs\DTO;
use App\Models\Concrens\HasHelpersTrait;

readonly class SectionFilterDTO implements DTO
{
  use HasHelpersTrait;

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
        'order' => $data['order'] ?? null,
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
