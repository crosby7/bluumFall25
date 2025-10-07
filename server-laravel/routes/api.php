<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\Auth\PatientAuthController;

// Patient Authentication Routes (public)
Route::post('/login', [PatientAuthController::class, 'login']);

// Protected Patient Routes (require Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::post('/logout', [PatientAuthController::class, 'logout']);
    Route::get('/me', [PatientAuthController::class, 'me']);

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