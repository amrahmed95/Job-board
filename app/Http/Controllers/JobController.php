<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Category;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $query = Job::query()->with('category');

    // Title filter
    if (request()->filled('title')) {
        $query->where('title', 'like', '%' . request('title') . '%')
        ->orWhere('description', 'like', '%' . request('title') . '%');
    }

    // Salary range filter
    if (request()->filled('min_salary') || request()->filled('max_salary'))
    {
        $minSalary = request('min_salary', 0); // Default to 0 if not provided
        $maxSalary = request('max_salary', PHP_INT_MAX); // Default to max if not provided

        // Convert to integers
        $minSalary = (int)$minSalary;
        $maxSalary = (int)$maxSalary;

        // Validate min is less than max
        if ($minSalary <= $maxSalary) {
            $query->whereBetween('salary', [$minSalary, $maxSalary]);
        }
    }

    // filter by experience
    if (request()->filled('experience')) {
        $query->where('experience', request('experience'));
    }

    // filter by job category
    if (request()->filled('category')) {
        $query->where('category_id', request('category'));
    }

    // filter by city
    if (request()->filled('city')) {
        $query->where('city', 'like', '%'.request('city').'%');
    }

    // filter by country
    if (request()->filled('country')) {
        $query->where('country', 'like', '%'.request('country').'%');
    }

    // filter by employment type
    if (request()->filled('employment_type')) {
        $query->where('employment_type', request('employment_type'));
    }

    $jobs = $query->latest()->paginate(10);
    $categories = Category::all();

    return view('job.index', compact('jobs', 'categories'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('job.show', ['job' => $job]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        //
    }
}
