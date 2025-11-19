<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ClassifiedController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
});

Route::get('/register', function () {
    return Inertia::render('Register');
});

Route::get('/login', function () {
    return Inertia::render('Login');
})->name('login');

Route::get('/profile', function () {
    return Inertia::render('Profile');
});

Route::get('/publications', [PostController::class, 'publicationsPage']);

Route::get('/comments', function () {
    return Inertia::render('Comments');
});

Route::get('/classifieds', function () {
    return Inertia::render('Classifieds');
});

// Rota para página de um classificado individual
Route::get('/classifieds/{id}', [ClassifiedController::class, 'showPage'])->name('classifieds.show');

Route::get('/content/create', [PostController::class, 'create'])->name('content.create');
// Route accepts an id; controller will attempt to load post or render a fallback placeholder
Route::get('/content/{id}', [PostController::class, 'showPage'])->name('content.show');
Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{post}', [PostController::class, 'update']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);
