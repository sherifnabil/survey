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
    if ($option = Option::findOrFail($dto->id)) {
      $option->update($dto->toArrayExceptColumns(['survey_id']));
    }

    return $option->refresh();
  }
}
