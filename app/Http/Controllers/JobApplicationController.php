<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JobApplicationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Job $job)
    {
        // Ensure user is a job seeker
        if(!Auth::user()->isJobSeeker())
        {
            abort(403, 'Only job seekers can apply for jobs');
        }

        // Check if user already applied
        if($job->hasUserApplied(Auth::user()))
        {
            return redirect()
                ->route("jobs.show", $job)
                ->with('error', 'You have already applied for this job.');
        }


        return view("job_applications.create", [
            "job"=> $job,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Job $job)
    {
        $request->validate([
            'expected_salary' => 'required|numeric|min:1000|max:1000000',
            'cover_letter' => 'nullable|string|min:50|max:2000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Store the resume file
        $resumePath = $request->file('resume')->store('resumes', 'private');

        // Create the job application
        JobApplication::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'expected_salary' => $request->input('expected_salary'),
            'cover_letter' => $request->input('cover_letter') ?? null,
            'resume_path' => $resumePath,
            'status' => JobApplication::STATUS_SUBMITTED,
        ]);

        return redirect()
            ->route("jobs.show", $job)
            ->with('success', 'Your application has been submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job, JobApplication $application)
    {
        // Verify the application belongs to the job
        if ($application->job_id !== $job->id) {
            abort(404);
        }

        // Eager load the necessary relationships
        $application->load(['job.employer', 'user']);

        // Ensure the user can view this application
        $this->authorize('view', $application);

        return view('job_applications.show', [
            'application' => $application,
            'job' => $job
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job, JobApplication $application)
    {
        // Verify the application belongs to the job
        if ($application->job_id !== $job->id) {
            abort(404);
        }

        $this->authorize('update', $application);

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(JobApplication::getStatusOptions())),
            'feedback' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', 'Application status updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
