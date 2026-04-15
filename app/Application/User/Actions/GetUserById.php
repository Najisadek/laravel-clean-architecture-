<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\User;

final class GetUserById
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function execute(string $id): User
    {
        $user = $this->repository->findById($id);

        if ($user === null) {
            throw new UserNotFoundException;
        }

        return $user;
    }
}
