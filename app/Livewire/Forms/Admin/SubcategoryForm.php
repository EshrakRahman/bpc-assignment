<?php

namespace App\Livewire\Forms\Admin;

use App\Models\Subcategory;
use Livewire\Form;

class SubcategoryForm extends Form
{
    public string $name = '';

    public string $categoryId = '';

    public ?string $editingSubcategoryId = null;

    /**
     * Get validation rules.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $uniqueRule = 'unique:subcategories,name';
        if ($this->editingSubcategoryId) {
            $uniqueRule .= ','.$this->editingSubcategoryId.',id';
        }

        return [
            'categoryId' => ['required', 'exists:categories,id'],
            'name' => ['required', $uniqueRule],
        ];
    }

    /**
     * Load an existing subcategory's properties.
     */
    public function setSubcategory(Subcategory $subcategory): void
    {
        $this->editingSubcategoryId = $subcategory->id;
        $this->name = $subcategory->name;
        $this->categoryId = $subcategory->category_id;
    }
}
