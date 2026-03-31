<?php

namespace App\DTOs\Question;

use App\DTOs\DTO;

readonly class QuestionDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public string $title,
    public ?int $order = null,
    public ?int $section_id = null,
  ) {}

  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      title: $data['title'],
      order: $data['order'] ?? null,
      section_id: $data['section_id'] ?? null,
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'order' => $this->order,
      'section_id' => $this->section_id,
    ];
  }
}
