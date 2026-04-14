<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\UserId;
use DateTimeImmutable;

/**
 * User Domain Entity
 *
 * Represents the core user concept in our domain.
 * Contains business logic and invariants.
 */
final class User
{
    private UserId $id;

    private string $name;

    private Email $email;

    private Password $password;

    private DateTimeImmutable $createdAt;

    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        UserId $id,
        string $name,
        Email $email,
        Password $password,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt ?? now();
        $this->updatedAt = $updatedAt;
    }

    /**
     * Factory method for creating a new user
     */
    public static function create(
        string $name,
        Email $email,
        Password $password
    ): self {
        return new self(
            new UserId,
            $name,
            $email,
            $password
        );
    }

    // Getters
    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Update user name
     */
    public function updateName(string $name): void
    {
        $this->name = $name;
        $this->markAsUpdated();
    }

    /**
     * Update email address
     */
    public function updateEmail(Email $email): void
    {
        $this->email = $email;
        $this->markAsUpdated();
    }

    /**
     * Update password
     */
    public function updatePassword(Password $password): void
    {
        $this->password = $password;
        $this->markAsUpdated();
    }

    /**
     * Verify password against plain text
     */
    public function verifyPassword(string $plainPassword, callable $hashVerifier): bool
    {
        return $hashVerifier($plainPassword, $this->password->hashedValue());
    }

    /**
     * Convert to array for serialization
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name,
            'email' => $this->email->value(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    private function markAsUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable;
    }
}
