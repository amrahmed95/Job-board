<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

   protected $policies = [
        JobApplication::class => JobApplicationPolicy::class,
        Employer::class => EmployerPolicy::class,
        Job::class => JobPolicy::class,
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
