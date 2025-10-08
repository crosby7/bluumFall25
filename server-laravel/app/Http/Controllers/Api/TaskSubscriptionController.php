<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskSubscriptionResource;
use App\Models\TaskSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskSubscriptionController extends Controller
{
    /**
     * Display a listing of task subscriptions.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
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
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'task_id' => ['required', 'exists:tasks,id'],
            'start_at' => ['required', 'date'],
            'interval_days' => ['required', 'integer', 'min:1'],
            'timezone' => ['nullable', 'string', 'timezone:all'],
            'is_active' => ['boolean'],
        ]);

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
        $validated = $request->validate([
            'patient_id' => ['sometimes', 'exists:patients,id'],
            'task_id' => ['sometimes', 'exists:tasks,id'],
            'start_at' => ['sometimes', 'date'],
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
        $validated = $request->validate([
            'subscriptions' => ['required', 'array', 'min:1'],
            'subscriptions.*.task_id' => ['required', 'exists:tasks,id'],
            'subscriptions.*.start_at' => ['required', 'date'],
            'subscriptions.*.interval_days' => ['required', 'integer', 'min:1'],
            'subscriptions.*.timezone' => ['nullable', 'string', 'timezone:all'],
            'subscriptions.*.is_active' => ['boolean'],
        ]);

        $createdSubscriptions = [];

        foreach ($validated['subscriptions'] as $subscriptionData) {
            $subscriptionData['patient_id'] = $patient->id;

            $subscription = TaskSubscription::create($subscriptionData);
            $subscription->load(['patient', 'task']);

            $createdSubscriptions[] = $subscription;
        }

        return TaskSubscriptionResource::collection($createdSubscriptions);
    }
}
