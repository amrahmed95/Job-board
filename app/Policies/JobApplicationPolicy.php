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
        // Eager load the job relationship to prevent N+1 queries
        $application->loadMissing(['job.employer', 'user']);

        // Check if the job exist
        if (!$application->job) {
            return false;
        }

        // Job seeker can view their own application
        if ($user->isJobSeeker() && $user->id === $application->user_id) {
            return true;
        }

        // Employer can view applications to their jobs
        if ($user->isEmployer() && $user->employer) {
            return $user->employer->id === $application->job->employer_id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the application status.
     */
    public function update(User $user, JobApplication $application)
    {
        $application->loadMissing('job.employer');

        if (!$application->job) {
            return false;
        }

        // Only the employer who owns the job can update status
        return $user->isEmployer() &&
            $user->employer &&
            $user->employer->id === $application->job->employer_id;
    }
}
