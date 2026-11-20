<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route(auth()->check() ? 'dashboard' : 'login'))->name('home');

Route::middleware(['private.response', 'guest'])->group(function () {
  Route::get('/login', [SessionController::class, 'create'])->name('login');
  Route::post('/login', [SessionController::class, 'store'])->name('login.store');
});

Route::middleware(['private.response', 'auth', 'account.active', 'auth.session'])->group(function () {
  Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
  Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
  Route::put('/password', [PasswordController::class, 'update'])->middleware('throttle:6,1')->name('password.update');

  Route::middleware('password.changed')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('/users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->middleware('throttle:10,1')->name('users.reset-password');
    Route::patch('/users/{user}/active', [UserController::class, 'setActive'])->name('users.active');
  });
});
