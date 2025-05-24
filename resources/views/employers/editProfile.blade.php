<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Employers', 'url' => route('employers.index')],
        ['label' => $employer->name, 'url' => route('employers.show', $employer)],
        ['label' => 'Edit Profile', 'url' => '#']
    ]" class="mb-4" />

    <x-card class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Edit Employer Profile</h1>

        <form method="POST" action="{{ route('employers.update', $employer) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Company Name -->
                <div>
                    <x-input-label for="name" :value="__('Company Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                 value="{{ old('name', $employer->name) }}" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Website -->
                <div>
                    <x-input-label for="website" :value="__('Website (optional)')" />
                    <x-text-input id="website" name="website" type="url" class="mt-1 block w-full"
                                 value="{{ old('website', $employer->website) }}" />
                    <x-input-error :messages="$errors->get('website')" class="mt-2" />
                </div>

                <!-- Category -->
                <div>
                    <x-input-label for="category_id" :value="__('Industry Category')" />
                    <select id="category_id" name="category_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $employer->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                <!-- Logo -->
                <div>
                    <x-input-label for="logo" :value="__('Company Logo')" />
                    @if($employer->logo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$employer->logo) }}"
                                 alt="Current logo"
                                 class="h-16 w-16 rounded-full object-cover">
                        </div>
                    @endif
                    <input id="logo" name="logo" type="file" class="mt-1 block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-md file:border-0
                          file:text-sm file:font-semibold
                          file:bg-blue-50 file:text-blue-700
                          hover:file:bg-blue-100">
                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" onclick="confirmDelete()"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Delete Profile
                </button>

                <div class="space-x-2">
                    <a href="{{ route('employers.show', $employer) }}"
                       class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <form id="deleteForm" method="POST" action="{{ route('employers.destroy', $employer) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <script>
            function confirmDelete() {
                if (confirm('Are you sure you want to delete your employer profile? This action cannot be undone.')) {
                    document.getElementById('deleteForm').submit();
                }
            }
        </script>
    </x-card>
</x-layout>
