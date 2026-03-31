<?php

namespace App\Services;

use App\Actions\Option\CreateAction;
use App\Actions\Option\ListAction;
use App\Actions\Option\UpdateAction;
use App\DTOs\Option\OptionDTO;
use App\DTOs\Option\OptionFilterDTO;
use App\Enums\OptionType;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class OptionService
{
  /**
   * Get a paginated list of options with optional filters and selected columns
   * @param OptionFilterDTO $dto The data transfer object containing filters, pagination, and column selection information
   * @return array The formatted paginated response containing the list of options and pagination metadata
   */
  public function list(OptionFilterDTO $dto): array
  {
    return (new ListAction)->execute($dto);
  }

  /**
   * Get detailed information about a specific option
   * $var int $id The ID of the option to retrieve details for
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the option with the specified ID is not found
   * @return Option
   */
  public function getDetails(int $id): Option
  {
    return Option::findOrFail($id);
  }

  /**
   * Create a new option with the provided data
   * @param OptionDTO $dto The data to create the option with
   * @return JsonResponse
   */
  public function create(OptionDTO $dto): Option
  {
    return (new CreateAction)->execute($dto);
  }

  /**
   * Update an existing option by its ID
   * @param OptionDTO $data The data to update the option with
   * @return Option
   */
  public function update(OptionDTO $data): Option
  {
    return (new UpdateAction)->execute($data);
  }

  /**
   * Delete a option by its ID
   * @param int $id The ID of the option to delete
   * @return bool
   */
  public function delete(int $id): bool
  {
    if ($option = Option::findOrFail($id)) {
      $option->delete();
      return true;
    }
    return false;
  }

  /**
   * Get a list of all available option types
   * @return Collection A collection of option types
   */
  public function getTypes(): Collection
  {
    return collect(OptionType::cases())->map(fn($type) => $type->value);
  }
}
