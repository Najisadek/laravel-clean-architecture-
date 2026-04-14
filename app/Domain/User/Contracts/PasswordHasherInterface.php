<?php

declare(strict_types=1);

namespace App\Domain\User\Contracts;

/**
 * Password Hasher Interface
 *
 * Abstraction for password hashing operations.
 */
interface PasswordHasherInterface
{
    /**
     * Hash a plain password
     */
    public function hash(string $plainPassword): string;

    /**
     * Verify a plain password against a hash
     */
    public function verify(string $plainPassword, string $hashedPassword): bool;
}
