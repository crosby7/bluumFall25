<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskSubscriptionResource;
use App\Models\TaskSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TaskSubscriptionController extends Controller
{
    /**
     * Display a listing of task subscriptions.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $this->authorize('viewAny', TaskSubscription::class);

        $subscriptions = TaskSubscription::with(['patient', 'task'])->get();
        return TaskSubscriptionResource::collection($subscriptions);
    }

    /**
     * Store a newly created task subscription.
     *
     * @param Request $request
     * @return TaskSubscriptionResource
     */
    public function store(Request $request): TaskSubscriptionResource
    {
        $this->authorize('create', TaskSubscription::class);

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'task_id' => ['required', 'exists:tasks,id'],
            'start_at' => ['required', 'date'],
            'scheduled_time' => ['required', 'date_format:H:i:s'],
            'interval_days' => ['required', 'integer', 'min:1'],
            'timezone' => ['nullable', 'string', 'timezone:all'],
            'is_active' => ['boolean'],
        ]);

        // Check if an active subscription already exists for this patient, task, and time
        $existingSubscription = TaskSubscription::where('patient_id', $validated['patient_id'])
            ->where('task_id', $validated['task_id'])
            ->where('scheduled_time', $validated['scheduled_time'])
            ->where('is_active', true)
            ->first();

        if ($existingSubscription) {
            throw ValidationException::withMessages([
                'scheduled_time' => ['This task is already assigned to this patient at this time.'],
            ]);
        }

        $subscription = TaskSubscription::create($validated);
        $subscription->load(['patient', 'task']);

        return new TaskSubscriptionResource($subscription);
    }

    /**
     * Display the specified task subscription.
     *
     * @param TaskSubscription $taskSubscription
     * @return TaskSubscriptionResource
     */
    public function show(TaskSubscription $taskSubscription): TaskSubscriptionResource
    {
        $this->authorize('view', $taskSubscription);

        $taskSubscription->load(['patient', 'task']);
        return new TaskSubscriptionResource($taskSubscription);
    }

    /**
     * Update the specified task subscription.
     *
     * @param Request $request
     * @param TaskSubscription $taskSubscription
     * @return TaskSubscriptionResource
     */
    public function update(Request $request, TaskSubscription $taskSubscription): TaskSubscriptionResource
    {
        $this->authorize('update', $taskSubscription);

        $validated = $request->validate([
            'patient_id' => ['sometimes', 'exists:patients,id'],
            'task_id' => ['sometimes', 'exists:tasks,id'],
            'start_at' => ['sometimes', 'date'],
            'scheduled_time' => ['sometimes', 'date_format:H:i:s'],
            'interval_days' => ['sometimes', 'integer', 'min:1'],
            'timezone' => ['sometimes', 'nullable', 'string', 'timezone:all'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $taskSubscription->update($validated);
        $taskSubscription->load(['patient', 'task']);

        return new TaskSubscriptionResource($taskSubscription);
    }

    /**
     * Remove the specified task subscription.
     *
     * @param TaskSubscription $taskSubscription
     * @return JsonResponse
     */
    public function destroy(TaskSubscription $taskSubscription): JsonResponse
    {
        $this->authorize('delete', $taskSubscription);

        $taskSubscription->delete();

        return response()->json([
            'message' => 'Task subscription deleted successfully',
        ]);
    }

    /**
     * Bulk create task subscriptions for a patient.
     *
     * @param Request $request
     * @param \App\Models\Patient $patient
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function bulkStore(Request $request, \App\Models\Patient $patient)
    {
        $this->authorize('bulkStore', [TaskSubscription::class, $patient]);

        $validated = $request->validate([
            'subscriptions' => ['required', 'array', 'min:1'],
            'subscriptions.*.task_id' => ['required', 'exists:tasks,id'],
            'subscriptions.*.start_at' => ['required', 'date'],
            'subscriptions.*.scheduled_time' => ['required', 'date_format:H:i:s'],
            'subscriptions.*.interval_days' => ['required', 'integer', 'min:1'],
            'subscriptions.*.timezone' => ['nullable', 'string', 'timezone:all'],
            'subscriptions.*.is_active' => ['boolean'],
        ]);

        $createdSubscriptions = [];

        foreach ($validated['subscriptions'] as $index => $subscriptionData) {
            $subscriptionData['patient_id'] = $patient->id;

            // Check if an active subscription already exists for this patient, task, and time
            $existingSubscription = TaskSubscription::where('patient_id', $subscriptionData['patient_id'])
                ->where('task_id', $subscriptionData['task_id'])
                ->where('scheduled_time', $subscriptionData['scheduled_time'])
                ->where('is_active', true)
                ->first();

            if ($existingSubscription) {
                throw ValidationException::withMessages([
                    "subscriptions.{$index}.scheduled_time" => ['This task is already assigned to this patient at this time.'],
                ]);
            }

            $subscription = TaskSubscription::create($subscriptionData);
            $subscription->load(['patient', 'task']);

            $createdSubscriptions[] = $subscription;
        }

        return TaskSubscriptionResource::collection($createdSubscriptions);
    }

    /**
     * Assign default schedule to a patient.
     * Creates a full daily schedule matching the PatientSeeder schedule.
     * All tasks are created with status pending (incomplete).
     *
     * @param \App\Models\Patient $patient
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function assignDefaultSchedule(\App\Models\Patient $patient)
    {
        $this->authorize('bulkStore', [TaskSubscription::class, $patient]);

        // Define task schedules matching PatientSeeder
        $taskSchedules = [
            'Morning Medication' => '08:00:00',
            'Grooming' => '09:00:00',
            'School: Reading' => '10:00:00',
            'PT Therapy' => '10:30:00',
            'School: Math' => '11:00:00',
            'Lunch' => '12:00:00',
            'Bandage Change' => '13:00:00',
            'OT Therapy' => '14:00:00',
            'Rec Activity' => '15:00:00',
            'Night Medications' => '20:00:00',
        ];

        // Get all tasks
        $tasks = \App\Models\Task::all();

        $createdSubscriptions = [];

        // Assign all tasks to the patient with their scheduled times
        foreach ($tasks as $task) {
            $scheduledTime = $taskSchedules[$task->name] ?? '12:00:00';

            $subscriptionData = [
                'patient_id' => $patient->id,
                'task_id' => $task->id,
                'start_at' => now()->format('Y-m-d H:i:s'),
                'scheduled_time' => $scheduledTime,
                'interval_days' => 1, // Daily tasks
                'timezone' => 'UTC',
                'is_active' => true,
            ];

            // Check if an active subscription already exists for this patient, task, and time
            $existingSubscription = TaskSubscription::where('patient_id', $subscriptionData['patient_id'])
                ->where('task_id', $subscriptionData['task_id'])
                ->where('scheduled_time', $subscriptionData['scheduled_time'])
                ->where('is_active', true)
                ->first();

            // Skip if already exists
            if ($existingSubscription) {
                continue;
            }

            $subscription = TaskSubscription::create($subscriptionData);
            $subscription->load(['patient', 'task']);

            $createdSubscriptions[] = $subscription;
        }

        return TaskSubscriptionResource::collection($createdSubscriptions);
    }
}
