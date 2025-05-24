<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Employers', 'url' => route('employers.index')],
        ['label' => $employer->name, 'url' => '#']
    ]" class="mb-4" />

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ $employer->name }} Profile</h1>
        @can('update', $employer)
            <a href="{{ route('employers.edit', $employer) }}"
               class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit Profile
            </a>
        @endcan
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-1/3">
            <x-card>
                <div class="flex flex-col items-center text-center">
                    @if($employer->logo)
                        <img src="{{ asset('storage/'.$employer->logo) }}"
                             alt="{{ $employer->name }} logo"
                             class="w-32 h-32 rounded-full object-cover mb-4 border-2 border-gray-200">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                            <span class="text-gray-500 text-xl font-bold">{{ substr($employer->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <h1 class="text-2xl font-bold">{{ $employer->name }}</h1>
                    <p class="text-gray-500 mb-2">{{ $employer->category->name }}</p>

                    @if($employer->website)
                        <a href="{{ $employer->website }}" target="_blank"
                           class="text-blue-600 hover:underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            {{ parse_url($employer->website, PHP_URL_HOST) }}
                        </a>
                    @endif

                    <div class="mt-6 w-full space-y-2">
                        @can('update', $employer)
                            <a href="{{ route('employers.edit', $employer) }}"
                               class="block w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Profile
                            </a>
                        @endcan
                        <a href="{{ route('employer.jobs.create') }}"
                           class="block w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                            </svg>
                            Post New Job
                        </a>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="md:w-2/3">
            <x-card>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Current Job Openings</h2>
                    <span class="text-sm text-gray-500">
                        {{ $jobs->total() }} {{ Str::plural('opening', $jobs->total()) }}
                    </span>
                </div>

                @if($jobs->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">No current job openings.</p>
                        <a href="{{ route('employer.jobs.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                            </svg>
                            Post a Job
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($jobs as $job)
                            <a href="{{ route('jobs.show', $job) }}" class="block hover:bg-gray-50 rounded-lg transition-colors">
                                <x-job-card :job="$job" />
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $jobs->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-layout>
