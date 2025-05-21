<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'My Applications', 'url' => '#'],
    ]" class="mb-4" />

    <x-card class="mb-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">My Job Applications</h1>
            <div class="text-sm text-gray-600">
                {{ $applications->total() }} {{ Str::plural('application', $applications->total()) }}
            </div>
        </div>
    </x-card>

    @forelse ($applications as $application)
        <x-card class="mb-4 hover:shadow-md transition-shadow">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <h2 class="text-lg font-semibold hover:text-blue-600 transition-colors">
                            <a href="{{ route('jobs.show', $application->job) }}">
                                {{ $application->job->title }}
                            </a>
                        </h2>
                        <span class="text-xs font-medium px-2 py-1 rounded-full
                            @if($application->status === 'submitted') bg-blue-100 text-blue-800
                            @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-800
                            @elseif($application->status === 'interview_scheduled') bg-purple-100 text-purple-800
                            @elseif($application->status === 'offer_extended') bg-green-100 text-green-800
                            @elseif($application->status === 'hired') bg-emerald-100 text-emerald-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ str_replace('_', ' ', $application->status) }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 mb-2">
                        <span><i class="fas fa-building mr-1"></i> {{ $application->job->employer->name }}</span>
                        <span class="mx-2">•</span>
                        <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $application->job->location }}</span>
                    </div>

                    <div class="text-sm">
                        Applied {{ $application->created_at->diffForHumans() }}
                        • Expected salary: {{ $application->job->salary_currency }} {{ number_format($application->expected_salary) }}
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('job.application.show', $application) }}"
                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
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

    @if($applications->hasPages())
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    @endif
</x-layout>
