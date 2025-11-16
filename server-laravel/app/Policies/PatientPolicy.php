<?php

namespace App\Policies;

use App\Models\Nurse;
use App\Models\Patient;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * Nurses can view all patients.
     * Patients cannot list all patients (only their own profile).
     */
    public function viewAny(Nurse|Patient $user): bool
    {
        // Only nurses can view the list of all patients
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can view the model.
     *
     * Nurses can view any patient.
     * Patients can only view their own profile.
     */
    public function view(Nurse|Patient $user, Patient $patient): bool
    {
        // Nurses can view any patient
        if ($user instanceof Nurse) {
            return true;
        }

        // Patients can only view their own profile
        if ($user instanceof Patient) {
            return $user->id === $patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * Only nurses can create patients.
     */
    public function create(Nurse|Patient $user): bool
    {
        // Only nurses can create new patients
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can update the model.
     *
     * Nurses can update any patient.
     * Patients can only update their own profile.
     */
    public function update(Nurse|Patient $user, Patient $patient): bool
    {
        // Nurses can update any patient
        if ($user instanceof Nurse) {
            return true;
        }

        // Patients can only update their own profile
        if ($user instanceof Patient) {
            return $user->id === $patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only nurses can delete patients.
     */
    public function delete(Nurse|Patient $user, Patient $patient): bool
    {
        // Only nurses can delete patients
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Nurse|Patient $user, Patient $patient): bool
    {
        // Only nurses can restore patients
        return $user instanceof Nurse;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Nurse|Patient $user, Patient $patient): bool
    {
        // Only nurses can force delete patients
        return $user instanceof Nurse;
    }
}
