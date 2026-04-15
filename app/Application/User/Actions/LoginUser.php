<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Application\User\DTOs\LoginUserDTO;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\User;
use Illuminate\Support\Facades\Hash;

final class LoginUser
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function execute(LoginUserDTO $dto): User
    {
        $domainUser = $this->repository->findByEmail($dto->email);

        if ($domainUser === null) {
            throw new InvalidCredentialsException;
        }

        if (! Hash::check($dto->password, $domainUser->password())) {
            throw new InvalidCredentialsException;
        }

        return $domainUser;
    }

    public function generateToken(User $user): string
    {
        return $user->getModel()->createToken('auth-token')->plainTextToken;
    }
}
