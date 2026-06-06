<div class="space-y-8">
    <div class="flex justify-between items-center border-b border-gray-150 pb-5">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Subcategories</h1>
            <p class="text-xs text-gray-500 mt-1">Manage subcategories and assign parents.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-150 shadow-sm h-fit space-y-6">
            <div>
                <h2 class="text-base font-bold text-gray-900">
                    {{ $editingSubcategoryId ? 'Edit Subcategory' : 'Create New Subcategory' }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Link subcategory to category.</p>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label for="categoryId" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Parent Category</label>
                    <select id="categoryId" wire:model="categoryId"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all bg-white">
                        <option value="">Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('categoryId')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Subcategory Name</label>
                    <input type="text" id="name" wire:model="name"
                        class="mt-1.5 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all placeholder-gray-400"
                        placeholder="e.g. Laptops">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit"
                        class="inline-flex justify-center items-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        {{ $editingSubcategoryId ? 'Update' : 'Save' }}
                    </button>
                    @if($editingSubcategoryId)
                        <button type="button" wire:click="$set('editingSubcategoryId', null); $set('name', ''); $set('categoryId', '')"
                            class="inline-flex justify-center items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Listing Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden flex flex-col">
            @if($deletingSubcategoryId)
                <div class="bg-red-50 border-b border-red-200 p-4 flex justify-between items-center animate-fade-in">
                    <div class="flex items-center gap-2 text-red-700">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="text-xs font-semibold">Delete subcategory? This will affect products in it.</span>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="delete" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Confirm
                        </button>
                        <button wire:click="$set('deletingSubcategoryId', null)" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold px-3 py-1.5 rounded-lg transition-all shadow-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Slug</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Parent Category</th>
                            <th scope="col" class="relative px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($subcategories as $subcategory)
                            <tr wire:key="{{ $subcategory->id }}" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $subcategory->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">{{ $subcategory->slug }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100/50 text-[10px] font-bold uppercase tracking-wider">
                                        {{ $subcategory->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                    <button wire:click="edit('{{ $subcategory->id }}')" class="text-indigo-600 hover:text-indigo-950 mr-3 transition-colors">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete('{{ $subcategory->id }}')" class="text-red-600 hover:text-red-950 transition-colors">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                    No subcategories defined.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
