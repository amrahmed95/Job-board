<x-layout>
    @if(auth()->user()->isJobSeeker())
        <x-breadcrumbs :links="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'My Applications', 'url' => route('applications.index')],
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
                <form method="POST" action="{{ route('job.application.update', $application) }}" class="flex items-center gap-4">
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
                    <p class="text-sm text-gray-500">Expected Salary</p>
                    <p class="font-medium">{{ $application->job->salary_currency }} {{ number_format($application->expected_salary) }}</p>
                </div>

                <div>
                    <a href="{{ Storage::url($application->resume_path) }}"
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
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">
                        @if(auth()->user()->isJobSeeker())
                            Employer Feedback
                        @else
                            Your Feedback
                        @endif
                    </h4>
                    <div class="bg-blue-50 p-4 rounded-md">
                        {!! nl2br(e($application->feedback)) !!}
                    </div>
                </div>
            @elseif(auth()->user()->id === $application->job->employer_id)
                <form method="POST" action="{{ route('job.application.update', $application) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mt-4">
                        <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Add Feedback</label>
                        <textarea id="feedback" name="feedback" rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('feedback', $application->feedback) }}</textarea>
                        <button type="submit"
                            class="mt-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Feedback
                        </button>
                    </div>
                </form>
            @endif
        </x-card>
    </div>
</x-layout>
