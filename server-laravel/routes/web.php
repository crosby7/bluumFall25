<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login.show');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/home', function () {
    return view('screens.home');
})->middleware('auth')->name('home');

Route::get('/patients', function () {
    return view('screens.patients');
})->middleware('auth')->name('patients');

require __DIR__.'/auth.php';
