<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmployerController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $employers = Employer::with('category')->latest()->paginate(12);
        return view('employers.showProfiles', [
            'employers' => $employers,
            'categories' => Category::all()
        ]);
    }

    public function show(Employer $employer)
    {
        $jobs = $employer->jobs()
            ->with('category')
            ->filter(request()->all())
            ->latest()
            ->paginate(10);

        return view('employers.showProfile', [
            'employer' => $employer->load('category'),
            'jobs' => $jobs,
            'categories' => Category::all()
        ]);
    }

    /**
     * Show the form for editing the employer profile.
     */
    public function edit(Employer $employer)
    {
        $this->authorize('update', $employer);

        return view('employers.editProfile', [
            'employer' => $employer,
            'categories' => Category::all()
        ]);
    }

    /**
     * Update the employer profile in storage.
     */
    public function update(Request $request, Employer $employer)
    {
        $this->authorize('update', $employer);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url'],
            'category_id' => ['required', 'exists:categories,id'],
            'logo' => ['nullable', 'image', 'max:2048']
        ]);

        $data = [
            'name' => $request->name,
            'website' => $request->website,
            'category_id' => $request->category_id
        ];

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('employers/logos', 'public');
            $data['logo'] = $path;
        }

        $employer->update($data);

        return redirect()->route('employers.show', $employer)
            ->with('success', 'Employer profile updated successfully!');
    }

    /**
     * Remove the employer profile from storage.
     */
    public function destroy(Employer $employer)
    {
        // Delete associated user account
        $employer->user()->delete();

        // Delete employer profile
        $employer->delete();

        // Logout if deleting own account
        if (auth()->id() === $employer->user_id) {
            auth()->logout();
            return redirect()->route('jobs.index')
                ->with('success', 'Your employer account has been deleted.');
        }

        return redirect()->route('employers.index')
            ->with('success', 'Employer account deleted successfully!');
    }
}
