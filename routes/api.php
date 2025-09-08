<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tokens\TokenController;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\JobPosts\JobPostController;

// Generate Token
Route::prefix('v1')->group(function () {
    Route::resources(['/token/generate' => TokenController::class]);
});

// Register Login users
Route::prefix('v1')->group(function () {
    Route::post('/register', [RegisterUserController::class, 'register']);
    Route::post('/login', [LoginUserController::class, 'login']);
    Route::get('/jobs', [JobPostController::class, 'index']);
});

// All Auth routes
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
            Route::post('/job/create', [JobPostController::class, 'store']);
            Route::post('/logout', [LoginUserController::class, 'logout']);
        });
});
