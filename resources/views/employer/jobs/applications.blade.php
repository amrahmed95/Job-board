<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Employer Dashboard', 'url' => route('employer.dashboard')],
        ['label' => $job->title, 'url' => route('jobs.show', $job)],
        ['label' => 'Applications', 'url' => '#'],
    ]" class="mb-4" />

    <x-card class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold">Applications for: {{ $job->title }}</h2>
                <p class="text-gray-600 mt-1">
                    {{ $applications->total() }} {{ Str::plural('application', $applications->total()) }} received
                </p>
            </div>
            <a href="{{ route('jobs.show', $job) }}"
               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Back to Job
            </a>
        </div>
    </x-card>

    @if($applications->isEmpty())
        <x-card>
            <div class="text-center py-8">
                <p class="text-gray-500 mb-4">No applications received yet.</p>
            </div>
        </x-card>
    @else
        <div class="space-y-4">
            @foreach($applications as $application)
                <x-card class="hover:shadow-md transition-shadow">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Applicant Info -->
                        <div class="md:w-1/3">
                            <div class="flex items-center space-x-4">
                                <div class="bg-gray-100 p-3 rounded-full">
                                    <i class="fas fa-user text-gray-500 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium">{{ $application->user->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $application->user->email }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Applied {{ $application->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                <div>
                                    <span class="text-sm text-gray-500">Expected Salary:</span>
                                    <span class="font-medium">{{ $job->salary_currency }} {{ number_format($application->expected_salary) }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('download.resume', $application) }}" target="_blank"
                                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-download mr-1"></i> Download Resume
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Application Details -->
                        <div class="md:w-2/3">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="font-semibold capitalize px-2 py-1 rounded-full text-xs
                                        @if($application->status === 'submitted') bg-blue-100 text-blue-800
                                        @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800
                                        @elseif($application->status === 'interview_scheduled') bg-purple-100 text-purple-800
                                        @elseif($application->status === 'offer_extended') bg-green-100 text-green-800
                                        @elseif($application->status === 'hired') bg-emerald-100 text-emerald-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ str_replace('_', ' ', $application->status) }}
                                    </span>
                                </div>
                                <form method="POST"
                                      action="{{ route('job.application.update', ['job' => $job, 'application' => $application]) }}"
                                      class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status"
                                            class="text-xs rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach(App\Models\JobApplication::getStatusOptions() as $value => $label)
                                            <option value="{{ $value }}" {{ $application->status === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                            class="text-xs inline-flex items-center px-2 py-1 border border-transparent font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        Update
                                    </button>
                                </form>
                            </div>

                            <div class="prose max-w-none text-sm mb-4">
                                <h4 class="font-medium text-gray-700 mb-1">Cover Letter</h4>
                                {!! nl2br(e($application->cover_letter)) !!}
                            </div>

                            @if($application->feedback)
                                <div class="bg-blue-50 p-3 rounded-md mb-3">
                                    <h4 class="font-medium text-gray-700 mb-1 text-sm">Your Feedback</h4>
                                    {!! nl2br(e($application->feedback)) !!}
                                </div>
                            @endif

                            <form method="POST"
                                  action="{{ route('employer.jobs.applications.update', ['job' => $job, 'application' => $application]) }}">
                                @csrf
                                @method('PATCH')
                                <div class="mt-2">
                                    <label for="feedback-{{ $application->id }}" class="sr-only">Feedback</label>
                                    <textarea id="feedback-{{ $application->id }}" name="feedback" rows="2"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                        placeholder="Add feedback for the applicant...">{{ old('feedback', $application->feedback) }}</textarea>
                                    <button type="submit"
                                        class="mt-1 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        Save Feedback
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    @endif
</x-layout>
