<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'E-Commerce Platform' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Header Navigation -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-150 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left Nav: Logo and Storefront -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-xl font-black text-indigo-600 tracking-tight flex items-center gap-2 group">
                        <svg class="w-6 h-6 text-indigo-600 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>E-Store</span>
                    </a>

                    <nav class="hidden md:flex items-center gap-4">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition {{ request()->routeIs('home') ? 'bg-indigo-50/50 text-indigo-700 font-semibold' : '' }}">
                            Shop Home
                        </a>
                    </nav>
                </div>

                <!-- Right Nav: Conditional Auth Links -->
                <div class="flex items-center gap-4">
                    @auth
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            Admin Mode
                        </span>
                        
                        <nav class="flex items-center gap-2">
                            <a href="{{ route('admin.categories') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.categories') ? 'bg-gray-100 text-indigo-600 font-semibold' : '' }}">
                                Categories
                            </a>
                            <a href="{{ route('admin.subcategories') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.subcategories') ? 'bg-gray-100 text-indigo-600 font-semibold' : '' }}">
                                Subcategories
                            </a>
                            <a href="{{ route('admin.products') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.products') ? 'bg-gray-100 text-indigo-600 font-semibold' : '' }}">
                                Products
                            </a>
                        </nav>

                        <div class="h-6 w-px bg-gray-200"></div>

                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-600 hidden sm:inline">{{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 px-3 py-2 rounded-md transition-all hover:bg-red-50">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    @else
                        <nav class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition shadow-sm">
                                Get Started
                            </a>
                        </nav>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-150 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} E-Store. Bangla Puzzle Assignment. All rights reserved.
        </div>
    </footer>

    <!-- Toast Notifications Container -->
    <div x-data="{ toasts: [] }" 
         @toast.window="toasts.push({ id: Date.now(), message: $event.detail.message, type: $event.detail.type || 'success' })"
         x-init="
            @if(session()->has('success'))
                $nextTick(() => { $dispatch('toast', { message: '{{ session('success') }}', type: 'success' }) });
            @endif
            @if(session()->has('error'))
                $nextTick(() => { $dispatch('toast', { message: '{{ session('error') }}', type: 'error' }) });
            @endif
         "
         class="fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full pointer-events-none">
        
        <template x-for="toast in toasts" :key="toast.id">
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => { show = false; setTimeout(() => { toasts = toasts.filter(t => t.id !== toast.id) }, 300) }, 4000)"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-xl bg-white border shadow-lg ring-1 ring-black/5"
                 :class="toast.type === 'error' ? 'border-red-150' : (toast.type === 'warning' ? 'border-amber-150' : 'border-emerald-150')">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <template x-if="toast.type === 'success'">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        <template x-if="toast.type === 'info' || toast.type === 'warning'">
                            <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-semibold text-gray-900" x-text="toast.message"></p>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button @click="show = false; setTimeout(() => { toasts = toasts.filter(t => t.id !== toast.id) }, 300)" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

</body>
</html>
