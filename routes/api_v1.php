<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Users\UserController;
use App\Http\Controllers\Api\V1\Tokens\TokenController;
use App\Http\Controllers\Api\V1\JobPosts\JobPostController;


Route::prefix('v1')->group(function () {

    // Public routes (no auth)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // They can see the jobs and not all the details of the users if no Auth user
    Route::middleware('auth:sanctum')->group(function () {
         Route::apiResource('jobs', JobPostController::class)->only(['index', 'show']);
    });

    // Token endpoints (for testing or initial authentication)
    Route::post('/token/generate', [TokenController::class, 'generate']);
    Route::post('/token/create', [TokenController::class, 'create']);

    // Auth-protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('jobs', JobPostController::class)->only(['store', 'destroy', 'update']);
        Route::apiResource('users', UserController::class);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
