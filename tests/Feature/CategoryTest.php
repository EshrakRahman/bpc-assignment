<?php

use App\Livewire\Admin\CategoryManager;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the category manager page at the named route', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('admin.categories'))
        ->assertSuccessful()
        ->assertSeeLivewire(CategoryManager::class);
});

it('validates that category name is required and unique', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(CategoryManager::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);

    $existingCategory = Category::factory()->create([
        'name' => 'Electronics',
    ]);

    Livewire::actingAs($user)->test(CategoryManager::class)
        ->set('name', 'Electronics')
        ->call('save')
        ->assertHasErrors(['name' => 'unique']);
});

it('can create a category with a uuid and unique slug', function () {
    expect(Category::count())->toBe(0);

    $user = User::factory()->create();

    Livewire::actingAs($user)->test(CategoryManager::class)
        ->set('name', 'Home & Kitchen')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('name', ''); // Clears input after save

    expect(Category::count())->toBe(1);

    $category = Category::first();
    expect($category->name)->toBe('Home & Kitchen')
        ->and(Str::isUuid($category->id))->toBeTrue()
        ->and($category->slug)->toBe('home-kitchen');
});

it('can edit an existing category name and updates slug', function () {
    $category = Category::factory()->create([
        'name' => 'Books',
    ]);

    $user = User::factory()->create();

    Livewire::actingAs($user)->test(CategoryManager::class)
        ->call('edit', $category->id)
        ->assertSet('editingCategoryId', $category->id)
        ->assertSet('name', 'Books')
        ->set('name', 'Literature Books')
        ->call('save')
        ->assertHasNoErrors();

    $category->refresh();
    expect($category->name)->toBe('Literature Books')
        ->and($category->slug)->toBe('literature-books');
});

it('can delete an existing category', function () {
    $category = Category::factory()->create([
        'name' => 'Gardening',
    ]);

    $user = User::factory()->create();

    expect(Category::count())->toBe(1);

    Livewire::actingAs($user)->test(CategoryManager::class)
        ->call('confirmDelete', $category->id)
        ->assertSet('deletingCategoryId', $category->id)
        ->call('delete')
        ->assertHasNoErrors();

    expect(Category::count())->toBe(0);
});
