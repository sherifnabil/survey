<?php

namespace App\DTOs\User;

use App\DTOs\DTO;

readonly class UserDTO implements DTO
{
  public function __construct(
    public ?int $id = null,
    public ?string $name,
    public string $email,
    public ?string $password = null,
  ) {}

  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      name: $data['name'] ?? null,
      email: $data['email'],
      password: $data['password'] ?? null,
    );
  }

  public function toArray(): array
  {
    $data = [
      'id' => $this->id,
      'name' => $this->name,
      'email' => $this->email,
      'password' => $this->password,
    ];

    if (is_null($data['password'])) unset($data['password']);

    return $data;
  }
}
