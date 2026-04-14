<?php

declare(strict_types=1);

namespace App\Domain\User\Contracts;

/**
 * Token Generator Interface
 *
 * Abstraction for authentication token operations.
 */
interface TokenGeneratorInterface
{
    /**
     * Generate token for user
     */
    public function generate(array $payload): string;

    /**
     * Revoke a token
     */
    public function revoke(string $token): void;
}
