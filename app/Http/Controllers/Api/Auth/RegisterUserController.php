<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Resources\Api\UserResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\UserRequest\StoreUserRequest;
use App\Events\Api\UserRegistered\UserRegisteredEventMessage;
use Throwable;

class RegisterUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $request->storeUser();
            $token = $user->createAuthToken('registration_token');
            UserRegisteredEventMessage::dispatch($user);

            $userData = new UserResource($user)->toArray($request);

            $response = [
                ...$userData,
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->accessToken->expires_at->toISOString()
            ];

            return response()->json($response, Response::HTTP_CREATED);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to create user',
                'error'   => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
