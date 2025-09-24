<?php

declare(strict_types=1);

use App\Models\User;

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

    Route::get('/jobs', [JobPostController::class, 'index']);
    Route::get('/job/{id}', [JobPostController::class, 'jobDescription'])
        ->name('job.description');

    // Token endpoints (for testing or initial authentication)
    Route::post('/token/generate', [TokenController::class, 'generate']);
    Route::post('/token/create', [TokenController::class, 'create']);

    // Auth-protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/job/{job}', [JobPostController::class, 'show']);
        Route::post('/job/store', [JobPostController::class, 'store']);
        Route::put('/job/update/{job}', [JobPostController::class, 'update']);
        Route::delete('/job/delete/{job}', [JobPostController::class, 'destroy']);
        Route::apiResource('/users', UserController::class);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
