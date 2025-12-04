<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NurseDashboardController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskSubscriptionController;
use App\Http\Controllers\Api\TaskCompletionController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\NurseController;
use App\Models\Nurse;

Route::get('/', function () {
    return view('auth.login');
})->name('login.show');

Route::get('/search', [NurseDashboardController::class, 'search'])->middleware(['auth', 'verified'])->name('search');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/home', [NurseDashboardController::class, 'home'])->middleware(['auth', 'verified'])->name('home');

Route::get('/patients', [NurseDashboardController::class, 'patients'])->middleware(['auth', 'verified'])->name('patients');

Route::get('/inbox', [NurseDashboardController::class, 'inbox'])->middleware(['auth', 'verified'])->name('inbox');

// Nurse Portal API Routes (using web session authentication)
Route::prefix('api/nurse')->middleware(['auth', 'verified', 'throttle:nurse-api'])->group(function () {
    // Patient Management
    Route::apiResource('patients', PatientController::class)->except(['show', 'update']);

    // Task Management
    Route::apiResource('tasks', TaskController::class);

    // Task Subscription Management
    Route::apiResource('task-subscriptions', TaskSubscriptionController::class);
    Route::post('patients/{patient}/task-subscriptions/bulk', [TaskSubscriptionController::class, 'bulkStore']);
    Route::post('patients/{patient}/task-subscriptions/default-schedule', [TaskSubscriptionController::class, 'assignDefaultSchedule']);

    // Task Completion Management
    Route::apiResource('task-completions', TaskCompletionController::class);

    // Nurse Management
    Route::apiResource('nurses', NurseController::class);

    // Base Context (for AJAX)
    Route::get('/context/base', [NurseDashboardController::class, 'baseContextJson'])->name('context.base');
});

require __DIR__.'/auth.php';
