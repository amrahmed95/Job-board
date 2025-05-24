<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Employers', 'url' => route('employers.index')]
    ]" class="mb-4" />

    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Employers Directory</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($employers as $employer)
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4">
                    @if($employer->logo)
                        <img src="{{ asset('storage/'.$employer->logo) }}"
                             alt="{{ $employer->name }} logo"
                             class="w-16 h-16 rounded-full object-cover">
                    @endif
                    <div>
                        <h3 class="font-medium text-lg">
                            <a href="{{ route('employers.show', $employer) }}"
                               class="hover:text-blue-600">
                                {{ $employer->name }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500">{{ $employer->category->name }}</p>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-sm text-gray-500">
                        {{ $employer->jobs_count }} {{ Str::plural('job', $employer->jobs_count) }}
                    </span>
                    <a href="{{ route('employers.show', $employer) }}"
                       class="text-sm text-blue-600 hover:underline">
                        View Profile
                    </a>
                </div>
            </x-card>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $employers->links() }}
    </div>
</x-layout>
