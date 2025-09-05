<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/nurses', function () {
    return view('nurses');
});

//require __DIR__.'/auth.php';
