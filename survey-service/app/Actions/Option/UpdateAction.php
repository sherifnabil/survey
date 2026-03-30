<?php

namespace App\Actions\Option;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Section\OptionDTO;
use App\Models\Option;

class UpdateAction implements Action
{
  public function execute(DTO $dto): ?Option
  {
    /** @var OptionDTO $dto */
    if ($section = Option::findOrFail($dto->id)) {
      $section->update($dto->toArrayExceptColumns(['survey_id']));
    }

    return $section;
  }
}
