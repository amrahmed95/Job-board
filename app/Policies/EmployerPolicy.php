<?php

namespace App\Policies;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Employer $employer): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isEmployer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Employer $employer): bool
    {
        return $user->id === $employer->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Employer $employer): bool
    {
        return $user->id === $employer->user_id;;
    }

   /**
     * Determine whether the user can post a job.
     */
    public function postJob(User $user, Employer $employer)
    {
        return $user->id === $employer->user_id;
    }

    public function editProfile(User $user, Employer $employer)
    {
        return $user->id === $employer->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Employer $employer): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Employer $employer): bool
    {
        return false;
    }
}
