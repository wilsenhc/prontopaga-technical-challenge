<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use App\RoleEnum;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role_id === RoleEnum::DOCTOR;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->patient_id || $user->id === $appointment->doctor_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role_id === RoleEnum::PATIENT;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return $user->role_id === RoleEnum::PATIENT;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->patient_id || $user->id === $appointment->doctor_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->patient_id || $user->id === $appointment->doctor_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->patient_id || $user->id === $appointment->doctor_id;
    }
}
