<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Application\User\DTOs\RegisterUserDTO;
use App\Application\User\DTOs\UserResponseDTO;
use App\Domain\User\Contracts\PasswordHasherInterface;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\Exceptions\EmailAlreadyExistsException;
use App\Domain\User\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Password;

/**
 * Register User Action
 *
 * Handles user registration use case.
 */
final class RegisterUser
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly PasswordHasherInterface $hasher
    ) {}

    /**
     * Execute registration
     *
     * @throws EmailAlreadyExistsException
     */
    public function execute(RegisterUserDTO $dto): UserResponseDTO
    {
        $email = new Email($dto->email);

        // Check if email already exists
        if ($this->repository->existsByEmail($email)) {
            throw new EmailAlreadyExistsException;
        }

        // Create domain user entity
        $user = User::create(
            name: $dto->name,
            email: $email,
            password: new Password($dto->password)
        );

        // Hash password and set it on entity
        $hashedPassword = $this->hasher->hash($dto->password);
        $user->password()->setHashedValue($hashedPassword);

        // Persist user
        $this->repository->save($user);

        return new UserResponseDTO(
            id: $user->id()->value(),
            name: $user->name(),
            email: $user->email()->value(),
            createdAt: $user->createdAt()->format('Y-m-d H:i:s')
        );
    }
}
