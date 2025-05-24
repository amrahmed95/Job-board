<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $employer = auth()->user()->employer;

        return view('employer.dashboard', [
            'employer' => $employer,
            'jobs' => $employer->jobs()->latest()->paginate(10)
        ]);
    }
}
