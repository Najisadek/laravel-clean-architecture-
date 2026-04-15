<?php

namespace App\Http\Controllers\Api;

use App\Application\User\Actions\LoginUser;
use App\Application\User\Actions\LogoutUser;
use App\Application\User\Actions\RegisterUser;
use App\Application\User\DTOs\LoginUserDTO;
use App\Application\User\DTOs\RegisterUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUser $registerUser,
        private readonly LoginUser $loginUser,
        private readonly LogoutUser $logoutUser
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $dto = RegisterUserDTO::fromArray($request->validated());
            $user = $this->registerUser->execute($dto);

            return (new UserResource($user))
                ->response()
                ->setStatusCode(201);

        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginUserDTO::fromArray($request->validated());
            $user = $this->loginUser->execute($dto);
            $token = $this->loginUser->generateToken($user);

            return (new UserResource($user))->additional(['meta' => ['token' => $token]])->response();
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Login failed',
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $this->logoutUser->execute($user);

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out',
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
            ], 500);
        }
    }
}
