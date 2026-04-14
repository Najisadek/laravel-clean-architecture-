<?php

namespace App\Providers;

use App\Application\User\Actions\GetUserById;
use App\Application\User\Actions\LoginUser;
use App\Application\User\Actions\LogoutUser;
use App\Application\User\Actions\RegisterUser;
use App\Domain\User\Contracts\PasswordHasherInterface;
use App\Domain\User\Contracts\TokenGeneratorInterface;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Infrastructure\Services\LaravelPasswordHasher;
use App\Infrastructure\Services\SanctumTokenGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Domain Contracts -> Infrastructure Implementations
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(PasswordHasherInterface::class, LaravelPasswordHasher::class);
        $this->app->bind(TokenGeneratorInterface::class, SanctumTokenGenerator::class);

        // Application Actions - Laravel auto-injects dependencies
        // No need to bind explicitly, but we can for clarity
        $this->app->bind(RegisterUser::class, RegisterUser::class);
        $this->app->bind(LoginUser::class, LoginUser::class);
        $this->app->bind(LogoutUser::class, LogoutUser::class);
        $this->app->bind(GetUserById::class, GetUserById::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
