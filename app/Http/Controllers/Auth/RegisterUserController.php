<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Mail\UserRegisteredEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\RegisterUserRequest\StoreUserRequest;
use App\ApiResponseTrait\ApiResponseTrait;


class RegisterUserController extends Controller
{
    use ApiResponseTrait;

    private const SUCCESS = 'Registration was successful';
    private const TOKEN = 'registration_token';
    private const FAILED = 'Failed to create user and token';

    /**
     * Store a newly created resource in storage.
     */
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
}
