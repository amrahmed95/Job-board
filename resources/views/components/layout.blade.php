<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laravel Job Board</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-3xl font-bold text-gray-900 hover:text-blue-600">Laravel Job Board</a>
                <nav class="flex space-x-4 items-center">
                    <a href="{{ route('jobs.index') }}" class="text-gray-700 hover:text-blue-600">Browse Jobs</a>
                    {{-- <a href="{{ route('jobs.create') }}" class="text-gray-700 hover:text-blue-600">Post a Job</a> --}}

                    @auth
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700">Welcome, {{ Auth::user()->name ?? 'Guest' }}</span>
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                            <form method="POST" action="{{ route('auth.destroy') }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-700 hover:text-blue-600">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                        {{-- <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600">Register</a> --}}
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @auth
            <!-- Display user information in the main content area if needed -->
            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                <p class="text-blue-800">Logged in as: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</p>
            </div>
        @endauth

        {{ $slot }}
    </main>

    <footer class="bg-white border-t mt-8">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500">&copy; {{ date('Y') }} Laravel Job Board</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
