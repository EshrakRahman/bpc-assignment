<x-layouts.app>
    <div class="flex items-center justify-center min-h-[70vh] px-4">
        <div class="bg-white/80 backdrop-blur-md border border-gray-200/80 rounded-2xl shadow-xl p-8 max-w-md w-full relative overflow-hidden">
            <!-- Decorative gradient blur -->
            <div class="absolute -top-12 -left-12 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-12 -right-12 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl"></div>

            <div class="relative space-y-6">
                <!-- Header -->
                <div class="text-center">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Create Account</h1>
                    <p class="text-sm text-gray-500 mt-1">Join us to manage categories and products</p>
                </div>

                <!-- Form -->
                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                            placeholder="John Doe">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                            placeholder="you@example.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Password</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                            placeholder="••••••••">
                    </div>

                    <button type="submit"
                        class="w-full inline-flex justify-center items-center rounded-lg border border-transparent bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        Create Account
                    </button>
                </form>

                <!-- Footer link -->
                <div class="text-center text-xs text-gray-500">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
