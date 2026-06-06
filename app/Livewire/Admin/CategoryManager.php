<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\CategoryForm;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CategoryManager extends Component
{
    public CategoryForm $form;

    public ?string $deletingCategoryId = null;

    public function save(): void
    {
        $this->form->validate();

        if ($this->form->editingCategoryId) {
            $category = Category::findOrFail($this->form->editingCategoryId);
            $category->update([
                'name' => $this->form->name,
            ]);
            $this->dispatch('toast', message: 'Category updated successfully!');
        } else {
            Category::create([
                'name' => $this->form->name,
            ]);
            $this->dispatch('toast', message: 'Category created successfully!');
        }

        $this->form->reset('name', 'editingCategoryId');
    }

    public function edit(string $id): void
    {
        $category = Category::findOrFail($id);
        $this->form->setCategory($category);
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
