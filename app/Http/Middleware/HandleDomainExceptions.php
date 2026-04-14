<?php

namespace App\Http\Middleware;

use App\Domain\User\Exceptions\EmailAlreadyExistsException;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use Closure;
use Illuminate\Http\Request;

class HandleDomainExceptions
{
    /**
     * Handle domain exceptions and convert them to HTTP responses
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (EmailAlreadyExistsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        } catch (InvalidCredentialsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
