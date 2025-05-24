<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Employer Dashboard', 'url' => '#'],
    ]" class="mb-4" />

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">{{ $employer->name }} Dashboard</h1>

        <div class="flex space-x-3">
            <!-- Edit Profile Button -->
            <a href="{{ route('employers.edit', $employer) }}"
            class="flex items-center px-4 py-2 bg-white border border-blue-600 rounded-md font-medium text-xs text-blue-600 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-150 shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Profile
            </a>

            <!-- Post New Job Button -->
            <a href="{{ route('employer.jobs.create') }}"
            class="flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-150 shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Post New Job
            </a>
        </div>
    </div>

    <x-card>
        <h2 class="text-xl font-semibold mb-4">Your Posted Jobs</h2>

        @if($jobs->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500">You haven't posted any jobs yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($jobs as $job)
                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium">
                                    <a href="{{ route('jobs.show', $job) }}" class="hover:text-blue-600">
                                        {{ $job->title }}
                                    </a>
                                </h3>
                                <div class="text-sm text-gray-500 mt-1">
                                    <span>{{ $job->created_at->diffForHumans() }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $job->applications_count }} applications</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('jobs.show', $job) }}"
                                   class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded hover:bg-blue-200">
                                    View Applications
                                </a>
                                <a href="{{ route('employer.jobs.edit', $job) }}"
                                   class="px-3 py-1 text-sm bg-gray-100 text-gray-800 rounded hover:bg-gray-200">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $jobs->links() }}
            </div>
        @endif
    </x-card>
</x-layout>
