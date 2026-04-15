<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\User;
use App\Domain\User\ValueObjects\UserId;

/**
 * Get User By ID Action
 *
 * Handles fetching a user by their ID.
 */
final class GetUserById
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    /**
     * Execute fetch
     *
     * @throws UserNotFoundException
     */
    public function execute(string $id): User
    {
        $user = $this->repository->findById(new UserId($id));

        if ($user === null) {
            throw new UserNotFoundException;
        }

        return $user;
    }
}
