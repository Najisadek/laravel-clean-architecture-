<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Application\User\DTOs\RegisterUserDTO;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Exceptions\EmailAlreadyExistsException;
use App\Domain\User\User;
use Illuminate\Support\Facades\Hash;

final class RegisterUser
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function execute(RegisterUserDTO $dto): User
    {
        if ($this->repository->existsByEmail($dto->email)) {
            throw new EmailAlreadyExistsException;
        }

        return User::create(
            name: $dto->name,
            email: $dto->email,
            password: Hash::make($dto->password)
        );
    }
}
