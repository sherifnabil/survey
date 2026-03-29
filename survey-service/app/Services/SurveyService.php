<?php

namespace App\Services;

use App\Actions\Survey\CreateAction;
use App\Actions\Survey\ListAction;
use App\Actions\Survey\UpdateAction;
use App\DTOs\Survey\SurveyDTO;
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

  /**
   * Create a new survey along with its sections and options
   * @param SurveyDTO $dto The data transfer object
   * @return JsonResponse
   */
  public function create(SurveyDTO $dto): JsonResponse
  {
    $survey = (new CreateAction())->execute($dto);
    return ResponseFormatter::singleItemResponse(new SurveyResource($survey));
  }

  /**
   * Update an existing survey along with its sections and options
   * @param SurveyDTO $dto The data transfer object containing the updated survey information
   * @param int $id The ID of the survey to update
   * @return JsonResponse
   */
  public function update(SurveyDTO $dto, int $id): JsonResponse
  {
    $survey = (new UpdateAction())->execute($dto, $id);
    return ResponseFormatter::singleItemResponse(new SurveyResource($survey));
  }

  /**
   * Delete a survey by its ID
   * @param int $id The ID of the survey to delete
   * @return JsonResponse
   */
  public function delete(int $id): JsonResponse
  {
    $survey = Survey::find($id);
    if ($survey) $survey->delete();

    return ResponseFormatter::singleItemResponse(data: null);
  }
}
