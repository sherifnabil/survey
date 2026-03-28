<?php

namespace App\Services;

use App\Actions\Survey\ListAction;
use App\DTOs\Survey\SurveyFilterDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\SurveyResource;
use Illuminate\Support\Collection;

class SurveyService
{
  /**
   * Get a paginated list of surveys with optional filters and selected columns
   */
  public function list(SurveyFilterDTO $dto): Collection|array
  {
    $action = (new ListAction())->execute($dto);
    return ResponseFormatter::cursorPaginationResponse(
      SurveyResource::collection($action['data']->items()),
      $action['data'],
      $action['total'],
    );
  }

  /**
   * Get a minimal list of surveys (id and name only) for select options
   */
  public function minimalList(SurveyFilterDTO $dto): Collection|array
  {
    $action = (new ListAction())->execute($dto);
    return ResponseFormatter::cursorPaginationResponse(
      $action['data']->items(),
      $action['data'],
      $action['total']
    );
  }
}
