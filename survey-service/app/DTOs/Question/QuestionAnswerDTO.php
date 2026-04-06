<?php

namespace App\DTOs\Question;

use App\DTOs\DTO;

readonly class QuestionAnswerDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public int $question_id,
    public ?string $title = null,
    public ?int $response_id = null,
    public ?int $option_id = null,
  ) {}

  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      response_id: $data['response_id'] ?? null,
      title: $data['title'] ?? null,
      option_id: $data['option_id'] ?? null,
      question_id: $data['question_id'],
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'question_id' => $this->question_id,
      'title' => $this->title,
      'response_id' => $this->response_id,
      'option_id' => $this->option_id,
    ];
  }
}
