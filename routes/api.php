<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserRegisterController;

// Public endpoints
Route::apiResource('posts', PostController::class, [
    'only' => ['index', 'show']
]);

Route::post('login', [AuthController::class, 'login']);
Route::post('/register', [UserRegisterController::class, 'store']);

// Protected endpoints (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{post}', [PostController::class, 'update']);
    Route::patch('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
});
