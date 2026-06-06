<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CategoryManager extends Component
{
    public string $name = '';

    public ?string $editingCategoryId = null;

    public ?string $deletingCategoryId = null;

    /**
     * Get validation rules.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        $uniqueRule = 'unique:categories,name';
        if ($this->editingCategoryId) {
            $uniqueRule .= ','.$this->editingCategoryId.',id';
        }

        return [
            'name' => ['required', $uniqueRule],
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingCategoryId) {
            $category = Category::findOrFail($this->editingCategoryId);
            $category->update([
                'name' => $this->name,
            ]);
            $this->editingCategoryId = null;
            $this->dispatch('toast', message: 'Category updated successfully!');
        } else {
            Category::create([
                'name' => $this->name,
            ]);
            $this->dispatch('toast', message: 'Category created successfully!');
        }

        $this->reset('name', 'editingCategoryId');
    }

    public function edit(string $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
    }

    public function confirmDelete(string $id): void
    {
        $this->deletingCategoryId = $id;
    }

    public function delete(): void
    {
        if ($this->deletingCategoryId) {
            $category = Category::findOrFail($this->deletingCategoryId);
            $category->delete();
            $this->deletingCategoryId = null;
            $this->dispatch('toast', message: 'Category deleted successfully!');
        }
    }

    public function render(): View
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::latest()->get(),
        ])->layout('components.layouts.app');
    }
}
