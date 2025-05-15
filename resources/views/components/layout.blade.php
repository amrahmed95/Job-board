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
                <h1 class="text-3xl font-bold text-gray-900">Laravel Job Board</h1>
                <nav class="flex space-x-4">
                    <a href="#" class="text-gray-700 hover:text-blue-600">Post a Job</a>
                    @auth
                        <a href="#" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    @else
                        <a href="#" class="text-gray-700 hover:text-blue-600">Login</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
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
