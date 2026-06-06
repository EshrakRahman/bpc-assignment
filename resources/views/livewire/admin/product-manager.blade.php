<div class="space-y-8">
    <div class="flex justify-between items-center border-b border-gray-150 pb-5">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Products</h1>
            <p class="text-xs text-gray-500 mt-1">Manage e-commerce inventory, pricing, and assets.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-150 shadow-sm h-fit space-y-6">
            <div>
                <h2 class="text-base font-bold text-gray-900">
                    {{ $form->editingProductId ? 'Edit Product' : 'Create New Product' }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Define product details.</p>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label for="categoryId" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Category</label>
                    <select id="categoryId" wire:model.live="form.categoryId"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all bg-white">
                        <option value="">Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('form.categoryId')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subcategoryId" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Subcategory</label>
                    <select id="subcategoryId" wire:model="form.subcategoryId"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all bg-white disabled:bg-gray-50 disabled:text-gray-400"
                        {{ empty($form->categoryId) ? 'disabled' : '' }}>
                        <option value="">Select a Subcategory</option>
                        @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                        @endforeach
                    </select>
                    @error('form.subcategoryId')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Product Name</label>
                    <input type="text" id="name" wire:model="form.name"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                        placeholder="e.g. Wireless Mouse">
                    @error('form.name')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Description</label>
                    <textarea id="description" wire:model="form.description" rows="3"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                        placeholder="Provide details about the product..."></textarea>
                    @error('form.description')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Price ($)</label>
                    <input type="number" id="price" wire:model="form.price" step="0.01" min="0"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                        placeholder="e.g. 29.99">
                    @error('form.price')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Product Image</label>
                    <input type="file" id="image" wire:model="form.image"
                        class="mt-1.5 block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 file:hover:bg-indigo-100 transition-colors">
                    
                    <div wire:loading wire:target="form.image" class="mt-1 text-xs text-gray-500">Uploading preview...</div>
                    
                    @error('form.image')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror

                    @if ($form->image)
                        <div class="mt-3">
                            <span class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Preview:</span>
                            <img src="{{ $form->image->temporaryUrl() }}" class="h-24 w-24 object-cover rounded-xl border border-gray-200 shadow-sm">
                        </div>
                    @endif
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit"
                        class="inline-flex justify-center items-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        {{ $form->editingProductId ? 'Update' : 'Save' }}
                    </button>
                    @if($form->editingProductId)
                        <button type="button" wire:click="$set('form.editingProductId', null); $set('form.name', ''); $set('form.categoryId', ''); $set('form.subcategoryId', ''); $set('form.description', ''); $set('form.price', ''); $set('form.image', null)"
                            class="inline-flex justify-center items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Listing Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden flex flex-col">
            @if($deletingProductId)
                <div class="bg-red-50 border-b border-red-200 p-4 flex justify-between items-center animate-fade-in">
                    <div class="flex items-center gap-2 text-red-700">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="text-xs font-semibold">Delete product? This action is permanent.</span>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="delete" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Confirm
                        </button>
                        <button wire:click="$set('deletingProductId', null)" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Image</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Product Info</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Categories</th>
                            <th scope="col" class="relative px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr wire:key="{{ $product->id }}" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" class="h-10 w-10 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <div class="h-10 w-10 bg-gray-100 flex items-center justify-center rounded-lg border border-gray-150 text-[9px] font-bold text-gray-400 uppercase tracking-wider">No Img</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $product->slug }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-y-1">
                                    <div>
                                        <span class="inline-block px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100/50 text-[9px] font-bold uppercase tracking-wider">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="inline-block px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100/50 text-[9px] font-bold uppercase tracking-wider">
                                            {{ $product->subcategory->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                    <button wire:click="edit('{{ $product->id }}')" class="text-indigo-600 hover:text-indigo-950 mr-3 transition-colors">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete('{{ $product->id }}')" class="text-red-600 hover:text-red-950 transition-colors">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                    No products defined.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
