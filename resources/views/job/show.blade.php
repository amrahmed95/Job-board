<x-layout>

    <x-breadcrumbs :links="[
        ['label' => 'Jobs', 'url' => route('jobs.index')],
        ['label' => Str::limit($job->title, 30)]
    ]" />

    <div class="max-w-4xl mx-auto">
        <!-- Back button -->
        <div class="mb-6">
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to all jobs
            </a>
        </div>

        <!-- Main job card -->
        <x-card class="mb-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
                <div class="flex-1">

                    <!-- Employer Info -->
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ $job->employer->logo_url }}" alt="{{ $job->employer->name }}"
                             class="h-12 w-12 rounded-full object-cover border border-gray-200">
                        <div>
                            <h2 class="font-medium text-gray-900">{{ $job->employer->name }}</h2>
                            @if($job->employer->website)
                                <a href="{{ $job->employer->website }}" target="_blank"
                                   class="text-sm text-blue-600 hover:text-blue-800 inline-flex items-center">
                                    <i class="fas fa-globe mr-1"></i>
                                    {{ parse_url($job->employer->website, PHP_URL_HOST) }}
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- line with html --}}
                    <div class="border-t border-gray-300 mb-4"></div>

                    <!-- Job Title and Details -->
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>

                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $job->city }}, {{ $job->country }}</span>
                        <span><i class="far fa-clock mr-1"></i> Posted {{ $job->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                            <i class="fas fa-briefcase mr-1"></i> {{ ucfirst($job->employment_type) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-50 text-green-700">
                            @if($job->work_location_type === 'remote')
                                <i class="fas fa-laptop-house mr-1"></i> Remote
                            @elseif($job->work_location_type === 'hybrid')
                                <i class="fas fa-random mr-1"></i> Hybrid
                            @else
                                <i class="fas fa-building mr-1"></i> On-site
                            @endif
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-50 text-purple-700">
                            <i class="fas fa-chart-line mr-1"></i> {{ ucfirst(str_replace('-', ' ', $job->experience)) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700">
                            <i class="fas fa-tag mr-1"></i> {{ $job->category->name }}
                        </span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-gray-900">
                                    {{ $job->salary_currency }} {{ number_format($job->salary) }}
                                </span>
                                <span class="text-gray-600">/ {{ $job->salary_period }}</span>
                            </div>
                            @if($job->salary)
                                <div class="text-sm text-gray-500">
                                    @php
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
                                        ≈ {{ $job->salary_currency }}{{ number_format(round($monthly)) }}/month
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:text-right">
                    <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="far fa-paper-plane mr-2"></i> Apply Now
                    </button>
                    <button class="mt-2 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="far fa-bookmark mr-2"></i> Save Job
                    </button>
                </div>
            </div>

            <div class="prose max-w-none mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Job Description</h3>
                {!! nl2br(e($job->description)) !!}
            </div>
        </x-card>

        <!-- Additional job details section -->
        <x-card class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Job Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Salary</h4>
                    <p>{{ $job->salary_currency }}{{ number_format($job->salary) }} per {{ $job->salary_period }}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Employment Type</h4>
                    <p>{{ ucfirst($job->employment_type) }}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Location</h4>
                    <p>{{ $job->work_location_type === 'remote' ? 'Remote' : ($job->work_location_type === 'hybrid' ? 'Hybrid' : 'On-site') }} @if($job->work_location_type !== 'remote')({{ $job->city }}, {{ $job->country }})@endif</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Experience Level</h4>
                    <p>{{ ucfirst(str_replace('-', ' ', $job->experience)) }}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Category</h4>
                    <p>{{ $job->category->name }}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Posted</h4>
                    <p>{{ $job->created_at->format('M j, Y') }} ({{ $job->created_at->diffForHumans() }})</p>
                </div>
            </div>
        </x-card>

        <!-- Apply section -->
        <x-card>
            <h3 class="text-lg font-medium text-gray-900 mb-4">How to Apply</h3>
            <p class="mb-4">Interested in this position? Please submit your application through the button below.</p>
            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="far fa-paper-plane mr-2"></i> Apply Now
            </button>
        </x-card>

        <!-- More jobs from this employer -->
        @if($job->employer->jobs->count() > 1)
            <div class="mt-12">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    More jobs from {{ $job->employer->name }}
                </h2>

                <div class="space-y-4">
                    @foreach($job->employer->jobs->where('id', '!=', $job->id) as $otherJob)
                        <a href="{{ route('jobs.show', $otherJob) }}" class="block hover:no-underline">
                            <x-card class="hover:border-blue-500 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $otherJob->title }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-sm text-gray-500">
                                                {{ $otherJob->employment_type_name }}
                                            </span>
                                            <span class="text-sm text-gray-500">•</span>
                                            <span class="text-sm text-gray-500">
                                                {{ $otherJob->work_location_type_name }}
                                            </span>
                                            <span class="text-sm text-gray-500">•</span>
                                            <span class="text-sm text-gray-500">
                                                {{ $otherJob->city }}, {{ $otherJob->country }}
                                            </span>
                                            <span class="text-sm text-gray-500">•</span>
                                            <span class="text-sm text-gray-500">
                                                {{ $otherJob->salary_currency }} {{ number_format($otherJob->salary) }}
                                                / {{ $otherJob->salary_period }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $otherJob->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </x-card>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layout>
