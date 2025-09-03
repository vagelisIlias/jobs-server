<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Tokens;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\Tokens\UserNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Api\TokenRequest\TokenRequest;

class TokenController extends Controller
{
    public function store(Request $request, TokenRequest $tokenRequest): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
            if (! $user) {
                throw new UserNotFoundException();
            }

            $token = $user->createToken($request->token_name, [], now()->addDays(7));
            $token->accessToken->last_used_at = now();
            $token->accessToken->save();

            return response()->json(['generate_token' => $token->plainTextToken]);
    }
}
