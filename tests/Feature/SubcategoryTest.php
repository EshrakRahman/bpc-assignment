<?php

use App\Livewire\Admin\SubcategoryManager;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the subcategory manager page at the named route', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('admin.subcategories'))
        ->assertSuccessful()
        ->assertSeeLivewire(SubcategoryManager::class);
});

it('validates that subcategory name and category are required and unique', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(SubcategoryManager::class)
        ->set('form.name', '')
        ->set('form.categoryId', '')
        ->call('save')
        ->assertHasErrors([
            'form.name' => 'required',
            'form.categoryId' => 'required',
        ]);

    $category = Category::factory()->create();
    $existingSubcategory = Subcategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Mobile Phones',
    ]);

    Livewire::actingAs($user)->test(SubcategoryManager::class)
        ->set('form.name', 'Mobile Phones')
        ->set('form.categoryId', $category->id)
        ->call('save')
        ->assertHasErrors(['form.name' => 'unique']);
});

it('can create a subcategory with a uuid and unique slug', function () {
    expect(Subcategory::count())->toBe(0);

    $category = Category::factory()->create();
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(SubcategoryManager::class)
        ->set('form.categoryId', $category->id)
        ->set('form.name', 'Laptops & Computers')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('form.name', '') // Clears input after save
        ->assertSet('form.categoryId', '');

    expect(Subcategory::count())->toBe(1);

    $subcategory = Subcategory::first();
    expect($subcategory->name)->toBe('Laptops & Computers')
        ->and(Str::isUuid($subcategory->id))->toBeTrue()
        ->and($subcategory->slug)->toBe('laptops-computers')
        ->and($subcategory->category_id)->toBe($category->id);
});

it('can edit an existing subcategory name and updates slug', function () {
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Smartphones',
    ]);
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(SubcategoryManager::class)
        ->call('edit', $subcategory->id)
        ->assertSet('form.editingSubcategoryId', $subcategory->id)
        ->assertSet('form.name', 'Smartphones')
        ->assertSet('form.categoryId', $category->id)
        ->set('form.name', 'Apple iPhone')
        ->call('save')
        ->assertHasNoErrors();

    $subcategory->refresh();
    expect($subcategory->name)->toBe('Apple iPhone')
        ->and($subcategory->slug)->toBe('apple-iphone');
});

it('can delete an existing subcategory', function () {
    $subcategory = Subcategory::factory()->create();
    $user = User::factory()->create();

    expect(Subcategory::count())->toBe(1);

    Livewire::actingAs($user)->test(SubcategoryManager::class)
        ->call('confirmDelete', $subcategory->id)
        ->assertSet('deletingSubcategoryId', $subcategory->id)
        ->call('delete')
        ->assertHasNoErrors();

    expect(Subcategory::count())->toBe(0);
});
