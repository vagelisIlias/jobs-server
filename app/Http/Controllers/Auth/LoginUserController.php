<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\ApiResponseTrait\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\LoginUserRequest\LoginUserRequest;

class LoginUserController extends Controller
{
    use ApiResponseTrait;

    private const EMAIL = 'email';
    private const PASSWORD = 'password';
    private const ERROR = 'The provided credentials are incorrect';
    private const SUCCESS = 'Login was successful';
    private const TOKEN = 'login_token';
    private const FAILED_TOKEN = 'Token creation failed';

    /**
     * Login a user
     */
    public function login(LoginUserRequest $loginUserRequest): JsonResponse
    {
        $user = User::where(self::EMAIL, $loginUserRequest->input(self::EMAIL))->first();

        if (!$user || !Hash::check($loginUserRequest->input(self::PASSWORD), $user->password)) {
            return $this->sendError(self::ERROR, [],Response::HTTP_UNAUTHORIZED);
        }

        try {
            $token = $user->createToken($loginUserRequest->input('token_name',self::TOKEN) ?? $loginUserRequest->input(self::TOKEN), [], now()->addDays(7));
            $token->accessToken->last_used_at = now();
            $token->accessToken->save();
        } catch (Throwable $e) {
             return $this->sendError(
                self::FAILED_TOKEN . ': ' .$e->getMessage(),
                [],
                Response::HTTP_CONFLICT
            );
        }

        return $this->sendResponse([
            ...new UserResource($user)->toArray($loginUserRequest),
            'login_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->accessToken->expires_at->toISOString(),
        ], self::SUCCESS, Response::HTTP_OK);
    }
}

