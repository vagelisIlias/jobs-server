<?php

declare(strict_types=1);

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        Route::apiResource('jobs', JobPostController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('users', UserController::class);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});




// Add this temporary route to test basic transaction functionality
Route::get('/test-transaction', function () {
    try {
        $result = DB::transaction(function () {
            // Create a test user
            $user = User::create([
                'first_name' => 'Test',
                'last_name' => 'Transaction',
                'user_name' => 'test_transaction_' . time(),
                'email' => 'test_transaction_' . time() . '@test.com',
                'password' => Hash::make('password'),
            ]);

            if ($user) {
                throw new \Exception('Testing transaction rollback');
            }

        });

        return response()->json(['success' => false, 'message' => 'Should not reach here']);

    } catch (\Exception $e) {
        // Check if the test user was rolled back
        $testUserExists = User::where('email', 'like', 'test_transaction_%@test.com')->exists();

        return response()->json([
            'success' => !$testUserExists,
            'user_rolled_back' => !$testUserExists,
            'error' => $e->getMessage()
        ]);
    }
});
