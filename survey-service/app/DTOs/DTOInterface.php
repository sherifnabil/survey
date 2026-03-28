<?php

namespace App\DTOs;

interface DTOInterface
{
  public static function fromRequest(array $data): self;
}
