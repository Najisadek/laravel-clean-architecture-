<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\Contracts\PasswordHasherInterface;
use App\Domain\User\Contracts\TokenGeneratorInterface;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Application\User\DTOs\LoginUserDTO;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\User;

/**
 * Login User Action
 *
 * Handles user authentication use case.
 * Returns the Domain Entity.
 */
final class LoginUser
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly PasswordHasherInterface $hasher,
        private readonly TokenGeneratorInterface $tokenGenerator
    ) {}

    /**
     * Execute login
     *
     * @throws InvalidCredentialsException
     */
    public function execute(LoginUserDTO $dto): User
    {
        $email = new Email($dto->email);
        $user = $this->repository->findByEmail($email);

        if ($user === null) {
            throw new InvalidCredentialsException;
        }

        if (! $user->verifyPassword($dto->password, [$this->hasher, 'verify'])) {
            throw new InvalidCredentialsException;
        }

        return $user;
    }

    /**
     * Generate token for authenticated user
     */
    public function generateToken(User $user): string
    {
        return $this->tokenGenerator->generate([
            'user_id' => $user->id()->value(),
            'email' => $user->email()->value(),
        ]);
    }
}
