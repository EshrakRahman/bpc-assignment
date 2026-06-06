<?php

use App\Livewire\Admin\ProductManager;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the product manager page at the named route', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('admin.products'))
        ->assertSuccessful()
        ->assertSeeLivewire(ProductManager::class);
});

it('validates that required fields are present', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(ProductManager::class)
        ->set('name', '')
        ->set('categoryId', '')
        ->set('subcategoryId', '')
        ->set('price', '')
        ->call('save')
        ->assertHasErrors([
            'name' => 'required',
            'categoryId' => 'required',
            'subcategoryId' => 'required',
            'price' => 'required',
        ]);
});

it('validates that subcategory belongs to selected category', function () {
    $categoryA = Category::factory()->create();
    $categoryB = Category::factory()->create();
    $user = User::factory()->create();

    // Subcategory belongs to Category B
    $subcategoryB = Subcategory::factory()->create(['category_id' => $categoryB->id]);

    // Attempt to save product with Category A but Subcategory B
    Livewire::actingAs($user)->test(ProductManager::class)
        ->set('name', 'Test Product')
        ->set('categoryId', $categoryA->id)
        ->set('subcategoryId', $subcategoryB->id)
        ->set('price', 99.99)
        ->call('save')
        ->assertHasErrors(['subcategoryId']);
});

it('can upload product image and create product with unique slug', function () {
    Storage::fake('public');
    expect(Product::count())->toBe(0);

    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
    $image = UploadedFile::fake()->image('product.jpg');
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(ProductManager::class)
        ->set('categoryId', $category->id)
        ->set('subcategoryId', $subcategory->id)
        ->set('name', 'Mechanical Keyboard')
        ->set('description', 'Cool mechanical keyboard')
        ->set('price', 129.99)
        ->set('image', $image)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('name', '') // Reset form
        ->assertSet('categoryId', '')
        ->assertSet('subcategoryId', '')
        ->assertSet('description', '')
        ->assertSet('price', '')
        ->assertSet('image', null);

    expect(Product::count())->toBe(1);

    $product = Product::first();
    expect($product->name)->toBe('Mechanical Keyboard')
        ->and($product->slug)->toBe('mechanical-keyboard')
        ->and($product->price)->toEqual(129.99)
        ->and($product->category_id)->toBe($category->id)
        ->and($product->subcategory_id)->toBe($subcategory->id)
        ->and($product->image_path)->not->toBeNull();

    Storage::disk('public')->assertExists($product->image_path);
});

it('can edit product details', function () {
    Storage::fake('public');
    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'subcategory_id' => $subcategory->id,
        'name' => 'Old Product Name',
        'price' => 10.00,
    ]);
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(ProductManager::class)
        ->call('edit', $product->id)
        ->assertSet('editingProductId', $product->id)
        ->assertSet('name', 'Old Product Name')
        ->set('name', 'New Product Name')
        ->set('price', 15.50)
        ->call('save')
        ->assertHasNoErrors();

    $product->refresh();
    expect($product->name)->toBe('New Product Name')
        ->and($product->price)->toEqual(15.50)
        ->and($product->slug)->toBe('new-product-name');
});

it('can delete product', function () {
    $product = Product::factory()->create();
    $user = User::factory()->create();

    expect(Product::count())->toBe(1);

    Livewire::actingAs($user)->test(ProductManager::class)
        ->call('confirmDelete', $product->id)
        ->assertSet('deletingProductId', $product->id)
        ->call('delete')
        ->assertHasNoErrors();

    expect(Product::count())->toBe(0);
});
