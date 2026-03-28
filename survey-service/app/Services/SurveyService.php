<?php

namespace App\Services;

use App\Actions\Survey\ListAction;
use App\DTOs\Survey\SurveyFilterDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;
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

  /**
   * Get detailed information about a specific survey, including its sections and questions
   * $var int $id The ID of the survey to retrieve details for
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the survey with the specified ID is not found
   * @return JsonResponse
   */
  public function getDetails(int $id): JsonResponse
  {
    $survey = Survey::with(['sections.questions', 'options'])->findOrFail($id);
    return ResponseFormatter::singleItemResponse(new SurveyResource($survey));
  }
}
