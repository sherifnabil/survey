<?php

namespace App\Actions\User;

use App\Actions\Action;
use App\DTOs\DTO;
use App\DTOs\User\UserDTO;
use App\Models\User;

class CreateAction implements Action
{
  public function execute(DTO $dto): User
  {
    /** @var UserDTO $dto */
    $data = $dto->toArray();
    $data['password'] = bcrypt($dto->password);
    return User::create($data);
  }
}
