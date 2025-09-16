<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Controllers\Api\V1\Tokens\TokenController;
use App\Trait\Api\V1\ApiResponseTrait\ApiResponseTrait;
use App\Http\Resources\Api\V1\UserResource\UserResource;
use App\Mail\Api\V1\UserRegisteredEmail\UserRegisteredEmail;
use App\Http\Requests\Api\V1\Auth\LoginUserRequest\LoginUserRequest;
use App\Http\Requests\Api\V1\Auth\RegisterUserRequest\StoreUserRequest;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(LoginUserRequest $request, TokenController $token): JsonResponse
    {
        try {
             if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->error('Invalid credentials', Response::HTTP_UNAUTHORIZED);
            }

            $user = User::firstWhere('email', $request->email);
            $tokenData = $token->create($user, 'login_token');
        } catch (Throwable $e) {
             return $this->error(
                $e->getMessage(),
                Response::HTTP_CONFLICT
            );
        }

        return $this->success([
            'user' => new UserResource($user),
            'login_token' => $tokenData,
        ], 'Successfully logged in', Response::HTTP_OK);
    }

    public function register(StoreUserRequest $request, TokenController $token): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $request->storeUser();
            $tokenData = $token->create($user, 'register_token');
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->error(
                $e->getMessage(),
             Response::HTTP_CONFLICT
            );
        }

        Mail::to($user)->send(new UserRegisteredEmail($user->user_name));

        return $this->success([
            'user' => new UserResource($user),
            'register_token' => $tokenData,
        ],   'Registration was successful', Response::HTTP_CREATED);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success([], 'User logout successfully', Response::HTTP_OK);
    }

    // public function resetPassword()
    // {

    // }
}
