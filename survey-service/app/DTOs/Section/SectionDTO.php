<?php

namespace App\DTOs\Section;

use App\DTOs\DTO;

readonly class SectionDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public string $name,
    public int|null $order = null,
    public int|null $survey_id = null,
    public array $questions = [],
  ) {}

  public static function fromRequest(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      name: $data['name'],
      order: $data['order'] ?? null,
      survey_id: $data['survey_id'] ?? null,
      questions: $data['questions'] ?? [],
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'order' => $this->order,
      'survey_id' => $this->survey_id,
      'questions' => $this->questions,
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
