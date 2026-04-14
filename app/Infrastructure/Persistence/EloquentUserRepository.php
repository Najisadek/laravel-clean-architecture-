<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\UserId;
use App\Models\User as UserModel;
use DateTimeImmutable;

/**
 * Eloquent User Repository
 *
 * Implements the repository interface using Laravel Eloquent.
 */
final class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function existsByEmail(Email $email): bool
    {
        return UserModel::where('email', $email->value())->exists();
    }

    public function save(User $user): void
    {
        UserModel::updateOrCreate(
            ['id' => $user->id()->value()],
            [
                'name' => $user->name(),
                'email' => $user->email()->value(),
                'password' => $user->password()->hashedValue(),
                'created_at' => $user->createdAt(),
                'updated_at' => $user->updatedAt(),
            ]
        );
    }

    public function delete(UserId $id): void
    {
        UserModel::where('id', $id->value())->delete();
    }

    /**
     * Convert Eloquent model to Domain entity
     */
    private function toEntity(UserModel $model): User
    {
        $password = new Password($model->password, true);

        return new User(
            new UserId($model->id),
            $model->name,
            new Email($model->email),
            $password,
            new DateTimeImmutable($model->created_at->toDateTimeString()),
            $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null
        );
    }
}
