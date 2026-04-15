<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\User;

final class LogoutUser
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function execute(User $user): void
    {
        $user->getModel()->tokens()->delete();
    }
}
