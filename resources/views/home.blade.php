<x-layouts.app>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-150 shadow-sm sticky top-24">
                <div class="flex items-center gap-2 mb-6 pb-3 border-b border-gray-100">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                    </svg>
                    <h2 class="text-sm font-black text-gray-800 uppercase tracking-wider">Browse Store</h2>
                </div>
                
                <div class="space-y-6">
                    <!-- Link to reset filter -->
                    <a href="{{ route('home') }}" class="group flex items-center justify-between text-sm font-bold p-2.5 rounded-xl transition-all {{ !$subcategory ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                            </svg>
                            <span>All Products</span>
                        </span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 group-hover:bg-indigo-100 group-hover:text-indigo-700 transition-colors">
                            {{ \App\Models\Product::count() }}
                        </span>
                    </a>

                    @foreach($categories as $cat)
                        <div class="space-y-2.5">
                            <span class="block text-xs font-black text-gray-400 uppercase tracking-widest">{{ $cat->name }}</span>
                            @if($cat->subcategories->isNotEmpty())
                                <ul class="space-y-1 pl-1">
                                    @foreach($cat->subcategories as $sub)
                                        <li>
                                            <a href="{{ route('storefront.subcategory', $sub->slug) }}" 
                                               class="group flex items-center justify-between text-sm font-medium p-2 rounded-lg transition-all {{ ($subcategory && $subcategory->id === $sub->id) ? 'bg-indigo-50/50 text-indigo-700 font-bold border-l-2 border-indigo-500 pl-3' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600 pl-2 hover:pl-3' }}">
                                                <span>{{ $sub->name }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                                    {{ $sub->products_count }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="block text-xs text-gray-400 italic pl-2">No subcategories</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Main Product Grid -->
        <div class="lg:col-span-3 space-y-8">
            @if($subcategory)
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 border border-indigo-500/20 rounded-2xl p-6 shadow-md flex justify-between items-center text-white relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Store Filter</span>
                        <h1 class="text-3xl font-black mt-1">{{ $subcategory->name }}</h1>
                    </div>
                    <a href="{{ route('home') }}" class="relative z-10 bg-white/10 hover:bg-white/20 text-white border border-white/20 text-sm font-semibold px-4 py-2 rounded-xl transition flex items-center gap-1.5 backdrop-blur-sm shadow-sm">
                        Clear Filter
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            @endif

            @if($products->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="group bg-white rounded-2xl border border-gray-150 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                            <!-- Product Image Container -->
                            <div class="aspect-video bg-gradient-to-tr from-indigo-50/50 to-emerald-50/50 border-b border-gray-100 flex items-center justify-center relative overflow-hidden">
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-10 h-10 text-gray-300 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
                                        </svg>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">No Image Available</span>
                                    </div>
                                @endif
                                
                                <span class="absolute top-3 right-3 text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-full bg-white/90 text-indigo-700 shadow-sm border border-indigo-100/50 backdrop-blur-sm">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div class="space-y-2">
                                    <span class="text-[10px] text-emerald-600 font-extrabold tracking-widest uppercase">{{ $product->subcategory->name }}</span>
                                    <h3 class="text-base font-bold text-gray-800 group-hover:text-indigo-600 line-clamp-1 transition-colors" title="{{ $product->name }}">{{ $product->name }}</h3>
                                    @if($product->description)
                                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                                    @endif
                                </div>
                                
                                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-50">
                                    <span class="text-lg font-black text-gray-900">${{ number_format($product->price, 2) }}</span>
                                    <button class="bg-indigo-600 hover:bg-indigo-700 hover:shadow-md hover:shadow-indigo-100 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-sm">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-150 p-16 text-center shadow-sm">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v4.5A2.25 2.25 0 002.25 13.5z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No products found</h3>
                    <p class="text-sm text-gray-500">There are no products listed here yet.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
