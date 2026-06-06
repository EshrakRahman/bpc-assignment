<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SubcategoryManager extends Component
{
    public string $name = '';

    public string $categoryId = '';

    public ?string $editingSubcategoryId = null;

    public ?string $deletingSubcategoryId = null;

    /**
     * Get validation rules.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
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

    public function save(): void
    {
        $this->validate();

        if ($this->editingSubcategoryId) {
            $subcategory = Subcategory::findOrFail($this->editingSubcategoryId);
            $subcategory->update([
                'category_id' => $this->categoryId,
                'name' => $this->name,
            ]);
            $this->editingSubcategoryId = null;
            $this->dispatch('toast', message: 'Subcategory updated successfully!');
        } else {
            Subcategory::create([
                'category_id' => $this->categoryId,
                'name' => $this->name,
            ]);
            $this->dispatch('toast', message: 'Subcategory created successfully!');
        }

        $this->reset('name', 'categoryId', 'editingSubcategoryId');
    }

    public function edit(string $id): void
    {
        $subcategory = Subcategory::findOrFail($id);
        $this->editingSubcategoryId = $subcategory->id;
        $this->name = $subcategory->name;
        $this->categoryId = $subcategory->category_id;
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
