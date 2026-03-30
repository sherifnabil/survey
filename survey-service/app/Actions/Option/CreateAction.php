<?php

namespace App\Actions\Option;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\Section\OptionDTO;
use App\Models\Option;

class CreateAction implements Action
{
  public function execute(DTO $dto): Option
  {
    /** @var OptionDTO $dto */
    $option = Option::create($dto->toArray());
    return $option;
  }
}
