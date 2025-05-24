<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmployerRegisterController extends Controller
{
    public function create()
    {
        return view('auth.employer-register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'employer_name' => ['required', 'string', 'max:255'],
            'employer_website' => ['nullable', 'url'],
            'employer_category' => ['required', 'exists:categories,id']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employer'
        ]);

        Employer::create([
            'name' => $request->employer_name,
            'website' => $request->employer_website,
            'category_id' => $request->employer_category,
            'user_id' => $user->id
        ]);

        auth()->login($user);

        return redirect()->route('employer.dashboard')
            ->with('success', 'Your account is created successfully!
                                            Registration successful! Welcome to the employer dashboard.');
    }
}
