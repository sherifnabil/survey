<?php

namespace App\Services;

use App\Actions\Section\CreateAction;
use App\Actions\Section\ListAction;
use App\Actions\Section\UpdateAction;
use App\DTOs\Section\SectionDTO;
use App\DTOs\Section\SectionFilterDTO;
use App\Models\Section;

class SectionService
{
  /**
   * Get a paginated list of sections with optional filters and selected columns
   * @param SectionFilterDTO $dto The data transfer object containing filters, pagination, and column selection information
   * @return array The formatted paginated response containing the list of sections and pagination metadata
   */
  public function list(SectionFilterDTO $dto): array
  {
    return (new ListAction)->execute($dto);
  }

  /**
   * Get detailed information about a specific section, including its questions
   * $var int $id The ID of the section to retrieve details for
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the section with the specified ID is not found
   * @return Section
   */
  public function getDetails(int $id): Section
  {
    return Section::with(['questions'])->findOrFail($id);
  }

  /**
   * Create a new section with the provided data
   * @param SectionDTO $data The data to create the section with
   * @return Section
   */
  public function create(SectionDTO $dto): Section
  {
    return (new CreateAction)->execute($dto);
  }

  /**
   * Update an existing section by its ID
   * @param SectionDTO $data The data to update the section with
   * @return Section
   */
  public function update(SectionDTO $data): Section
  {
    return (new UpdateAction)->execute($data);
  }

  /**
   * Delete a section by its ID
   * @param int $id The ID of the section to delete
   * @return bool Returns true if the section was successfully deleted, false otherwise
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the section with the specified ID is not found
   */
  public function delete(int $id): bool
  {

    if ($section = Section::findOrFail($id)) {
      $section->questions()->delete(); // Delete related questions first to maintain referential integrity
      $section->delete();
      return true;
    }
    return false;
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
