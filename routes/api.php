<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Tokens\TokenController;

// Check all the users as auth user
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        });
});

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::prefix('v1')->group(function () {
//         Route::resources(['/login' => LoginUserController::class]);
//         });
// });

Route::prefix('v1')->group(function () {
    Route::resources(['/token/generate' => TokenController::class]);
    Route::resources(['/register' => RegisterUserController::class]);
});
