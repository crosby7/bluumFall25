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

// Nurse Portal API Routes (require web session authentication)
Route::prefix('nurse')->middleware(['auth:web', 'throttle:nurse-api'])->group(function () {
    // Patient Management
    Route::apiResource('patients', PatientController::class)->except(['show', 'update']);

    // Task Management
    Route::apiResource('tasks', TaskController::class);

    // Task Subscription Management
    Route::apiResource('task-subscriptions', TaskSubscriptionController::class);
    Route::post('patients/{patient}/task-subscriptions/bulk', [TaskSubscriptionController::class, 'bulkStore']);

    // Task Completion Management
    Route::apiResource('task-completions', TaskCompletionController::class);

    // Item Management
    Route::apiResource('items', ItemController::class);

    // Nurse Management
    Route::apiResource('nurses', NurseController::class);
});

// Patient App API Routes (require Sanctum token authentication)
Route::middleware(['auth:sanctum', 'throttle:patient-api'])->group(function () {
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
});