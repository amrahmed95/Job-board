<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Jobs', 'url' => route('jobs.index')],
        ['label' => $job->title, 'url' => route('jobs.show', $job)],
        ['label' => 'Apply', 'url' => '#'],
    ]" class="mb-4" />

    <x-card class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Apply for: {{ $job->title }}</h2>
        <div class="mb-4">
            <h3 class="text-lg font-medium">Job Details</h3>
            <div class="mt-2 space-y-2">
                <p><span class="font-medium">Company:</span> {{ $job->employer->name }}</p>
                <p><span class="font-medium">Location:</span> {{ $job->city }}, {{ $job->country }}</p>
                <p><span class="font-medium">Salary:</span> {{ $job->salary_currency }} {{ number_format($job->salary) }} / {{ $job->salary_period }}</p>
            </div>
        </div>
    </x-card>

    <x-card>
        <form method="POST" action="{{ route('job.application.store', $job) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <x-text-input
                    name="expected_salary"
                    label="Expected Salary ({{ $job->salary_currency }})"
                    type="number"
                    placeholder="e.g. 50000"
                    :required="true"
                />
            </div>

            <div class="mb-4">
                <label for="resume" class="block text-sm font-medium text-gray-700 mb-1">
                    Resume <span class="text-red-500">*</span>
                </label>
                <input type="file" id="resume" name="resume"
                    class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100"
                    accept=".pdf,.doc,.docx" required>
                @error('resume')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-1">
                    Cover Letter <span class="text-red-500">*</span>
                </label>
                <textarea id="cover_letter" name="cover_letter" rows="8"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    {{ old('cover_letter') }}
                </textarea>
                @error('cover_letter')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Application
                </button>
            </div>
        </form>
    </x-card>
</x-layout>
