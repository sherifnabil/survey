<?php

namespace App\DTOs\Option;

use App\DTOs\DTO;

readonly class OptionDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public string $name,
    public ?string $type,
    public ?int $value = null,
    public ?int $survey_id = null,
  ) {}

  public static function fromArray(array $data): self
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
