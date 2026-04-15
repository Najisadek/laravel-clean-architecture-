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

            return (new UserResource($user))
                ->response()
                ->setStatusCode(201);
        } catch (EmailAlreadyExistsException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exists',
            ], 409);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
            ], 500);
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
            $token = $this->loginUser->generateToken($user);

            return (new UserResource($user))->additional(['meta' => ['token' => $token]])->response();
        } catch (InvalidCredentialsException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Login failed',
            ], 500);
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
                return response()->json([
                    'success' => false,
                    'message' => 'No token provided',
                ], 401);
            }

            $this->logoutUser->execute($token);

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
