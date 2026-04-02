<?php

namespace App\Services;

use App\DTOs\DTO;
use App\DTOs\User\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
  public function __construct(private UserService $userService) {}

  /**
   * Register a new user and generate an authentication token for them.
   * @param DTO $dto The data transfer object containing user registration information
   * @return array An array containing the registered user and their authentication token
   */
  public function register(DTO $dto): array
  {
    /** @var UserDTO $dto */
    $user = $this->userService->create($dto);
    $token = $this->createToken($user);

    return [$user, $token];
  }

  private function createToken(User $user): string
  {
    return $user->createToken('user-app-token')->plainTextToken;
  }

  /**
   * Authenticate a user using their email and password, and return the user along with an authentication token if successful.
   * @param UserDTO $dto The data transfer object containing the user's email and password for authentication
   * @throws \InvalidArgumentException If the email or password is invalid
   * @return array An array containing the authenticated user and their authentication token
   */
  public function login(UserDTO $dto): array
  {
    $user = $this->userService->getUserBy(
      column: 'email',
      value: $dto->email
    );

    if (!$user || !Hash::check($dto->password, $user->password)) {
      throw new \InvalidArgumentException("Invalid email or password");
    }

    $token = $this->createToken($user);

    return [$user, $token];
  }

  /**
   * Log out the user by deleting all of their authentication tokens.
   * @param User $user The user to log out
   * @return void
   */
  public function logout(User $user): void
  {
    $user->tokens()->delete();
  }

  /**
   * Log out the user by deleting all of their authentication tokens.
   * @param User $user the request user 
   * @return User the requested user data
   */
  public function loggedUser(User $user): User
  {
    return $this->userService->getUserBy(
      column: 'email',
      value: $user->email
    );
  }
}
