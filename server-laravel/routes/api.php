<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/user', function (Request $request) {
    $user = \App\Models\User::where('email', 'dev@example.com')->first();
    Auth::setUser($user);
    $request->setUserResolver(fn () => $user);
    return $request->user();
});

Route::get('/inventory', function (Request $request) {
    $user = \App\Models\User::findOrFail(1);

    // dev impersonation (stateless)
    Auth::setUser($user);
    $request->setUserResolver(fn () => $user);

    // Load this user's inventory
    $inventory = $user->inventory()->with('item')->get();

    return response()->json($inventory);
});

/*
Route::get('/tasks', function (Request $request) {
    // dev user id = 1
    $user = \App\Models\User::findOrFail(1);

    // dev impersonation (stateless)
    Auth::setUser($user);
    $request->setUserResolver(fn () => $user);

    // Load this user's inventory
    $inventory = $user->inventory()->with('item')->get();

    return response()->json($inventory);
});
*/

//require __DIR__.'/auth.php';