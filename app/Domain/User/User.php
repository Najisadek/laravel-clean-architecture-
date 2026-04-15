<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Models\User as UserModel;

final class User
{
    public function __construct(private UserModel $model) {}

    public static function create(string $name, string $email, string $password): self
    {
        $model = UserModel::create([
            'name' => $name,
            'email' => strtolower($email),
            'password' => $password,
        ]);

        return new self($model);
    }

    public static function fromModel(UserModel $model): self
    {
        return new self($model);
    }

    public function id(): string
    {
        return $this->model->id;
    }

    public function name(): string
    {
        return $this->model->name;
    }

    public function email(): string
    {
        return $this->model->email;
    }

    public function password(): string
    {
        return $this->model->password;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->model->created_at;
    }

    public function updatedAt(): ?\DateTimeInterface
    {
        return $this->model->updated_at;
    }

    public function updateName(string $name): void
    {
        $this->model->name = $name;
        $this->model->save();
    }

    public function updateEmail(string $email): void
    {
        $this->model->email = strtolower($email);
        $this->model->save();
    }

    public function getModel(): UserModel
    {
        return $this->model;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'email' => $this->email(),
            'created_at' => $this->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
