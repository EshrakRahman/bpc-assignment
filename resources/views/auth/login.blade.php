<x-layouts.app>
    <div class="flex items-center justify-center min-h-[70vh] px-4">
        <div class="bg-white/80 backdrop-blur-md border border-gray-200/80 rounded-2xl shadow-xl p-8 max-w-md w-full relative overflow-hidden">
            <!-- Decorative gradient blur -->
            <div class="absolute -top-12 -left-12 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-12 -right-12 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl"></div>

            <div class="relative space-y-6">
                <!-- Header -->
                <div class="text-center">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Welcome Back</h1>
                    <p class="text-sm text-gray-500 mt-1">Please sign in to access your admin account</p>
                </div>

                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', 'admin@admin.com') }}" required autofocus
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                            placeholder="you@example.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Password</label>
                        </div>
                        <input type="password" id="password" name="password" value="password" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2">Remember me</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full inline-flex justify-center items-center rounded-lg border border-transparent bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        Sign In
                    </button>
                </form>

                <!-- Footer link -->
                <div class="text-center text-xs text-gray-500">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition">Create account</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
