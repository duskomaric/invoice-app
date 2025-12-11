<?php

use App\Filament\Pages\Register;
use Illuminate\Support\Facades\Route;

Route::get('/register/{user:invitation_code}', Register::class)->name('filament.pages.register');

Route::get('/email/pixel/{id}', [\App\Http\Controllers\TrackingController::class, 'pixel'])->name('email.pixel');
Route::get('/email/click/{id}', [\App\Http\Controllers\TrackingController::class, 'click'])->name('email.click');
