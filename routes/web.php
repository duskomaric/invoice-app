<?php

use App\Filament\Pages\Register;
use Illuminate\Support\Facades\Route;

Route::get('/register/{user:invitation_code}', Register::class)->name('filament.pages.register');
