<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Tokens;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Api\V1\TokenRequest\TokenRequest;
use App\Trait\Api\V1\ApiResponseTrait\ApiResponseTrait;

class TokenController extends Controller
{
    use ApiResponseTrait;

    public function generate(TokenRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Unauthenticated users not allowed', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::firstWhere('email', $request->email);

        if (!$user) {
            return $this->error('User not found', Response::HTTP_NOT_FOUND);
        }

        $tokenData = $this->create($user, 'generate_token');
        return $this->success(
            data: $tokenData,
            message: 'Authenticated',
            statusCode: Response::HTTP_OK);
    }

    public function create(User $user, string $tokenName = 'default_token', int $days = 7): array
    {
        $token = $user->createToken($tokenName, ['*'], now()->addDays($days));
        $token->accessToken->last_used_at = Date::now();
        $token->accessToken->save();

        return [
            'token'      => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->accessToken->expires_at?->toISOString(),
        ];
    }
}
