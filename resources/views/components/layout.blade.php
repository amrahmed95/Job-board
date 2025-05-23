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

                    @auth
                        @if(auth()->user()->isJobSeeker())
                            <a href="{{ route('my-job-applications.index') }}" class="text-gray-700 hover:text-blue-600">
                                <i class="fas fa-file-alt mr-1"></i> My Applications
                            </a>
                        @else
                            <a href="{{ route('jobs.index') }}" class="text-gray-700 hover:text-blue-600">
                                <i class="fas fa-briefcase mr-1"></i> All Jobs
                            </a>
                        @endif
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
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
            <!-- Role-specific dashboard information -->
            <div class="mb-4 p-4 rounded-lg
                @if(auth()->user()->isJobSeeker()) bg-blue-50 text-blue-800
                @else bg-purple-50 text-purple-800 @endif">
                <div class="flex justify-between items-center">
                    <div>
                        <p>Logged in as:
                            <strong>{{ Auth::user()->name }}</strong>
                            ({{ Auth::user()->email }})
                        </p>
                        <p class="mt-1 text-sm">
                            @if(auth()->user()->isJobSeeker())
                                <i class="fas fa-user-tie mr-1"></i> Job Seeker Account
                            @else
                                <i class="fas fa-building mr-1"></i> Employer Account
                            @endif
                        </p>
                    </div>

                    @if(auth()->user()->isJobSeeker())
                        @php
                            $applicationCount = auth()->user()->jobApplications()->count();
                        @endphp
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-medium
                            @if($applicationCount > 0) text-blue-800 bg-blue-100
                            @else text-gray-500 bg-gray-100 @endif">
                            <i class="fas fa-file-alt mr-1"></i>
                            {{ $applicationCount }} {{ Str::plural('application', $applicationCount) }}
                        </div>
                    @else
                        @php
                            $jobCount = auth()->user()->employer->jobs()->count();
                        @endphp
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-medium
                            @if($jobCount > 0) text-purple-800 bg-purple-100
                            @else text-gray-500 bg-gray-100 @endif">
                            <i class="fas fa-briefcase mr-1"></i>
                            {{ $jobCount }} {{ Str::plural('job', $jobCount) }} posted
                        </div>
                    @endif
                </div>
            </div>
        @endauth

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
