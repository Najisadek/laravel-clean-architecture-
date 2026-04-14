<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Attributes\Fillable, Attributes\Hidden, Factories\HasFactory};
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
final class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasUuids;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
