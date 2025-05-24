<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\Employer;
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
}
