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
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl md:text-3xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                    Laravel Job Board
                </a>

                <nav class="flex items-center space-x-4 md:space-x-6 mt-4 md:mt-0">
                    <div class="flex flex-wrap justify-center gap-3 md:gap-4">
                        <a href="{{ route('jobs.index') }}"
                           class="flex items-center text-gray-700 hover:text-blue-600 transition-colors px-2 py-1 rounded hover:bg-blue-50">
                            <i class="fas fa-search mr-1 text-sm"></i> Browse Jobs
                        </a>
                        <a href="{{ route('employers.index') }}"
                           class="flex items-center text-gray-700 hover:text-blue-600 transition-colors px-2 py-1 rounded hover:bg-blue-50">
                            <i class="fas fa-building mr-1 text-sm"></i> Employers
                        </a>

                        @auth
                            @if(auth()->user()->isJobSeeker())
                                <a href="{{ route('my-job-applications.index') }}"
                                   class="flex items-center text-gray-700 hover:text-blue-600 transition-colors px-2 py-1 rounded hover:bg-blue-50">
                                    <i class="fas fa-file-alt mr-1 text-sm"></i> My Applications
                                </a>
                            @elseif(auth()->user()->isEmployer())
                                <a href="{{ route('employer.dashboard') }}"
                                   class="flex items-center text-gray-700 hover:text-blue-600 transition-colors px-2 py-1 rounded hover:bg-blue-50">
                                    <i class="fas fa-tachometer-alt mr-1 text-sm"></i> Dashboard
                                </a>
                            @endif
                        @endauth
                    </div>

                    @auth
                        <div x-data="{ open: false }" class="relative ml-2">
                            <!-- Trigger Button -->
                            <button @click="open = !open"
                                    class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 px-3 py-1 rounded-md hover:bg-blue-50">
                                <span class="font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'transform rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute right-0 mt-1 w-48 bg-white rounded-md shadow-lg py-1 z-10 border border-gray-100"
                                style="display: none;">
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('login') }}"
                               class="text-gray-700 hover:text-blue-600 px-3 py-1 rounded-md hover:bg-blue-50 transition-colors">
                                Login
                            </a>
                            <div class="hidden md:block text-sm text-gray-500">|</div>
                            <a href="{{ route('employer.register.create') }}"
                               class="text-sm bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition-colors">
                                Employer Registration
                            </a>
                        </div>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @auth
            <!-- Role-specific dashboard information -->
            <div class="mb-6 p-4 rounded-lg shadow-sm
                @if(auth()->user()->isJobSeeker()) bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200
                @else bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 @endif">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex items-center">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white p-2 rounded-full shadow-sm">
                                <i class="text-lg @if(auth()->user()->isJobSeeker()) text-blue-500 fa fa-user-tie @else text-purple-500 fa fa-building @endif"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        @if(auth()->user()->isJobSeeker()) bg-blue-100 text-blue-800 @else bg-purple-100 text-purple-800 @endif">
                                        <i class="fas fa-circle text-xs mr-1 @if(auth()->user()->isJobSeeker()) text-blue-500 @else text-purple-500 @endif"></i>
                                        @if(auth()->user()->isJobSeeker())
                                            Job Seeker
                                        @else
                                            Employer
                                        @endif
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1 flex items-center">
                                    <i class="fas fa-envelope mr-1 text-gray-400"></i>
                                    {{ Auth::user()->email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if(auth()->user()->isJobSeeker())
                            @php $applicationCount = auth()->user()->jobApplications()->count(); @endphp
                            <span class="bg-white px-3 py-1 rounded-full text-xs font-medium shadow-sm
                                @if($applicationCount > 0) text-blue-800 bg-blue-100 @else text-gray-500 bg-gray-100 @endif">
                                <i class="fas fa-file-alt mr-1"></i>
                                {{ $applicationCount }} {{ Str::plural('application', $applicationCount) }}
                            </span>
                        @else
                            @php $jobCount = auth()->user()->employer->jobs()->count(); @endphp
                            <span class="bg-white px-3 py-1 rounded-full text-xs font-medium shadow-sm
                                @if($jobCount > 0) text-purple-800 bg-purple-100 @else text-gray-500 bg-gray-100 @endif">
                                <i class="fas fa-briefcase mr-1"></i>
                                {{ $jobCount }} {{ Str::plural('job', $jobCount) }} posted
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endauth

        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center shadow-sm">
                <i class="fas fa-check-circle mr-3 text-green-500"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center shadow-sm">
                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i>
                    <strong>Whoops!</strong> There were some problems with your input.
                </div>
                <ul class="mt-2 pl-8 space-y-1 list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="bg-white border-t mt-12">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <p class="text-center md:text-left text-gray-500">
                    &copy; {{ date('Y') }} Laravel Job Board. All rights reserved.
                </p>
                <div class="flex justify-center md:justify-end space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
