<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\Employer;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class JobController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // $this->authorizeResource([Employer::class], 'employer');
    }

    public function create()
    {
        $this->authorize('create', Employer::class);

        return view('employer.jobs.create', [
            'categories' => Category::all(),
            'employmentTypes' => Job::$employment_type,
            'workLocationTypes' => Job::$work_location_type,
            'experienceLevels' => Job::$experience,
            'salaryPeriods' => Job::$salary_period
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Employer::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer|min:0',
            'salary_currency' => 'required|string|size:3',
            'salary_period' => 'required|in:' . implode(',', Job::$salary_period),
            'employment_type' => 'required|in:' . implode(',', Job::$employment_type),
            'work_location_type' => 'required|in:' . implode(',', Job::$work_location_type),
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'experience' => 'required|in:' . implode(',', Job::$experience),
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['employer_id'] = auth()->user()->employer->id;

        Job::create($validated);

        return redirect()->route('employer.dashboard')
            ->with('success', 'Job posted successfully!');
    }

    public function edit(Job $job)
    {
        $this->authorize('update', $job);

        return view('employer.jobs.edit', [
            'job' => $job,
            'categories' => Category::all(),
            'employmentTypes' => Job::$employment_type,
            'workLocationTypes' => Job::$work_location_type,
            'experienceLevels' => Job::$experience,
            'salaryPeriods' => Job::$salary_period
        ]);
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer|min:0',
            'salary_currency' => 'required|string|size:3',
            'salary_period' => 'required|in:' . implode(',', Job::$salary_period),
            'employment_type' => 'required|in:' . implode(',', Job::$employment_type),
            'work_location_type' => 'required|in:' . implode(',', Job::$work_location_type),
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'experience' => 'required|in:' . implode(',', Job::$experience),
        ]);

        $job->update($validated);

        return redirect()->route('employer.dashboard')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);

        $job->delete();

        return redirect()->route('employer.dashboard')
            ->with('success', 'Job deleted successfully!');
    }


    public function applications(Job $job)
    {
        $this->authorize('viewApplications', $job);

        return view('employer.jobs.applications', [
            'job' => $job,
            'applications' => $job->jobApplications()
                ->with('user')
                ->latest()
                ->paginate(10)
        ]);
    }

    public function showApplication(Job $job, JobApplication $application)
    {
        $this->authorize('view', $job);
        $this->authorize('view', $application);

        return view('employer.jobs.application-show', [
            'job' => $job,
            'application' => $application->load('user')
        ]);
    }


    public function updateApplication(Request $request, Job $job, JobApplication $application)
    {
        $this->authorize('update', $job);
        $this->authorize('update', $application);

        $validated = $request->validate([
            'feedback' => 'nullable|string|max:1000',
            'status' => 'nullable|in:' . implode(',', array_keys(JobApplication::getStatusOptions()))
        ]);

        // Update only the fields that were provided in the request
        if (isset($validated['feedback'])) {
            $application->feedback = $validated['feedback'];
        }

        if (isset($validated['status'])) {
            $application->status = $validated['status'];
        }

        $application->save();

        return redirect()->route('employer.jobs.applications', $job)
            ->with('success', 'Application updated successfully!');
    }

}
