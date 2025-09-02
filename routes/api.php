<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\Tokens\TokenController;

// Check all the users as auth user
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        });
});

Route::prefix('v1')->group(function () {
    Route::resources(['/token/generate' => TokenController::class]);
    Route::resources(['/register' => RegisterUserController::class]);
});
