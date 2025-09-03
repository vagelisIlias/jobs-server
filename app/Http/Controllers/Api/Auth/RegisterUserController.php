<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use Throwable;
use App\Mail\UserRegisteredEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\UserRequest\StoreUserRequest;

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
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Mail::to($user)->send(new UserRegisteredEmail($user->user_name));
        
        return response()->json([
            ...new UserResource($user)->toArray($request),
            'registration_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->accessToken->expires_at->toISOString(),
        ], Response::HTTP_CREATED);
    }
}
