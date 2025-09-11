<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
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
            new UserResource($user)->toArray($loginUserRequest),
            'login_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->accessToken->expires_at->toISOString(),
        ], self::SUCCESS, Response::HTTP_OK);
    }

    public function register(StoreUserRequest $storeUserRequest): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $storeUserRequest->storeUser();
            $token = $user->createToken($storeUserRequest->token_name ?? self::TOKEN, [], now()->addDays(7));
            $token->accessToken->last_used_at = now();
            $token->accessToken->save();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->sendError(
                self::FAILED . ': ' . $e->getMessage(),
                [],
                Response::HTTP_CONFLICT
            );
        }

        Mail::to($user)->send(new UserRegisteredEmail($user->user_name));

        return $this->sendResponse(
            new UserResource($user),
            self::SUCCESS,
            Response::HTTP_CREATED
        );
    }

    public function logout()
    {

    }

    public function resetPassword()
    {

    }
}
