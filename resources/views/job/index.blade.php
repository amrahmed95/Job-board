<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Jobs', 'url' => route('jobs.index')]
    ]" />

    <!-- Filter Section -->
    <x-job-filter :categories="$categories" :selectedFilters="request()->all()" />

    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Job Listings</h2>
            <p class="text-gray-600">
                Showing {{ $jobs->total() }} job{{ $jobs->total() !== 1 ? 's' : '' }}
                @if(request()->anyFilled(['min_salary', 'max_salary', 'experience', 'category', 'city', 'country', 'employment_type']))
                    with active filters
                @endif
            </p>
        </div>

        @if(request()->anyFilled(['min_salary', 'max_salary', 'experience', 'category', 'city', 'country', 'employment_type']))
            <a href="{{ route('jobs.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fas fa-times mr-1"></i> Clear all filters
            </a>
        @endif
    </div>

    <!-- Active Filters Display -->
    @if(request()->anyFilled(['min_salary', 'max_salary', 'experience', 'category', 'city', 'country', 'employment_type']))
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach(request()->except('page') as $key => $value)
                @if($value)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ str_replace('_', ' ', $key) }}:
                        @if($key === 'category')
                            {{ \App\Models\Category::find($value)->name }}
                        @else
                            {{ $value }}
                        @endif
                        <a href="{{ route('jobs.index', Arr::except(request()->query(), [$key, 'page'])) }}"
                           class="ml-1 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
            @endforeach
        </div>
    @endif

    <!-- Job Listings -->
    <div class="space-y-6">
        @forelse($jobs as $job)
            <a href="{{ route('jobs.show', $job) }}" class="block hover:no-underline">
                <x-job-card :job="$job" />
            </a>
        @empty
            <div class="text-center py-12">
                <div class="text-gray-400 text-5xl mb-4">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No jobs found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->anyFilled(['min_salary', 'max_salary', 'experience', 'category', 'city', 'country', 'employment_type']))
                        Try adjusting your search filters
                    @else
                        There are currently no job listings. Check back later!
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->withQueryString()->links() }}
        </div>
    @endif
</x-layout>
