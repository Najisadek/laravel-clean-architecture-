<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Application\User\DTOs\LoginUserDTO;
use App\Application\User\DTOs\UserResponseDTO;
use App\Domain\User\Contracts\PasswordHasherInterface;
use App\Domain\User\Contracts\TokenGeneratorInterface;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\ValueObjects\Email;

/**
 * Login User Action
 *
 * Handles user authentication use case.
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
    public function execute(LoginUserDTO $dto): UserResponseDTO
    {
        $email = new Email($dto->email);
        $user = $this->repository->findByEmail($email);

        if ($user === null) {
            throw new InvalidCredentialsException;
        }

        // Verify password
        if (! $user->verifyPassword($dto->password, [$this->hasher, 'verify'])) {
            throw new InvalidCredentialsException;
        }

        // Generate authentication token
        $token = $this->tokenGenerator->generate([
            'user_id' => $user->id()->value(),
            'email' => $user->email()->value(),
        ]);

        return new UserResponseDTO(
            id: $user->id()->value(),
            name: $user->name(),
            email: $user->email()->value(),
            createdAt: $user->createdAt()->format('Y-m-d H:i:s'),
            token: $token
        );
    }
}
