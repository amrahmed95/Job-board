<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'My Applications', 'url' => '#']
    ]" class="mb-4" />

    <x-card class="mb-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">My Job Applications</h1>
            <div class="text-sm text-gray-600">
                {{ $applications->count() }} {{ Str::plural('application', $applications->count()) }}
            </div>
        </div>
    </x-card>

    @forelse ($applications as $application)
        <x-card class="mb-4 hover:shadow-md transition-shadow">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ $application->job->employer->logo_url }}"
                             alt="{{ $application->job->employer->name }}"
                             class="h-12 w-12 rounded-full object-cover border border-gray-200">
                        <div>
                            <h2 class="font-medium text-gray-900">
                                <a href="{{ route('employers.show', $application->job->employer) }}">
                                    {{ $application->job->employer->name }}
                                </a>
                            </h2>
                            <h3 class="text-lg font-semibold hover:text-blue-600 transition-colors">
                                <a href="{{ route('jobs.show', $application->job) }}">
                                    {{ $application->job->title }}
                                </a>
                            </h3>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                        <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $application->job->city }}, {{ $application->job->country }}</span>
                        <span>•</span>
                        <span><i class="fas fa-briefcase mr-1"></i> {{ ucfirst($application->job->employment_type) }}</span>
                        <span>•</span>
                        <span><i class="far fa-clock mr-1"></i> Applied {{ $application->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium px-2 py-1 rounded-full
                            @if($application->status === 'submitted') bg-blue-100 text-blue-800
                            @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800
                            @elseif($application->status === 'interview_scheduled') bg-purple-100 text-purple-800
                            @elseif($application->status === 'offer_extended') bg-green-100 text-green-800
                            @elseif($application->status === 'hired') bg-emerald-100 text-emerald-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ str_replace('_', ' ', $application->status) }}
                        </span>

                        <span class="text-sm text-gray-600">
                            Expected Salary: {{ $application->job->salary_currency }} {{ number_format($application->expected_salary) }} / {{ $application->job->salary_period }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('job.application.show', ['job' => $application->job, 'application' => $application]) }}"
                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>

                    <form method="POST" action="{{ route('my-job-applications.destroy', $application) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                onclick="return confirm('Are you sure you want to withdraw this application?')">
                            <i class="fas fa-trash-alt mr-1"></i> Withdraw
                        </button>
                    </form>
                </div>
            </div>
        </x-card>
    @empty
        <x-card>
            <div class="py-8 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-file-alt fa-3x"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No applications yet</h3>
                <p class="text-gray-500 mb-4">When you apply for jobs, they'll appear here.</p>
                <a href="{{ route('jobs.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Browse Jobs
                </a>
            </div>
        </x-card>
    @endforelse
</x-layout>
