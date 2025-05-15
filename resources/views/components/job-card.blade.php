@props(['job'])

<x-card>
    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
        <div class="flex-1">
            <div class="mb-2">
                <h2 class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                    {{ $job->title }}
                </h2>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span><i class="fas fa-map-marker-alt"></i> {{ $job->city }}, {{ $job->country }}</span>
                    <span>•</span>
                    <span><i class="far fa-clock"></i> {{ $job->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                <!-- Employment Type -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                    <i class="fas fa-briefcase mr-1"></i> {{ ucfirst($job->employment_type) }}
                </span>

                <!-- Work Location -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                    @if($job->work_location_type === 'remote')
                        <i class="fas fa-laptop-house mr-1"></i> Remote
                    @elseif($job->work_location_type === 'hybrid')
                        <i class="fas fa-random mr-1"></i> Hybrid
                    @else
                        <i class="fas fa-building mr-1"></i> On-site
                    @endif
                </span>

                <!-- Experience Level -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                    <i class="fas fa-chart-line mr-1"></i> {{ ucfirst(str_replace('-', ' ', $job->experience)) }}
                </span>

                <!-- Category - More prominent styling -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                    <i class="fas fa-tag mr-1"></i> {{ $job->category->name }}
                </span>
            </div>
        </div>

        <div class="flex flex-col items-end">
            <div class="text-lg font-semibold text-gray-900">
                <span class="text-base">{{ $job->salary_currency }} </span>{{ number_format($job->salary) }}
                <span class="text-sm text-gray-500">/ {{ $job->salary_period }}</span>
            </div>
            @if($job->salary)
                <div class="mt-1 text-xs text-gray-400">
                    @php
                        // Calculate approximate monthly equivalent for comparison
                        $monthly = match($job->salary_period) {
                            'hour' => $job->salary * 160,
                            'day' => $job->salary * 20,
                            'week' => $job->salary * 4,
                            'month' => $job->salary,
                            'year' => $job->salary / 12,
                            default => null,
                        };
                    @endphp
                    @if($monthly)
                        ≈ {{ $job->salary_currency }} {{ number_format(round($monthly)) }}/month
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-100">
        <p class="text-gray-700 line-clamp-3">{{ $job->description }}</p>
        <div class="mt-3 flex justify-end">
            <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                <i class="far fa-bookmark mr-1"></i> Save
            </button>
        </div>
    </div>
</x-card>
