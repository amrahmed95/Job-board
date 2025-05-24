
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Application for {{ $job->title }}</h1>
        <a href="{{ route('employer.jobs.applications', $job) }}" class="text-blue-600 hover:text-blue-800">
            &larr; Back to Applications
        </a>
    </div>

    <x-card>
        <h2 class="text-lg font-medium mb-4">Applicant: {{ $application->user->name }}</h2>

        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">Cover Letter</h3>
            <div class="prose max-w-none bg-gray-50 p-4 rounded">
                {!! nl2br(e($application->cover_letter)) !!}
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">Expected Salary</h3>
            <p>{{ number_format($application->expected_salary) }} {{ $job->salary_currency }}</p>
        </div>

        <form method="POST" action="{{ route('employer.jobs.applications.update', [$job, $application]) }}">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Application Status</label>
                <select id="status" name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach(\App\Models\JobApplication::getStatusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ $application->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Feedback for Applicant</label>
                <textarea id="feedback" name="feedback" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('feedback', $application->feedback) }}</textarea>
            </div>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Update Application
            </button>
        </form>
    </x-card>
</div>
@endsection
