<?php

namespace App\DTOs\Option;

use App\DTOs\DTO;

readonly class OptionDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public string $name,
    public string|null $type,
    public int|null $value = null,
    public int|null $survey_id = null,
  ) {}

  public static function fromRequest(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      name: $data['name'],
      type: $data['type'] ?? null,
      value: $data['value'] ?? null,
      survey_id: $data['survey_id'] ?? null,
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'type' => $this->type,
      'value' => $this->value,
      'survey_id' => $this->survey_id,
    ];
  }

  public function toArrayExceptColumns(array $columnsToExclude): array
  {
    $array = $this->toArray();
    foreach ($columnsToExclude as $column) {
      unset($array[$column]);
    }
    return $array;
  }
}
