<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ClassifiedController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserRegisterController;

// Endpoints públicos
Route::apiResource('posts', PostController::class, [
    'only' => ['index', 'show']
]);

// Classifieds: endpoints públicos index/show
Route::apiResource('classifieds', ClassifiedController::class, [
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

    // Atualizar perfil do usuário autenticado
    Route::put('user', [\App\Http\Controllers\UserProfileController::class, 'update']);
    Route::patch('user', [\App\Http\Controllers\UserProfileController::class, 'update']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
    // reações (upvote/downvote)
    Route::post('posts/{post}/reactions', [\App\Http\Controllers\ReactionController::class, 'toggle']);
    // comentários
    Route::post('comments', [\App\Http\Controllers\Api\CommentController::class, 'store']);
    Route::put('comments/{comment}', [\App\Http\Controllers\Api\CommentController::class, 'update']);
    Route::delete('comments/{comment}', [\App\Http\Controllers\Api\CommentController::class, 'destroy']);
    // classificados (protegidos)
    Route::post('classifieds', [\App\Http\Controllers\Api\ClassifiedController::class, 'store']);
    Route::put('classifieds/{classified}', [\App\Http\Controllers\Api\ClassifiedController::class, 'update']);
    Route::patch('classifieds/{classified}', [\App\Http\Controllers\Api\ClassifiedController::class, 'update']);
    Route::delete('classifieds/{classified}', [\App\Http\Controllers\Api\ClassifiedController::class, 'destroy']);
});
