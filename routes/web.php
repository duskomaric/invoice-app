<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register/{user:invitation_code}', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register/{user:invitation_code}', [AuthController::class, 'register'])->name('register.store');

Route::get('/sign-up', [AuthController::class, 'showPublicRegister'])->name('public.register');
Route::post('/sign-up', [AuthController::class, 'publicRegister']);

Route::get('/email/pixel/{id}', [\App\Http\Controllers\TrackingController::class, 'pixel'])->name('email.pixel');
Route::get('/email/click/{id}', [\App\Http\Controllers\TrackingController::class, 'click'])->name('email.click');
