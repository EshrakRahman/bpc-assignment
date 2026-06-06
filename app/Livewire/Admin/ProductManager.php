<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\ProductForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductManager extends Component
{
    use WithFileUploads;

    public ProductForm $form;

    public ?string $deletingProductId = null;

    public function save(): void
    {
        $this->form->validate();

        // Custom validation: verify subcategory belongs to category
        $subcategory = Subcategory::find($this->form->subcategoryId);
        if ($subcategory && $subcategory->category_id !== $this->form->categoryId) {
            $this->addError('form.subcategoryId', 'The selected subcategory does not belong to the selected category.');

            return;
        }

        $imagePath = null;
        if ($this->form->image) {
            $imagePath = $this->form->image->store('products', 'public');
        }

        if ($this->form->editingProductId) {
            $product = Product::findOrFail($this->form->editingProductId);

            $data = [
                'category_id' => $this->form->categoryId,
                'subcategory_id' => $this->form->subcategoryId,
                'name' => $this->form->name,
                'description' => $this->form->description,
                'price' => $this->form->price,
            ];

            if ($imagePath) {
                // Delete old image if it exists
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $data['image_path'] = $imagePath;
            }

            $product->update($data);
            $this->dispatch('toast', message: 'Product updated successfully!');
        } else {
            Product::create([
                'category_id' => $this->form->categoryId,
                'subcategory_id' => $this->form->subcategoryId,
                'name' => $this->form->name,
                'description' => $this->form->description,
                'price' => $this->form->price,
                'image_path' => $imagePath,
            ]);
            $this->dispatch('toast', message: 'Product created successfully!');
        }

        $this->form->reset('name', 'description', 'price', 'categoryId', 'subcategoryId', 'image', 'editingProductId');
    }

    public function edit(string $id): void
    {
        $product = Product::findOrFail($id);
        $this->form->setProduct($product);
    }

    public function confirmDelete(string $id): void
    {
        $this->deletingProductId = $id;
    }

    public function delete(): void
    {
        if ($this->deletingProductId) {
            $product = Product::findOrFail($this->deletingProductId);

            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->delete();
            $this->deletingProductId = null;
            $this->dispatch('toast', message: 'Product deleted successfully!');
        }
    }

    public function render(): View
    {
        // Get subcategories filtered by selected category
        $filteredSubcategories = $this->form->categoryId
            ? Subcategory::where('category_id', $this->form->categoryId)->orderBy('name')->get()
            : collect();

        return view('livewire.admin.product-manager', [
            'products' => Product::with(['category', 'subcategory'])->latest()->get(),
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => $filteredSubcategories,
        ])->layout('components.layouts.app');
    }
}
