<?php

namespace App\Policies;

use App\Models\Nurse;
use App\Models\Patient;
use App\Models\TaskSubscription;
use Illuminate\Auth\Access\Response;

class TaskSubscriptionPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * Nurses can view all task subscriptions.
     * Patients cannot list all subscriptions (only their own via scoped queries).
     */
    public function viewAny(Nurse|Patient $user): bool
    {
        // Only nurses can view the list of all task subscriptions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can view the model.
     *
     * Nurses can view any task subscription.
     * Patients can only view their own task subscriptions.
     */
    public function view(Nurse|Patient $user, TaskSubscription $taskSubscription): bool
    {
        // Nurses can view any task subscription
        if ($user instanceof Nurse) {
            return true;
        }

        // Patients can only view their own task subscriptions
        if ($user instanceof Patient) {
            return $taskSubscription->patient_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * Only nurses can create task subscriptions.
     * Patients cannot create subscriptions (prevents XP farming).
     */
    public function create(Nurse|Patient $user): bool
    {
        // Only nurses can create task subscriptions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can update the model.
     *
     * Only nurses can update task subscriptions.
     * Patients cannot modify subscriptions (prevents treatment plan manipulation).
     */
    public function update(Nurse|Patient $user, TaskSubscription $taskSubscription): bool
    {
        // Only nurses can update task subscriptions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only nurses can delete task subscriptions.
     */
    public function delete(Nurse|Patient $user, TaskSubscription $taskSubscription): bool
    {
        // Only nurses can delete task subscriptions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can bulk create task subscriptions.
     *
     * Only nurses can bulk create task subscriptions for patients.
     */
    public function bulkStore(Nurse|Patient $user, Patient $patient): bool
    {
        // Only nurses can bulk create task subscriptions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Nurse|Patient $user, TaskSubscription $taskSubscription): bool
    {
        // Only nurses can restore task subscriptions
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Nurse|Patient $user, TaskSubscription $taskSubscription): bool
    {
        // Only nurses can force delete task subscriptions
        return $user instanceof Nurse;
    }
}
