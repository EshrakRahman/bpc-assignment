<?php

namespace App\Livewire\Forms\Admin;

use App\Models\Category;
use Livewire\Form;

class CategoryForm extends Form
{
    public string $name = '';

    public ?string $editingCategoryId = null;

    /**
     * Get validation rules.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $uniqueRule = 'unique:categories,name';
        if ($this->editingCategoryId) {
            $uniqueRule .= ','.$this->editingCategoryId.',id';
        }

        return [
            'name' => ['required', $uniqueRule],
        ];
    }

    /**
     * Load an existing category's properties.
     */
    public function setCategory(Category $category): void
    {
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
    }
}
