<x-layout>
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Login']
    ]" />

    <div class="max-w-md mx-auto">
        <x-card class="mb-8">
            <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">Sign In</h1>

            <form action="{{ route('auth.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <x-text-input
                        name="email"
                        label="Email Address"
                        type="email"
                        placeholder="your@email.com"
                        required
                    />
                </div>

                <div>
                    <x-text-input
                        name="password"
                        label="Password"
                        type="password"
                        placeholder="••••••••"
                        required
                    />
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign in
                    </button>
                </div>
            </form>
        </x-card>

        <x-card>
            <div class="text-center text-sm text-gray-600">
                Don't have an account?
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                    Register here
                </a>
            </div>
        </x-card>
    </div>
</x-layout>
