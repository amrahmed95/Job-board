<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyJobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Auth::user()->jobApplications()
            ->with([
                "job"=> fn($query) => $query->withCount("jobApplications"),
                "job.employer",
            ])
            ->latest()
            ->paginate(10);
        return view("my-job-applications.index", compact('applications'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $myJobApplication)
    {
        // Delete the application
        $myJobApplication->delete();

        return redirect()
            ->route("my-job-applications.index")
            ->with('success', 'Job application deleted successfully.');
    }
}
