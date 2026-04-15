<?php

declare(strict_types=1);

namespace App\Domain\User\Contracts;

use App\Domain\User\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;

    public function findByEmail(string $email): ?User;

    public function existsByEmail(string $email): bool;

    public function save(User $user): void;

    public function delete(string $id): void;
}
