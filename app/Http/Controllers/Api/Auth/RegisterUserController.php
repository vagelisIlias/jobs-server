<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Resources\Api\UserResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\UserRequest\StoreUserRequest;
use App\Events\Api\UserRegistered\UserRegisteredEventMessage;
use App\Exceptions\Api\Tokens\GenerateTokenNotFoundException;

class RegisterUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $request->storeUser();
            $token = $user->createToken($request->token_name ?? 'registration_token', [], now()->addDays(7));
            $token->accessToken->last_used_at = now();
            $token->accessToken->save();
            DB::commit();
            UserRegisteredEventMessage::dispatch($user);

            return response()->json([
                ...new UserResource($user)->toArray($request),
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->accessToken->expires_at->toISOString(),
            ], Response::HTTP_CREATED);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to create user',
                'error'   => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            return response()->json([
                'error'   => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
