<?php

namespace App\Services;

use App\Actions\Section\CreateAction;
use App\Actions\Section\ListAction;
use App\Actions\Section\UpdateAction;
use App\DTOs\Section\SectionDTO;
use App\DTOs\Section\SectionFilterDTO;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Ramsey\Collection\Collection;

class SectionService
{
  /**
   * Get a paginated list of sections with optional filters and selected columns
   * @param SectionFilterDTO $dto The data transfer object containing filters, pagination, and column selection information
   * @return JsonResponse The formatted paginated response containing the list of sections and pagination metadata
   */
  public function list(SectionFilterDTO $dto): Collection|array
  {
    $action = (new ListAction)->execute($dto);
    return ResponseFormatter::paginationResponse(SectionResource::collection($action['data']), $action['paginator']);
  }

  /**
   * Get detailed information about a specific section, including its questions
   * $var int $id The ID of the section to retrieve details for
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the section with the specified ID is not found
   * @return JsonResponse
   */
  public function getDetails(int $id): JsonResponse
  {
    $section = Section::with(['questions'])->findOrFail($id);
    return ResponseFormatter::singleItemResponse(new SectionResource($section));
  }

  /**
   * Create a new section with the provided data
   * @param SectionDTO $data The data to create the section with
   * @return JsonResponse
   */
  public function create(SectionDTO $dto): JsonResponse
  {
    $action  = (new CreateAction)->execute($dto);
    return ResponseFormatter::singleItemResponse(new SectionResource($action));
  }

  /**
   * Update an existing section by its ID
   * @param SectionDTO $data The data to update the section with
   * @return JsonResponse
   */
  public function update(SectionDTO $data): JsonResponse
  {
    $action = (new UpdateAction)->execute($data);
    return ResponseFormatter::singleItemResponse(new SectionResource($action));
  }

  /**
   * Delete a section by its ID
   * @param int $id The ID of the section to delete
   * @return JsonResponse
   */
  public function delete(int $id): JsonResponse
  {

    if ($section = Section::findOrFail($id)) {
      $section->questions()->delete(); // Delete related questions first to maintain referential integrity
      $section->delete();
    }

    return ResponseFormatter::singleItemResponse(data: null);
  }

  /**
   * Get the next order value for a new section within a specific survey
   * @param int $surveyId The ID of the survey to get the next order value for
   * @return int The next order value for a new section within the specified survey
   */
  public function getNextOrder(int $surveyId): int
  {
    $maxOrder = Section::where('survey_id', $surveyId)->get('order')->max('order');
    return $maxOrder ? $maxOrder + 1 : 1;
  }
}
