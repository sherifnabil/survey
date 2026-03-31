<?php

namespace App\Actions;

use App\DTOs\DTO;
use Illuminate\Database\Eloquent\Model;

interface Action
{
  public function execute(DTO $data): Model|array;
}
