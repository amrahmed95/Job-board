<x-layout>
    @if(auth()->user()->isJobSeeker())
        <x-breadcrumbs :links="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'My Applications', 'url' => route('my-job-applications.index')],
            ['label' => 'Application Details', 'url' => '#'],
        ]" class="mb-4" />
    @else
        <x-breadcrumbs :links="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Job Postings', 'url' => route('jobs.index')],
            ['label' => $application->job->title, 'url' => route('jobs.show', $application->job)],
            ['label' => 'Applications', 'url' => route('jobs.applications', $application->job)],
            ['label' => 'Application Details', 'url' => '#'],
        ]" class="mb-4" />
    @endif

    <x-card class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
            <div>
                <h2 class="text-xl font-bold">Application for: {{ $application->job->title }}</h2>
                <p class="text-gray-600 mt-1">
                    Status:
                    <span class="font-semibold capitalize px-2 py-1 rounded-full
                        @if($application->status === 'submitted') bg-blue-100 text-blue-800
                        @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800
                        @elseif($application->status === 'interview_scheduled') bg-purple-100 text-purple-800
                        @elseif($application->status === 'offer_extended') bg-green-100 text-green-800
                        @elseif($application->status === 'hired') bg-emerald-100 text-emerald-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ str_replace('_', ' ', $application->status) }}
                    </span>
                </p>
            </div>

            @if(auth()->user()->id === $application->job->employer_id)
                <form method="POST"
                    action="{{ route('job.application.update', ['job' => $application->job, 'application' => $application]) }}"
                    class="flex items-center gap-4">
                    @csrf
                    @method('PATCH')

                    <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(App\Models\JobApplication::getStatusOptions() as $value => $label)
                            <option value="{{ $value }}" {{ $application->status === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Status
                    </button>
                </form>
            @endif
        </div>
    </x-card>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Applicant Information -->
        <x-card>
            <h3 class="text-lg font-medium mb-4">Applicant Information</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium">{{ $application->user->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $application->user->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Applied On</p>
                    <p class="font-medium">{{ $application->created_at->format('F j, Y') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Submitted - Expected Salary</p>
                    <p class="font-medium">{{ $application->job->salary_currency }} {{ number_format($application->expected_salary) }}</p>
                </div>


                <div>
                    <a href="{{ route('download.resume', $application) }}"
                       target="_blank"
                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-download mr-2"></i> Download Resume
                    </a>
                </div>
            </div>
        </x-card>

        <!-- Application Details -->
        <x-card>
            <h3 class="text-lg font-medium mb-4">Application Details</h3>

            <div class="mb-6">
                <h4 class="font-medium text-gray-700 mb-2">Cover Letter</h4>
                <div class="prose max-w-none">
                    {!! nl2br(e($application->cover_letter)) !!}
                </div>
            </div>

            @if($application->feedback)
                <div class="mb-6">
                    <h4 class="font-medium text-gray-700 mb-2">Employer Feedback</h4>
                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-0.5">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="feedback-container bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 shadow-sm">
                                <div class="feedback-header flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <div class="employer-avatar mr-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                                                {{ substr($application->job->employer->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-blue-800">
                                                {{ $application->job->employer->name }}
                                            </h4>
                                            <div class="flex items-center text-xs text-blue-600 mt-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Last updated {{ $application->updated_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="status-badge px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @switch($application->status)
                                                @case(\App\Models\JobApplication::STATUS_REJECTED)
                                                    bg-red-100 text-red-800
                                                    @break
                                                @case(\App\Models\JobApplication::STATUS_HIRED)
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case(\App\Models\JobApplication::STATUS_OFFER_EXTENDED)
                                                    bg-emerald-100 text-emerald-800
                                                    @break
                                                @case(\App\Models\JobApplication::STATUS_INTERVIEW_SCHEDULED)
                                                    bg-purple-100 text-purple-800
                                                    @break
                                                @case(\App\Models\JobApplication::STATUS_UNDER_REVIEW)
                                                    bg-blue-100 text-blue-800
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800
                                            @endswitch">
                                        {{ \App\Models\JobApplication::getStatusOptions()[$application->status] }}
                                    </span>
                                </div>

                                <div class="feedback-content bg-white rounded-md p-4 border border-blue-100">
                                    <div class="prose prose-sm max-w-none text-gray-700">
                                        {!! nl2br(e($application->feedback)) !!}
                                    </div>

                                    <!-- Status-specific messages -->
                                    @switch($application->status)
                                        @case(\App\Models\JobApplication::STATUS_SUBMITTED)
                                            <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-md flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800">Application Received</p>
                                                    <p class="text-sm text-gray-700">Your application has been successfully submitted. The employer will review it shortly.</p>
                                                </div>
                                            </div>
                                            @break

                                        @case(\App\Models\JobApplication::STATUS_UNDER_REVIEW)
                                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800">Under Review</p>
                                                    <p class="text-sm text-blue-700">The employer is currently reviewing your application. This process may take a few days.</p>
                                                </div>
                                            </div>
                                            @break

                                        @case(\App\Models\JobApplication::STATUS_INTERVIEW_SCHEDULED)
                                            <div class="mt-4 p-3 bg-purple-50 border border-purple-200 rounded-md flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-purple-800">Interview Scheduled</p>
                                                    <p class="text-sm text-purple-700">Please check your email for interview details and preparation tips.</p>
                                                </div>
                                            </div>
                                            @break

                                        @case(\App\Models\JobApplication::STATUS_OFFER_EXTENDED)
                                            <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-md flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-emerald-800">Offer Extended</p>
                                                    <p class="text-sm text-emerald-700">Congratulations! The employer has extended an offer. Please review the details in your email.</p>
                                                </div>
                                            </div>
                                            @break

                                        @case(\App\Models\JobApplication::STATUS_HIRED)
                                            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-green-800">Hired</p>
                                                    <p class="text-sm text-green-700">Welcome to the team! Your onboarding details will be shared soon.</p>
                                                </div>
                                            </div>
                                            @break

                                        @case(\App\Models\JobApplication::STATUS_REJECTED)
                                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-red-800">Not Selected</p>
                                                    <p class="text-sm text-red-700">Thank you for your application. While we were impressed with your qualifications, we've decided to move forward with other candidates.</p>
                                                </div>
                                            </div>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-100 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">No feedback yet</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>The employer hasn't provided feedback on your application yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Application Status -->
            <div class="mt-6">
                <h4 class="font-medium text-gray-700 mb-2">Application Status</h4>
                @php
                    $statusColors = [
                        'submitted' => 'bg-gray-100 text-gray-800',
                        'under_review' => 'bg-blue-100 text-blue-800',
                        'interview_scheduled' => 'bg-purple-100 text-purple-800',
                        'offer_extended' => 'bg-green-100 text-green-800',
                        'hired' => 'bg-emerald-100 text-emerald-800',
                        'rejected' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$application->status] ?? 'bg-gray-100' }}">
                    {{ \App\Models\JobApplication::getStatusOptions()[$application->status] ?? $application->status }}
                </span>
            </div>


            <!-- Job Details Summary -->
            <div class="mt-6 border-t pt-4">
                <h4 class="font-medium text-gray-700 mb-2">Job Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Position:</p>
                        <p class="font-medium">{{ $application->job->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Company:</p>
                        <p class="font-medium">{{ $application->job->employer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Salary:</p>
                        <p class="font-medium">{{ $application->job->formatted_salary }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Location:</p>
                        <p class="font-medium">{{ $application->job->work_location_type_name }}</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-layout>
