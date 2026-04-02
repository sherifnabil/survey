<?php

namespace App\Actions\User;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\User\UserDTO;
use App\Models\User;

class UpdateAction implements Action
{
  public function execute(DTO $dto): User
  {
    /** @var UserDTO $dto */
    if ($user = User::findOrFail($dto->id)) $user->update($dto->toArray());

    return $user->refresh();
  }
}
