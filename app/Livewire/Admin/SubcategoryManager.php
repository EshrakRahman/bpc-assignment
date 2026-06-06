<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\SubcategoryForm;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SubcategoryManager extends Component
{
    public SubcategoryForm $form;

    public ?string $deletingSubcategoryId = null;

    public function save(): void
    {
        $this->form->validate();

        if ($this->form->editingSubcategoryId) {
            $subcategory = Subcategory::findOrFail($this->form->editingSubcategoryId);
            $subcategory->update([
                'category_id' => $this->form->categoryId,
                'name' => $this->form->name,
            ]);
            $this->dispatch('toast', message: 'Subcategory updated successfully!');
        } else {
            Subcategory::create([
                'category_id' => $this->form->categoryId,
                'name' => $this->form->name,
            ]);
            $this->dispatch('toast', message: 'Subcategory created successfully!');
        }

        $this->form->reset('name', 'categoryId', 'editingSubcategoryId');
    }

    public function edit(string $id): void
    {
        $subcategory = Subcategory::findOrFail($id);
        $this->form->setSubcategory($subcategory);
    }

    public function confirmDelete(string $id): void
    {
        $this->deletingSubcategoryId = $id;
    }

    public function delete(): void
    {
        if ($this->deletingSubcategoryId) {
            $subcategory = Subcategory::findOrFail($this->deletingSubcategoryId);
            $subcategory->delete();
            $this->deletingSubcategoryId = null;
            $this->dispatch('toast', message: 'Subcategory deleted successfully!');
        }
    }

    public function render(): View
    {
        return view('livewire.admin.subcategory-manager', [
            'subcategories' => Subcategory::with('category')->latest()->get(),
            'categories' => Category::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
