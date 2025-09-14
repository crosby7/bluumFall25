<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login.show');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/home', function () {
    $patients = \App\Models\User::take(5)->get();
    return view('screens.home', compact('patients'));
})->middleware('auth')->name('home');

Route::get('/patients', function () {
    $patients = \App\Models\User::all();
    return view('screens.patients', compact('patients'));
})->middleware('auth')->name('patients');

require __DIR__.'/auth.php';
