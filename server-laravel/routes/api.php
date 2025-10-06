<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;

/*
 * Middleware to impersonate dev patient for testing (stateless)
 * TODO: Replace with proper authentication (auth:sanctum) in production
 */
Route::middleware('dev.impersonate')->group(function () {
    // Patient Profile Routes
    Route::get('/patient', [PatientController::class, 'show']);
    Route::get('/patient/profile', [PatientController::class, 'profile']);
    Route::put('/patient/profile', [PatientController::class, 'updateProfile']);

    // Patient Tasks Routes
    Route::get('/patient/tasks/current', [PatientController::class, 'currentTasks']);

    // Patient Inventory Routes
    Route::get('/patient/inventory', [PatientController::class, 'inventory']);

    // Legacy inventory route (backwards compatibility) REMOVE LATER?
    Route::get('/inventory', [PatientController::class, 'inventory']);
});

// Temporary dev impersonation middleware
// TODO: Remove this and use proper auth middleware in production
Route::middleware('api')->group(function () {
    Route::any('{any}', function (Request $request) {
        // This won't be reached for routes defined above
    })->where('any', '.*');
});

require __DIR__.'/auth.php';