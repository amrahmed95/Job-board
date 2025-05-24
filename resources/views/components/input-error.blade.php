@props(['messages', 'class' => ''])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'mt-2 text-sm text-red-600 dark:text-red-400 ' . $class]) }}>
        @foreach ((array) $messages as $message)
            <p class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $message }}
            </p>
        @endforeach
    </div>
@endif
