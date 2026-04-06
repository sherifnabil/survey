<?php

namespace App\DTOs\Section;

use App\DTOs\DTO;
use App\DTOs\Question\QuestionAnswerDTO;

readonly class SectionAnswerDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public ?int $section_id = null,
    public ?int $response_id = null,

    /** @var QuestionAnswerDTO[] */
    public array $questions = [],
  ) {}

  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      response_id: $data['response_id'] ?? null,
      section_id: $data['section_id'] ?? null,

      questions: array_map(
        fn($question) => QuestionAnswerDTO::fromArray($question),
        $data['questions'] ?? []
      ),
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'section_id' => $this->section_id,
      'questions' => $this->questions,
    ];
  }
}
