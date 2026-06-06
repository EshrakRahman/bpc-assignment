<?php

namespace App\Livewire\Admin;

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

    public string $name = '';

    public string $description = '';

    public string $price = '';

    public string $categoryId = '';

    public string $subcategoryId = '';

    /** @var mixed */
    public $image;

    public ?string $editingProductId = null;

    public ?string $deletingProductId = null;

    /**
     * Get validation rules.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        $uniqueRule = 'unique:products,name';
        if ($this->editingProductId) {
            $uniqueRule .= ','.$this->editingProductId.',id';
        }

        return [
            'categoryId' => ['required', 'exists:categories,id'],
            'subcategoryId' => ['required', 'exists:subcategories,id'],
            'name' => ['required', $uniqueRule],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        // Custom validation: verify subcategory belongs to category
        $subcategory = Subcategory::find($this->subcategoryId);
        if ($subcategory && $subcategory->category_id !== $this->categoryId) {
            $this->addError('subcategoryId', 'The selected subcategory does not belong to the selected category.');

            return;
        }

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        if ($this->editingProductId) {
            $product = Product::findOrFail($this->editingProductId);

            $data = [
                'category_id' => $this->categoryId,
                'subcategory_id' => $this->subcategoryId,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
            ];

            if ($imagePath) {
                // Delete old image if it exists
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $data['image_path'] = $imagePath;
            }

            $product->update($data);
            $this->editingProductId = null;
            $this->dispatch('toast', message: 'Product updated successfully!');
        } else {
            Product::create([
                'category_id' => $this->categoryId,
                'subcategory_id' => $this->subcategoryId,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'image_path' => $imagePath,
            ]);
            $this->dispatch('toast', message: 'Product created successfully!');
        }

        $this->reset('name', 'description', 'price', 'categoryId', 'subcategoryId', 'image', 'editingProductId');
    }

    public function edit(string $id): void
    {
        $product = Product::findOrFail($id);
        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->price = (string) $product->price;
        $this->categoryId = $product->category_id;
        $this->subcategoryId = $product->subcategory_id;
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
        $filteredSubcategories = $this->categoryId
            ? Subcategory::where('category_id', $this->categoryId)->orderBy('name')->get()
            : collect();

        return view('livewire.admin.product-manager', [
            'products' => Product::with(['category', 'subcategory'])->latest()->get(),
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => $filteredSubcategories,
        ])->layout('components.layouts.app');
    }
}
