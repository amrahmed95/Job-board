<?php

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
Route::resource("employers", EmployerController::class)->only([
    'index',
    'show'
]);

// Authentication routes
Route::get("login", [AuthController::class, "create"])->name("login");
Route::post("auth", [AuthController::class, "store"])->name("auth.store");
Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');

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
