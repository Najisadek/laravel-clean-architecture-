<?php

declare(strict_types=1);

namespace App\Application\User\DTOs;

/**
 * Login User DTO
 *
 * Input data for user authentication.
 */
final class LoginUserDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}

    /**
     * Create from array (e.g., request data)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password']
        );
    }
}
