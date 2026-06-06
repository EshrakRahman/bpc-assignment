<?php

namespace App\Livewire\Forms\Admin;

use App\Models\Product;
use Livewire\Form;

class ProductForm extends Form
{
    public string $name = '';

    public string $description = '';

    public string $price = '';

    public string $categoryId = '';

    public string $subcategoryId = '';

    /** @var mixed */
    public $image;

    public ?string $editingProductId = null;

    /**
     * Get validation rules.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
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

    /**
     * Load an existing product's properties.
     */
    public function setProduct(Product $product): void
    {
        $this->editingProductId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->price = (string) $product->price;
        $this->categoryId = $product->category_id;
        $this->subcategoryId = $product->subcategory_id;
    }
}
