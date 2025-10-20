<?php

namespace App\Policies;

use App\Models\Nurse;
use App\Models\Patient;
use Illuminate\Auth\Access\Response;

class NursePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * Only nurses can view the list of nurses.
     * Patients cannot access nurse management.
     */
    public function viewAny(Nurse|Patient $user): bool
    {
        // Only nurses can view the list of nurses
        // Patients have no access to nurse management
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can view the model.
     *
     * Only nurses can view nurse details.
     * Patients cannot access nurse management.
     */
    public function view(Nurse|Patient $user, Nurse $nurse): bool
    {
        // Only nurses can view nurse details
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can create models.
     *
     * Only nurses can create other nurse accounts.
     * Patients cannot create nurse accounts (prevents privilege escalation).
     */
    public function create(Nurse|Patient $user): bool
    {
        // Only nurses can create other nurse accounts
        // This prevents patients from escalating privileges
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can update the model.
     *
     * Only nurses can update nurse accounts.
     * Patients cannot modify nurse accounts.
     */
    public function update(Nurse|Patient $user, Nurse $nurse): bool
    {
        // Only nurses can update nurse accounts
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only nurses can delete nurse accounts.
     * Patients cannot delete nurse accounts.
     */
    public function delete(Nurse|Patient $user, Nurse $nurse): bool
    {
        // Only nurses can delete nurse accounts
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Nurse|Patient $user, Nurse $nurse): bool
    {
        // Only nurses can restore nurse accounts
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Nurse|Patient $user, Nurse $nurse): bool
    {
        // Only nurses can force delete nurse accounts
        return $user instanceof Nurse;
    }
}
