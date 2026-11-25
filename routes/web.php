<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', fn() => view('menu'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])
        ->name('menu');

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    Route::get('/new-game', [GameController::class, 'create'])
        ->name('game.create');

    Route::post('/game', [GameController::class, 'store'])
        ->name('game.store');

    Route::get('/game/{game}', [GameController::class, 'play'])
        ->name('game.play');

    Route::post('/game/{game}/move', [GameController::class, 'submitMove'])
        ->name('game.move');

    Route::post('/game/{game}/pause', [GameController::class, 'pause'])
        ->name('game.pause');

    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history.index');
});
