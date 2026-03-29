<?php

namespace App\Enums;

enum OptionType: string
{
  case YES = 'yes';
  case NO = 'no';
  case MAYBE = 'maybe';

  public static function valuesAsString(): string
  {
    return implode(',', array_map(fn($case) => $case->value, self::cases()));
  }
}
