<?php

namespace App\DTOs\Survey;

use App\DTOs\DTO;

readonly class SurveyDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public string $name,
    public string $description,
    public bool $active = true,
    public array $sections = [],
    public array $options = [],
  ) {}

  public static function fromRequest(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      name: $data['name'],
      description: $data['description'],
      active: $data['active'] ?? true,

      sections: $data['sections'] ?? [],
      options: $data['options'] ?? [],
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
}
