<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\User as DomainUser;
use App\Models\User as UserModel;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(string $id): ?DomainUser
    {
        $model = UserModel::find($id);

        return $model ? DomainUser::fromModel($model) : null;
    }

    public function findByEmail(string $email): ?DomainUser
    {
        $model = UserModel::where('email', strtolower($email))->first();

        return $model ? DomainUser::fromModel($model) : null;
    }

    public function existsByEmail(string $email): bool
    {
        return UserModel::where('email', strtolower($email))->exists();
    }

    public function save(DomainUser $user): void
    {
        $user->getModel()->save();
    }

    public function delete(string $id): void
    {
        UserModel::where('id', $id)->delete();
    }
}
