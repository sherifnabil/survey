<?php

namespace App\DTOs\Survey;

use App\DTOs\DTO;
use App\DTOs\Option\OptionDTO;
use App\DTOs\Section\SectionDTO;

readonly class SurveyDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public string $name,
    public string $description,
    public bool $active = true,

    /** @var SectionDTO[] */
    public array $sections = [],
    /** @var OptionDTO[] */
    public array $options = [],
  ) {}

  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      name: $data['name'],
      description: $data['description'],
      active: $data['active'] ?? true,

      // relationships
      sections: array_map(
        fn($section) => SectionDTO::fromArray($section),
        $data['sections'] ?? []
      ),
      options: array_map(
        fn($option) => OptionDTO::fromArray($option),
        $data['options'] ?? []
      ),
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'active' => $this->active,

      'sections' => $this->sections,
      'options' => $this->options,
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
