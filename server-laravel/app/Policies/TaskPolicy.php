<?php

namespace App\Policies;

use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Task;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * Both nurses and patients can view the list of tasks.
     * Nurses need to see tasks for assignment.
     * Patients need to see tasks to understand what's available.
     */
    public function viewAny(Nurse|Patient $user): bool
    {
        // Both nurses and patients can view the task list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * Both nurses and patients can view individual task details.
     */
    public function view(Nurse|Patient $user, Task $task): bool
    {
        // Both nurses and patients can view task details
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * Only nurses can create tasks.
     * Patients cannot create tasks (prevents system manipulation).
     */
    public function create(Nurse|Patient $user): bool
    {
        // Only nurses can create tasks
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can update the model.
     *
     * Only nurses can update tasks.
     * Patients cannot modify tasks (prevents XP/reward manipulation).
     */
    public function update(Nurse|Patient $user, Task $task): bool
    {
        // Only nurses can update tasks
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only nurses can delete tasks.
     */
    public function delete(Nurse|Patient $user, Task $task): bool
    {
        // Only nurses can delete tasks
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Nurse|Patient $user, Task $task): bool
    {
        // Only nurses can restore tasks
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Nurse|Patient $user, Task $task): bool
    {
        // Only nurses can force delete tasks
        return $user instanceof Nurse;
    }
}
