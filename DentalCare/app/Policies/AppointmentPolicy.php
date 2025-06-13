<?php

namespace App\Policies;

use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view appointments list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RendezVous $appointment): bool
    {
        // Users can view appointments if they are the patient, the dentist, or an admin
        return $user->id === $appointment->patient_id ||
               $user->id === $appointment->dentist_id ||
               $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isPatient(); // Only patients can create appointments
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RendezVous $appointment): bool
    {
        // Patients can update their own appointments
        // Dentists can update appointments assigned to them
        // Admins can update any appointment
        return $user->id === $appointment->patient_id ||
               $user->id === $appointment->dentist_id ||
               $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RendezVous $appointment): bool
    {
        // Only admins can delete appointments
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, RendezVous $appointment): bool
    {
        // Patients can cancel their own appointments
        // Dentists can cancel their assigned appointments
        // Admins can cancel any appointment
        return $user->id === $appointment->patient_id ||
               $user->id === $appointment->dentist_id ||
               $user->isAdmin();
    }

    /**
     * Determine whether the user can confirm the model.
     */
    public function confirm(User $user, RendezVous $appointment): bool
    {
        // Only dentists and admins can confirm appointments
        return $user->id === $appointment->dentist_id || $user->isAdmin();
    }
}
