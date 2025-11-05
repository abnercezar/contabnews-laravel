<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserRegisterController;

// Endpoints públicos
Route::apiResource('posts', PostController::class, [
    'only' => ['index', 'show']
]);

Route::post('login', [AuthController::class, 'login']);
Route::post('/register', [UserRegisterController::class, 'store']);

// Endpoints protegidos (requerem autenticação)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{post}', [PostController::class, 'update']);
    Route::patch('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);

    // Retorna o usuário autenticado para o front-end verificar sessão/token
    Route::get('user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
    // reações (upvote/downvote)
    Route::post('posts/{post}/reactions', [\App\Http\Controllers\ReactionController::class, 'toggle']);
});
