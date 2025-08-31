<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::apiResource('posts', PostController::class, [
    'only' => ['index', 'show']
]);

Route::middleware('auth.token')->group(function () {
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{post}', [PostController::class, 'update']);
    Route::patch('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);
});

Route::post('login', [AuthController::class, 'login']);
