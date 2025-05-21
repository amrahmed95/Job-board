<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the application.
     */
    public function view(User $user, JobApplication $application)
    {
        // User can view if they're the applicant or the job owner
        return $user->id === $application->user_id ||
               $user->id === $application->job->employer_id;
    }

    /**
     * Determine whether the user can update the application status.
     */
    public function update(User $user, JobApplication $application)
    {
        // Only the employer who owns the job can update status
        return $user->id === $application->job->employer_id;
    }
}
