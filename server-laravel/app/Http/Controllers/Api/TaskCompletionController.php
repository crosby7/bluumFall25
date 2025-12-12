<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCompletionResource;
use App\Models\TaskCompletion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskCompletionController extends Controller
{
    /**
     * Display a listing of task completions.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', TaskCompletion::class);

        $completions = TaskCompletion::with(['subscription.task'])->get();
        return TaskCompletionResource::collection($completions);
    }

    /**
     * Store a newly created task completion.
     *
     * @param Request $request
     * @return TaskCompletionResource
     */
    public function store(Request $request): TaskCompletionResource
    {
        $this->authorize('create', TaskCompletion::class);

        $validated = $request->validate([
            'subscription_id' => ['required', 'exists:task_subscriptions,id'],
            'scheduled_for' => ['required', 'date'],
            'completed_at' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(TaskStatus::class)],
        ]);

        $completion = TaskCompletion::create($validated);
        $completion->load(['subscription.task']);

        return new TaskCompletionResource($completion);
    }

    /**
     * Display the specified task completion.
     *
     * @param TaskCompletion $taskCompletion
     * @return TaskCompletionResource
     */
    public function show(TaskCompletion $taskCompletion): TaskCompletionResource
    {
        $this->authorize('view', $taskCompletion);

        $taskCompletion->load(['subscription.task']);
        return new TaskCompletionResource($taskCompletion);
    }

    /**
     * Update the specified task completion.
     *
     * @param Request $request
     * @param TaskCompletion $taskCompletion
     * @return TaskCompletionResource
     */
    public function update(Request $request, TaskCompletion $taskCompletion): TaskCompletionResource
    {
        $this->authorize('update', $taskCompletion);

        $validated = $request->validate([
            'subscription_id' => ['sometimes', 'exists:task_subscriptions,id'],
            'scheduled_for' => ['sometimes', 'date'],
            'completed_at' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', Rule::enum(TaskStatus::class)],
        ]);

        // Wrap in transaction to prevent race conditions
        $taskCompletion = DB::transaction(function () use ($taskCompletion, $validated) {
            // Lock the record to prevent concurrent modifications
            $taskCompletion = TaskCompletion::where('id', $taskCompletion->id)
                ->lockForUpdate()
                ->first();

            // Check if status is being changed to 'completed' and wasn't already completed
            $wasCompleted = $taskCompletion->status === TaskStatus::COMPLETED;
            $isNowCompleted = isset($validated['status']) && $validated['status'] === TaskStatus::COMPLETED->value;

            $taskCompletion->update($validated);
            $taskCompletion->refresh();
            $taskCompletion->load(['subscription.task', 'subscription.patient']);

            // Distribute rewards if task was just completed AND rewards haven't been distributed yet
            if ($isNowCompleted && !$wasCompleted && $taskCompletion->rewards_distributed_at === null) {
                $patient = $taskCompletion->subscription->patient;
                $task = $taskCompletion->subscription->task;

                if ($patient && $task) {
                    $xpValue = $task->xp_value ?? 0;
                    $gemValue = $task->gem_value ?? 0;

                    $patient->increment('experience', $xpValue);
                    $patient->increment('gems', $gemValue);

                    // Mark rewards as distributed
                    $taskCompletion->update(['rewards_distributed_at' => now()]);

                    \Log::info('Rewards distributed to patient', [
                        'patient_id' => $patient->id,
                        'task_id' => $task->id,
                        'task_completion_id' => $taskCompletion->id,
                        'xp_awarded' => $xpValue,
                        'gems_awarded' => $gemValue,
                        'rewards_distributed_at' => $taskCompletion->rewards_distributed_at,
                    ]);
                }
            }

            return $taskCompletion;
        });

        return new TaskCompletionResource($taskCompletion);
    }

    /**
     * Remove the specified task completion.
     *
     * @param TaskCompletion $taskCompletion
     * @return JsonResponse
     */
    public function destroy(TaskCompletion $taskCompletion): JsonResponse
    {
        $this->authorize('delete', $taskCompletion);

        $taskCompletion->delete();

        return response()->json([
            'message' => 'Task completion deleted successfully',
        ]);
    }
}
