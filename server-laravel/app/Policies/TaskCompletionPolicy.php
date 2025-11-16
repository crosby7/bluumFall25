<?php

namespace App\Policies;

use App\Models\Nurse;
use App\Models\Patient;
use App\Models\TaskCompletion;
use Illuminate\Auth\Access\Response;

class TaskCompletionPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * Nurses can view all task completions.
     * Patients can view their own task completions (filtered in controller).
     */
    public function viewAny(Nurse|Patient $user): bool
    {
        // Both nurses and patients can view task completions
        // (controller should filter to only show relevant records)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * Nurses can view any task completion.
     * Patients can only view their own task completions.
     */
    public function view(Nurse|Patient $user, TaskCompletion $taskCompletion): bool
    {
        // Nurses can view any task completion
        if ($user instanceof Nurse) {
            return true;
        }

        // Patients can only view their own task completions
        if ($user instanceof Patient) {
            return $taskCompletion->subscription->patient_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * Only nurses can create task completions.
     */
    public function create(Nurse|Patient $user): bool
    {
        // Only nurses can create task completions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can update the model.
     *
     * Nurses can update any task completion.
     * Patients can update their own task completions (to mark as complete).
     */
    public function update(Nurse|Patient $user, TaskCompletion $taskCompletion): bool
    {
        // Nurses can update any task completion
        if ($user instanceof Nurse) {
            return true;
        }

        // Patients can only update their own task completions
        if ($user instanceof Patient) {
            return $taskCompletion->subscription->patient_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only nurses can delete task completions.
     */
    public function delete(Nurse|Patient $user, TaskCompletion $taskCompletion): bool
    {
        // Only nurses can delete task completions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Nurse|Patient $user, TaskCompletion $taskCompletion): bool
    {
        // Only nurses can restore task completions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Nurse|Patient $user, TaskCompletion $taskCompletion): bool
    {
        // Only nurses can force delete task completions
        return $user instanceof Nurse;
    }
}
