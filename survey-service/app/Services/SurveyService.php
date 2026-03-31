<?php

namespace App\Services;

use App\Actions\Survey\CreateAction;
use App\Actions\Survey\ListAction;
use App\Actions\Survey\UpdateAction;
use App\DTOs\Survey\SurveyDTO;
use App\DTOs\Survey\SurveyFilterDTO;
use App\Models\Survey;

class SurveyService
{
  /**
   * Get a paginated list of surveys with optional filters and selected columns
   * @param SurveyFilterDTO $dto The data transfer object containing filters, pagination, and column selection information
   * @return array An array containing the paginated list of surveys and total count
   */
  public function list(SurveyFilterDTO $dto): array
  {
    return (new ListAction())->execute($dto);;
  }

  /**
   * Get a minimal list of surveys (id and name only) for select options
   * @param SurveyFilterDTO $dto The data transfer object containing filters and pagination information
   * @return array An array containing the paginated list of surveys
   */
  public function minimalList(SurveyFilterDTO $dto): array
  {
    return (new ListAction())->execute($dto);
  }

  /**
   * Get detailed information about a specific survey, including its sections and questions
   * $var int $id The ID of the survey to retrieve details for
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the survey with the specified ID is not found
   * @return Survey
   */
  public function getDetails(int $id): Survey
  {
    return Survey::with(['sections.questions', 'options'])->findOrFail($id);
  }

  /**
   * Create a new survey along with its sections and options
   * @param SurveyDTO $dto The data transfer object
   * @return Survey
   */
  public function create(SurveyDTO $dto): Survey
  {
    return (new CreateAction())->execute($dto);
  }

  /**
   * Update an existing survey along with its sections and options
   * @param SurveyDTO $dto The data transfer object containing the updated survey information
   * @param int $id The ID of the survey to update
   * @return Survey
   */
  public function update(SurveyDTO $dto): Survey
  {
    return (new UpdateAction())->execute($dto);
  }

  /**
   * Delete a survey by its ID
   * @param int $id The ID of the survey to delete
   * @return bool
   */
  public function deleteById(int $id): bool
  {
    $survey = Survey::findOrFail($id);
    return $survey->delete();
  }
}
