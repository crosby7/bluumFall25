<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NurseDashboardController;
use App\Models\Nurse;

Route::get('/', function () {
    return view('auth.login');
})->name('login.show');

Route::get('/search', [NurseDashboardController::class, 'search'])->middleware('auth')->name('search');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/home', [NurseDashboardController::class, 'home'])->middleware('auth')->name('home');

Route::get('/patients', [NurseDashboardController::class, 'patients'])->middleware('auth')->name('patients');

Route::get('/inbox', [NurseDashboardController::class, 'inbox'])->middleware('auth')->name('inbox');

require __DIR__.'/auth.php';
