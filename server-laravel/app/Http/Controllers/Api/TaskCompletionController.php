<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCompletionResource;
use App\Models\TaskCompletion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        \Log::info('Updating TaskCompletion ID: ' . $taskCompletion->id);
        $this->authorize('update', $taskCompletion);

        $validated = $request->validate([
            'subscription_id' => ['sometimes', 'exists:task_subscriptions,id'],
            'scheduled_for' => ['sometimes', 'date'],
            'completed_at' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', Rule::enum(TaskStatus::class)],
        ]);

        \Log::info('Validated Data: ', $validated);

        $taskCompletion->update($validated);
        $taskCompletion->load(['subscription.task']);

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
