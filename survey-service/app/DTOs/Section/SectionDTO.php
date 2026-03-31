<?php

namespace App\DTOs\Section;

use App\DTOs\DTO;
use App\DTOs\Question\QuestionDTO;

readonly class SectionDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public string $name,
    public ?int $order = null,
    public ?int $survey_id = null,

    /** @var QuestionDTO[] */
    public array $questions = [],
  ) {}

  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      name: $data['name'],
      order: $data['order'] ?? null,
      survey_id: $data['survey_id'] ?? null,

      questions: array_map(
        fn($question) => QuestionDTO::fromArray($question),
        $data['questions'] ?? []
      ),
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
