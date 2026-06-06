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
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left Nav: Logo and Storefront -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600 tracking-tight flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Storefront</span>
                    </a>

                    <nav class="hidden md:flex items-center gap-4">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                            Shop Home
                        </a>
                    </nav>
                </div>

                <!-- Right Nav: Admin Dashboard Link -->
                <div class="flex items-center gap-4">
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                        Admin Mode
                    </span>
                    <nav class="flex items-center gap-2">
                        <a href="{{ route('admin.categories') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.categories') ? 'bg-gray-100 text-indigo-600' : '' }}">
                            Categories
                        </a>
                        <a href="{{ route('admin.subcategories') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.subcategories') ? 'bg-gray-100 text-indigo-600' : '' }}">
                            Subcategories
                        </a>
                        <a href="{{ route('admin.products') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.products') ? 'bg-gray-100 text-indigo-600' : '' }}">
                            Products
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} E-Commerce Platform. Bangla Puzzle Assignment.
        </div>
    </footer>

</body>
</html>
