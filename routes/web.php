<?php

use App\Http\Controllers\PostController;
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
});


Route::get('/profile', function () {
    return Inertia::render('Profile');
});
Route::get('/publications', function () {
    return Inertia::render('Publications');
});

Route::get('/comments', function () {
    return Inertia::render('Comments');
});

Route::get('/classifieds', function () {
    return Inertia::render('Classifieds');
});
Route::get('/content/create', [PostController::class, 'create'])->name('content.create');
