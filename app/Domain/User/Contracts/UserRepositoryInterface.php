<?php

declare(strict_types=1);

namespace App\Domain\User\Contracts;

use App\Domain\User\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserId;

/**
 * User Repository Interface
 *
 * Defines the contract for user persistence.
 * Infrastructure implementations must adhere to this contract.
 */
interface UserRepositoryInterface
{
    /**
     * Find user by ID
     */
    public function findById(UserId $id): ?User;

    /**
     * Find user by email
     */
    public function findByEmail(Email $email): ?User;

    /**
     * Check if email exists
     */
    public function existsByEmail(Email $email): bool;

    /**
     * Save user (create or update)
     */
    public function save(User $user): void;

    /**
     * Delete user by ID
     */
    public function delete(UserId $id): void;
}
