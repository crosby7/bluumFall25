<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $this->authorize('viewAny', Task::class);

        $tasks = Task::all();
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created task.
     *
     * @param Request $request
     * @return TaskResource
     */
    public function store(Request $request): TaskResource
    {
        $this->authorize('create', Task::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'xp_value' => ['required', 'integer', 'min:0'],
            'gem_value' => ['required', 'integer', 'min:0'],
        ]);

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    /**
     * Display the specified task.
     *
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    /**
     * Update the specified task.
     *
     * @param Request $request
     * @param Task $task
     * @return TaskResource
     */
    public function update(Request $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'xp_value' => ['sometimes', 'integer', 'min:0'],
            'gem_value' => ['sometimes', 'integer', 'min:0'],
        ]);

        $task->update($validated);

        return new TaskResource($task);
    }

    /**
     * Remove the specified task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }
}
