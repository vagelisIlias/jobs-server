<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\Tokens\TokenController;

// Check all the users as auth user
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resources(['/users' => UserController::class]);
});

// Generate new token
Route::resources(['/token/generate' => TokenController::class]);

// Store new users
Route::resources(['/register' => RegisterUserController::class]);
