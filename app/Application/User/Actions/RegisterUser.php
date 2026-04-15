<?php

declare(strict_types=1);

namespace App\Application\User\Actions;

use App\Application\User\DTOs\RegisterUserDTO;
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
 * Returns the Domain Entity.
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
    public function execute(RegisterUserDTO $dto): User
    {
        $email = new Email($dto->email);

        if ($this->repository->existsByEmail($email)) {
            throw new EmailAlreadyExistsException;
        }

        $user = User::create(
            name: $dto->name,
            email: $email,
            password: new Password($dto->password)
        );

        $hashedPassword = $this->hasher->hash($dto->password);
        $user->password()->setHashedValue($hashedPassword);

        $this->repository->save($user);

        return $user;
    }
}
