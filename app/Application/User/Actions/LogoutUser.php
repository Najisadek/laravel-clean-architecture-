<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Domain\User\Contracts\TokenGeneratorInterface;

/**
 * Logout User Action
 *
 * Handles user logout (token revocation) use case.
 */
final class LogoutUser
{
    public function __construct(
        private readonly TokenGeneratorInterface $tokenGenerator
    ) {}

    /**
     * Execute logout by revoking token
     */
    public function execute(string $token): void
    {
        $this->tokenGenerator->revoke($token);
    }
}
