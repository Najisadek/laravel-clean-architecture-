<?php

declare(strict_types=1);

namespace App\Application\User\DTOs;

/**
 * Register User DTO
 *
 * Input data for user registration.
 */
final class RegisterUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {}

    /**
     * Create from array (e.g., request data)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password']
        );
    }
}
