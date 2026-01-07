<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/settings.php';
Route::get('/', fn () => 'home')->name('home');

Route::get('/dashboard', fn () => 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');
