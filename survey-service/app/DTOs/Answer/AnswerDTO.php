<?php

namespace App\DTOs\Answer;

use App\DTOs\DTO;
use App\Models\Concrens\HasHelpersTrait;

readonly class AnswerDTO implements DTO
{
  use HasHelpersTrait;

  public function __construct(
    public ?int $id = null,
    public ?int $question_id = null,
    public ?int $option_id = null,
    public ?int $survey_id = null,
  ) {}

  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      question_id: $data['question_id'] ?? null,
      option_id: $data['option_id'] ?? null,
      survey_id: $data['survey_id'] ?? null,
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'question_id' => $this->question_id,
      'option_id' => $this->option_id,
      'survey_id' => $this->survey_id,
    ];
  }
}
