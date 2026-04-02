<?php

namespace App\Services;

use App\Actions\User\CreateAction;
use App\Actions\User\ListAction;
use App\Actions\User\UpdateAction;
use App\DTOs\User\UserDTO;
use App\DTOs\User\UserFilterDTO;
use App\Models\User;

class UserService
{
  // Define the columns that are allowed for user lookup to prevent unauthorized access
  private $allowedColumns = ['id', 'email'];

  /**
   * Get a paginated list of users with optional filters and selected columns
   * @param UserFilterDTO $dto The data transfer object containing filters, pagination, and column selection information
   * @return array The formatted paginated response containing the list of users and pagination metadata
   */
  public function list(UserFilterDTO $dto): array
  {
    return (new ListAction)->execute($dto);
  }

  /**
   * Create a new user in the database using the provided data transfer object.
   * @param UserDTO $dto The data transfer object containing user information for registration
   * @return User The created user model instance
   */
  public function create(UserDTO $dto): User
  {
    return (new CreateAction)->execute($dto);
  }

  /**
   * Update an existing user by its ID
   * @param UserDTO $dto The data to update the user
   * @return User
   */
  public function update(UserDTO $dto): User
  {
    return (new UpdateAction)->execute($dto);
  }

  /**
   * Retrieve a user by a specified column and value, ensuring that only allowed columns are used for lookup.
   * @param string $column The column name to search by (e.g., 'id' or 'email')
   * @param int|string $value The value to search for in the specified column
   * @throws \InvalidArgumentException If an invalid column name is provided
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no user is found with the specified column and value
   * @return User The user model instance that matches the specified column and
   */
  public function getUserBy(string $column, int|string $value, $relations = []): User
  {
    if (!in_array($column, $this->allowedColumns)) throw new \InvalidArgumentException("Invalid column name: $column");

    return User::with($relations)->where($column, $value)->firstOrFail();
  }

  /**
   * Get detailed information about a specific user, including its questions
   * $var int $id The ID of the user to retrieve details for
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user with the specified ID is not found
   * @return User
   */
  public function getDetails(int $id): User
  {
    return $this->getUserBy('id', $id);
  }


  /**
   * Delete a user by its ID
   * @param int $id The ID of the user to delete
   * @return bool Returns true if the user was successfully deleted, false otherwise
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user with the specified ID is not found
   */
  public function delete(int $id): bool
  {

    if ($user = $this->getUserBy('id', $id)) {
      $user->delete();
      return true;
    }
    return false;
  }
}
