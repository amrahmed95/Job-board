<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Employer Dashboard', 'url' => route('employer.dashboard')],
        ['label' => 'Edit Job: ' . $job->title, 'url' => '#']
    ]" class="mb-4" />

    <x-card class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Edit Job Posting</h1>

        <form method="POST" action="{{ route('employer.jobs.update', $job) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Job Title -->
                <div class="md:col-span-2">
                    <x-input-label for="title" :value="__('Job Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                value="{{ old('title', $job->title) }}" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <!-- Job Description -->
                <div class="md:col-span-2">
                    <x-input-label for="description" :value="__('Job Description')" />
                    <textarea id="description" name="description" rows="6"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>{{ old('description', $job->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <!-- Salary Information -->
                <div>
                    <x-input-label for="salary" :value="__('Salary')" />
                    <div class="flex rounded-md shadow-sm">
                        <select name="salary_currency"
                                class="px-4 py-2 border border-r-0 border-gray-300 rounded-l-md bg-gray-50 text-gray-500 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="USD" @selected(old('salary_currency', $job->salary_currency) == 'USD')>USD</option>
                            <option value="EUR" @selected(old('salary_currency', $job->salary_currency) == 'EUR')>EUR</option>
                            <option value="GBP" @selected(old('salary_currency', $job->salary_currency) == 'GBP')>GBP</option>
                        </select>
                        <x-text-input id="salary" name="salary" type="number"
                                    class="flex-1 min-w-0 block w-full rounded-none rounded-r-md"
                                    value="{{ old('salary', $job->salary) }}" required />
                    </div>
                    <x-input-error :messages="$errors->get('salary')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="salary_period" :value="__('Salary Period')" />
                    <select id="salary_period" name="salary_period" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($salaryPeriods as $period)
                            <option value="{{ $period }}" @selected(old('salary_period', $job->salary_period) == $period)>
                                {{ ucfirst($period) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('salary_period')" class="mt-2" />
                </div>

                <!-- Job Details -->
                <div>
                    <x-input-label for="employment_type" :value="__('Employment Type')" />
                    <select id="employment_type" name="employment_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($employmentTypes as $type)
                            <option value="{{ $type }}" @selected(old('employment_type', $job->employment_type) == $type)>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('employment_type')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="work_location_type" :value="__('Work Location Type')" />
                    <select id="work_location_type" name="work_location_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($workLocationTypes as $type)
                            <option value="{{ $type }}" @selected(old('work_location_type', $job->work_location_type) == $type)>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('work_location_type')" class="mt-2" />
                </div>

                <!-- Location -->
                <div>
                    <x-input-label for="city" :value="__('City')" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                                value="{{ old('city', $job->city) }}" required />
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="country" :value="__('Country')" />
                    <x-text-input id="country" name="country" type="text" class="mt-1 block w-full"
                                value="{{ old('country', $job->country) }}" required />
                    <x-input-error :messages="$errors->get('country')" class="mt-2" />
                </div>

                <!-- Category and Experience -->
                <div>
                    <x-input-label for="category_id" :value="__('Category')" />
                    <select id="category_id" name="category_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $job->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="experience" :value="__('Experience Level')" />
                    <select id="experience" name="experience" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($experienceLevels as $level)
                            <option value="{{ $level }}" @selected(old('experience', $job->experience) == $level)>
                                {{ ucfirst(str_replace('-', ' ', $level)) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('experience')" class="mt-2" />
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('employer.dashboard') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Job
                </button>
            </div>
        </form>
    </x-card>
</x-layout>
