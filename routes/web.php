<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', \App\Livewire\Home::class)->name('home');
Route::get('/login', \App\Livewire\Login::class)->name('login');
Route::get('/register', \App\Livewire\Register::class)->name('register');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Protected routes (only accessible if logged in)
Route::middleware('auth')->group(function () {
    Route::get('/task-board', \App\Livewire\TaskBoard::class)->name('task_board');
});
