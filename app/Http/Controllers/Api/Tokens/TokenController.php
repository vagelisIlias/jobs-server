<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Tokens;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Exceptions\Api\Tokens\UserNotFoundException;
use App\Http\Requests\Api\TokenRequest\TokenRequest;

class TokenController extends Controller
{
    public function store(Request $request, TokenRequest $tokenRequest): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (! $user) {
                throw new UserNotFoundException();
            }

            $token = $request->user()->createToken($request->token_name);
            $token->accessToken->last_used_at = now();
            $token->accessToken->save();

            return response()->json(['API Token' => $token->plainTextToken]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
