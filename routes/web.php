<?php

use App\Models\Employer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\MyJobApplicationController;

use App\Models\JobApplication;

Route::get("/", function () {
    return redirect()->route("jobs.index");
})->name("home");

// Public job routes
Route::resource("jobs", JobController::class)->only([
    'index',
    'show'
]);

// Public employer routes
Route::resource('employers', EmployerController::class)
    ->only(['index', 'show'])
    ->names([
        'index' => 'employers.index',
        'show' => 'employers.show'
    ]);

// Authenticated employer management routes
Route::middleware('auth')->group(function() {
    Route::resource('employers', EmployerController::class)
        ->only(['edit', 'update', 'destroy'])
        ->names([
            'edit' => 'employers.edit',
            'update' => 'employers.update',
            'destroy' => 'employers.destroy'
        ]);
});

// Authentication routes
Route::get("login", [AuthController::class, "create"])->name("login");
Route::post("auth", [AuthController::class, "store"])->name("auth.store");
Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');
Route::post('register', [AuthController::class, 'register'])->name('register');

// /employer/register
Route::prefix('employer')->group(function() {
    // Employer Registration Routes
    Route::get('register', [\App\Http\Controllers\Auth\EmployerRegisterController::class, 'create'])
        ->name('employer.register.create');

    Route::post('register', [\App\Http\Controllers\Auth\EmployerRegisterController::class, 'store'])
        ->name('employer.register.store');
});

// Authenticated routes
Route::middleware('auth')->group(function() {
    // Nested job application routes
    Route::prefix('jobs/{job}')->group(function() {
        Route::get('applications/create', [JobApplicationController::class, 'create'])
            ->name('job.application.create');
        Route::post('applications', [JobApplicationController::class, 'store'])
            ->name('job.application.store');

        // Application-specific routes
        Route::prefix('applications/{application}')->group(function() {
            Route::get('/', [JobApplicationController::class, 'show'])
                ->name('job.application.show');
            Route::patch('/', [JobApplicationController::class, 'update'])
                ->name('job.application.update');
        });
    });

    // My job applications routes
    Route::resource('my-job-applications', MyJobApplicationController::class)
        ->only(['index', 'destroy'])
        ->names([
            'index' => 'my-job-applications.index',
            'destroy' => 'my-job-applications.destroy'
        ]);
});



Route::middleware('auth')->get('/download-resume/{application}', function(JobApplication $application)
{

    if (!Storage::disk('private')->exists($application->resume_path)) {
        abort(404);
    }

    return Storage::disk('private')->download($application->resume_path);
})->name('download.resume');


// Employer-specific routes
Route::prefix('employer')
    ->middleware(['auth', \App\Http\Middleware\EnsureEmployer::class])
    ->group(function() {
        Route::get('dashboard', [\App\Http\Controllers\Employer\DashboardController::class, 'index'])
            ->name('employer.dashboard');

        Route::resource('jobs', \App\Http\Controllers\Employer\JobController::class)
            ->only(['create', 'store', 'index', 'edit', 'update', 'destroy'])
            ->names([
                'index' => 'employer.jobs.index',
                'create' => 'employer.jobs.create',
                'store' => 'employer.jobs.store',
                'edit' => 'employer.jobs.edit',
                'update' => 'employer.jobs.update',
                'destroy' => 'employer.jobs.destroy'
            ]);


        Route::put('jobs/{job}', [\App\Http\Controllers\Employer\JobController::class, 'update'])
            ->name('employer.jobs.update');

        Route::get('jobs/{job}/edit', [\App\Http\Controllers\Employer\JobController::class, 'edit'])
            ->name('employer.jobs.edit');

        Route::get('jobs/{job}/delete', [\App\Http\Controllers\Employer\JobController::class, 'destroy'])
            ->name('employer.jobs.destroy');

        Route::get('jobs/{job}/applications', [\App\Http\Controllers\Employer\JobController::class, 'applications'])
            ->name('employer.jobs.applications');

        Route::get('jobs/{job}/applications/{application}', [\App\Http\Controllers\Employer\JobController::class, 'showApplication'])
            ->name('employer.jobs.applications.show');

        Route::Patch('jobs/{job}/applications/{application}', [\App\Http\Controllers\Employer\JobController::class, 'updateApplication'])
            ->name('employer.jobs.applications.update');


    });
