<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Tokens;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Exceptions\Api\Tokens\UserNotFoundException;
use App\Http\Requests\Api\TokenRequest\TokenRequest;

class TokenController extends Controller
{
    public function store(Request $request, TokenRequest $tokenRequest): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            throw new UserNotFoundException();
        }

        $token = $user->createAuthToken($request->token_name);

        $token->accessToken->last_used_at = now();
        $token->accessToken->save();

        return response()->json(['API Token' => $token->plainTextToken]);
    }
}
