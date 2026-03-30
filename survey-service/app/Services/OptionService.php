<?php

namespace App\Services;

use App\Actions\Option\CreateAction;
use App\Actions\Option\ListAction;
use App\Actions\Option\UpdateAction;
use App\DTOs\Option\OptionDTO;
use App\DTOs\Option\OptionFilterDTO;
use App\Enums\OptionType;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\OptionResource;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Ramsey\Collection\Collection;

class OptionService
{
  /**
   * Get a paginated list of options with optional filters and selected columns
   * @param OptionFilterDTO $dto The data transfer object containing filters, pagination, and column selection information
   * @return JsonResponse The formatted paginated response containing the list of options and pagination metadata
   */
  public function list(OptionFilterDTO $dto): Collection|array
  {
    $action = (new ListAction)->execute($dto);
    return ResponseFormatter::paginationResponse(OptionResource::collection($action['data']), $action['paginator']);
  }

  /**
   * Get detailed information about a specific option
   * $var int $id The ID of the option to retrieve details for
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the option with the specified ID is not found
   * @return JsonResponse
   */
  public function getDetails(int $id): JsonResponse
  {
    $option = Option::findOrFail($id);
    return ResponseFormatter::singleItemResponse(new OptionResource($option));
  }

  /**
   * Create a new option with the provided data
   * @param OptionDTO $dto The data to create the option with
   * @return JsonResponse
   */
  public function create(OptionDTO $dto): JsonResponse
  {
    $action  = (new CreateAction)->execute($dto);
    return ResponseFormatter::singleItemResponse(new OptionResource($action));
  }

  /**
   * Update an existing option by its ID
   * @param OptionDTO $data The data to update the option with
   * @return JsonResponse
   */
  public function update(OptionDTO $data): JsonResponse
  {
    $action = (new UpdateAction)->execute($data);
    return ResponseFormatter::singleItemResponse(new OptionResource($action));
  }

  /**
   * Delete a option by its ID
   * @param int $id The ID of the option to delete
   * @return JsonResponse
   */
  public function delete(int $id): JsonResponse
  {
    if ($option = Option::findOrFail($id)) {
      $option->delete();
    }
    return ResponseFormatter::singleItemResponse(data: null);
  }

  /**
   * Get a list of all available option types
   * @return JsonResponse The formatted response containing the list of option types
   */
  public function getTypes(): JsonResponse
  {
    $types = collect(OptionType::cases())->map(fn($type) => $type->value);
    return ResponseFormatter::singleItemResponse($types);
  }
}
