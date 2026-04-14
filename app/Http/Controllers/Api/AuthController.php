<?php

namespace App\Http\Controllers\Api;

use App\Application\User\Actions\LoginUser;
use App\Application\User\Actions\LogoutUser;
use App\Application\User\Actions\RegisterUser;
use App\Application\User\DTOs\LoginUserDTO;
use App\Application\User\DTOs\RegisterUserDTO;
use App\Domain\User\Exceptions\EmailAlreadyExistsException;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUser $registerUser,
        private readonly LoginUser $loginUser,
        private readonly LogoutUser $logoutUser
    ) {}

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $dto = RegisterUserDTO::fromArray($request->validated());
            $user = $this->registerUser->execute($dto);

            return $this->success(
                data: (new UserResource($user->toArray()))->response()->getData(true)['data'],
                message: 'User registered successfully',
                code: 201
            );
        } catch (EmailAlreadyExistsException $e) {
            return $this->error(
                message: 'Email already exists',
                code: 409
            );
        } catch (\Exception $e) {
            report($e);

            return $this->error(
                message: 'Registration failed',
                code: 500
            );
        }
    }

    /**
     * Login user and create token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginUserDTO::fromArray($request->validated());
            $user = $this->loginUser->execute($dto);

            return $this->success(
                data: (new UserResource($user->toArray()))->response()->getData(true)['data'],
                message: 'Login successful',
                code: 200
            );
        } catch (InvalidCredentialsException $e) {
            return $this->error(
                message: 'Invalid credentials',
                code: 401
            );
        } catch (\Exception $e) {
            report($e);

            return $this->error(
                message: 'Login failed',
                code: 500
            );
        }
    }

    /**
     * Logout user and revoke token
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $token = $request->bearerToken();

            if (! $token) {
                return $this->error(
                    message: 'No token provided',
                    code: 401
                );
            }

            $this->logoutUser->execute($token);

            return $this->success(
                data: [],
                message: 'Successfully logged out'
            );
        } catch (\Exception $e) {
            report($e);

            return $this->error(
                message: 'Logout failed',
                code: 500
            );
        }
    }
}
