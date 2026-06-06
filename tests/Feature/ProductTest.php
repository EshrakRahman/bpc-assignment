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
        ->set('form.name', '')
        ->set('form.categoryId', '')
        ->set('form.subcategoryId', '')
        ->set('form.price', '')
        ->call('save')
        ->assertHasErrors([
            'form.name' => 'required',
            'form.categoryId' => 'required',
            'form.subcategoryId' => 'required',
            'form.price' => 'required',
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
        ->set('form.name', 'Test Product')
        ->set('form.categoryId', $categoryA->id)
        ->set('form.subcategoryId', $subcategoryB->id)
        ->set('form.price', 99.99)
        ->call('save')
        ->assertHasErrors(['form.subcategoryId']);
});

it('can upload product image and create product with unique slug', function () {
    Storage::fake('public');
    expect(Product::count())->toBe(0);

    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
    $image = UploadedFile::fake()->image('product.jpg');
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(ProductManager::class)
        ->set('form.categoryId', $category->id)
        ->set('form.subcategoryId', $subcategory->id)
        ->set('form.name', 'Mechanical Keyboard')
        ->set('form.description', 'Cool mechanical keyboard')
        ->set('form.price', 129.99)
        ->set('form.image', $image)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('form.name', '') // Reset form
        ->assertSet('form.categoryId', '')
        ->assertSet('form.subcategoryId', '')
        ->assertSet('form.description', '')
        ->assertSet('form.price', '')
        ->assertSet('form.image', null);

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
        ->assertSet('form.editingProductId', $product->id)
        ->assertSet('form.name', 'Old Product Name')
        ->set('form.name', 'New Product Name')
        ->set('form.price', 15.50)
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
