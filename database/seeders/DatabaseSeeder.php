<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\Employer;
use App\Models\Category;
use App\Models\JobApplication;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'role' => 'job_seeker' // Ensure this matches your role enum
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'employer'
        ]);

        // Create storage link if it doesn't exist
        if (!file_exists(public_path('storage'))) {
            \Artisan::call('storage:link');
        }

        // Create categories
        $categories = Category::factory()->count(15)->create();

        // Create 300 users - some will be employers, some job seekers
        $users = User::factory()->count(300)->create();

        // Make random 30 users employers
        $employerUsers = $users->shuffle()->take(30);
        $employerUsers->each(function ($user) use ($categories) {
            $user->update(['role' => 'employer']);

            $employer = Employer::factory()->create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
                'logo' => $this->getPlaceholderLogo(),
            ]);

            // Each employer creates 2-10 jobs
            Job::factory()->count(rand(2, 10))->create([
                'employer_id' => $employer->id,
                'category_id' => $categories->random()->id
            ]);
        });

        // Remaining users are job seekers (no employer record)
        $jobSeekerUsers = $users->diff($employerUsers);
        $jobSeekerUsers->each(function ($user) {
            $user->update(['role' => 'job_seeker']);
        });

        // Create job applications
        $jobs = Job::all();

        foreach ($jobSeekerUsers as $user) {
            $jobsToApply = $jobs->shuffle()->take(rand(1, 4));

            foreach ($jobsToApply as $job) {
                // Ensure user hasn't already applied to this job
                if (!$job->jobApplications()->where('user_id', $user->id)->exists() && !$job->jobApplications()->where('user_id', $user->id)->exists()) {
                    JobApplication::factory()->create([
                        'user_id' => $user->id,
                        'job_id' => $job->id,
                        'status' => $this->getWeightedStatus(),
                    ]);
                }
            }
        }
    }

    private function getPlaceholderLogo()
    {
        $logos = [
            'company-logo-1.png',
            'company-logo-2.png',
            'company-logo-3.png',
        ];

        return 'employers/logos/' . fake()->randomElement($logos);
    }

    private function getWeightedStatus()
    {
        $statuses = [
            'submitted' => 40,
            'under_review' => 25,
            'interview_scheduled' => 15,
            'offer_extended' => 10,
            'hired' => 5,
            'rejected' => 5,
        ];

        $total = array_sum($statuses);
        $rand = rand(1, $total);
        $current = 0;

        foreach ($statuses as $status => $weight) {
            $current += $weight;
            if ($rand <= $current) {
                return $status;
            }
        }

        return 'submitted';
    }
}
