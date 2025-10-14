<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NurseDashboardController;

Route::get('/', function () {
    return view('auth.login');
})->name('login.show');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/home', [NurseDashboardController::class, 'home'])->middleware('auth')->name('home');

Route::get('/patients', [NurseDashboardController::class, 'patients'])->middleware('auth')->name('patients');

Route::get('/inbox', [NurseDashboardController::class, 'inbox'])->middleware('auth')->name('inbox');

require __DIR__.'/auth.php';
