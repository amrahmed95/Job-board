@props(['name', 'label', 'options', 'selected' => null, 'class' => ''])

<div class="{{ $class }}">
    <h3 class="font-medium text-gray-900 mb-2">{{ $label }}</h3>
    <div class="space-y-2">
        @foreach($options as $value => $optionLabel)
            <div class="flex items-center">
                <input
                    type="radio"
                    id="{{ $name }}_{{ $value }}"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    {{ $selected === $value ? 'checked' : '' }}
                    class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <label for="{{ $name }}_{{ $value }}" class="ml-2 text-sm text-gray-700">
                    {{ $optionLabel }}
                </label>
            </div>
        @endforeach
    </div>
</div>
