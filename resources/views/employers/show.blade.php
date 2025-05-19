<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Employers', 'url' => '#'],
        ['label' => $employer->name, 'url' => route('employers.show', $employer)]
    ]" />

    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-shrink-0">
                <img src="{{ $employer->logo_url }}" alt="{{ $employer->name }} logo"
                     class="h-32 w-32 rounded-full object-cover border border-gray-200">
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $employer->name }}</h1>
                        @if($employer->category)
                            <span class="inline-block mt-1 px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                {{ $employer->category->name }}
                            </span>
                        @endif
                    </div>
                    @if($employer->website)
                        <a href="{{ $employer->website }}" target="_blank"
                           class="text-blue-600 hover:text-blue-800 inline-flex items-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                            </svg>
                            {{ $employer->website_host }}
                        </a>
                    @endif
                </div>

                @if($employer->description)
                    <div class="mt-4 prose max-w-none">
                        {!! nl2br(e($employer->description)) !!}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Jobs at {{ $employer->name }}</h2>
        <p class="text-gray-600">Browse all available positions</p>
    </div>

    <x-job-filter :categories="$categories" :selectedFilters="request()->all()" />

    <div class="space-y-4 mt-6">
        @forelse($jobs as $job)
            <x-job-card :job="$job" />
        @empty
            <div class="text-center py-8">
                <p class="text-gray-500">This employer has no job listings yet.</p>
            </div>
        @endforelse
    </div>

    @if($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->withQueryString()->links() }}
        </div>
    @endif
</x-layout>
