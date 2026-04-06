<?php

namespace App\DTOs\Response;

use App\DTOs\DTO;
use App\DTOs\Section\SectionAnswerDTO;
use App\Models\Concrens\HasHelpersTrait;

readonly class ResponseDTO implements DTO
{
  use HasHelpersTrait;

  public function __construct(
    public ?int $id = null,
    public int $user_id,
    public int $survey_id,
    /** @var SectionAnswerDTO[] */
    public ?array $sections = [],
  ) {}

  /**
   * Create a new ResponseDTO instance from an array of data.
   *
   * @param array $data containing the data to create the ResponseDTO
   * @return ResponseDTO
   */
  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      user_id: $data['user_id'] ?? null,
      survey_id: $data['survey_id'] ?? null,

      sections: array_map(
        fn($section) => SectionAnswerDTO::fromArray($section),
        $data['sections'] ?? []
      ),
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'user_id' => $this->user_id,
      'survey_id' => $this->survey_id,
      'sections' => $this->sections,
    ];
  }
}
