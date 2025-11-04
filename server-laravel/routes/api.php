<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\NurseController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskSubscriptionController;
use App\Http\Controllers\Api\TaskCompletionController;
use App\Http\Controllers\Api\Auth\PatientAuthController;

// Patient Authentication Routes (public)
Route::post('/login', [PatientAuthController::class, 'login'])
    ->middleware('throttle:auth');

// Patient App API Routes (require Sanctum token authentication)
Route::middleware(['auth:sanctum', 'throttle:patient-api'])->group(function () {
    // Auth Routes
    Route::post('/logout', [PatientAuthController::class, 'logout']);
    Route::get('/me', [PatientAuthController::class, 'me']);

    // Patient Profile Routes
    Route::get('/patient', [PatientController::class, 'show']);
    Route::get('/patient/profile', [PatientController::class, 'profile']);
    Route::patch('/patient/profile', [PatientController::class, 'updateProfile']);

    // Patient Tasks Routes
    Route::get('/patient/tasks/current', [PatientController::class, 'currentTasks']);
    Route::patch('/patient/tasks/completions/{taskCompletion}', [TaskCompletionController::class, 'update']);

    // Patient Inventory Routes
    Route::get('/patient/inventory', [PatientController::class, 'inventory']);

    // Shop Items Routes
    Route::get('/items', [ItemController::class, 'index']);
});