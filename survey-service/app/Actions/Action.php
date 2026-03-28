<?php

namespace App\Actions;

use App\DTOs\DTO;

interface Action
{
  public function execute(DTO $data);
}
