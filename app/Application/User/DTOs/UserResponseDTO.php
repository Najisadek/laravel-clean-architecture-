<?php

declare(strict_types=1);

namespace App\Application\User\DTOs;

/**
 * User Response DTO
 *
 * Output data returned from use cases.
 */
final class UserResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $createdAt,
        public readonly ?string $token = null
    ) {}

    /**
     * Convert to array for serialization
     */
    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->createdAt,
        ];

        if ($this->token !== null) {
            $data['token'] = $this->token;
        }

        return $data;
    }
}
