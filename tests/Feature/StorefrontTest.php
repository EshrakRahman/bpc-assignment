<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the storefront home page and displays products', function () {
    $category = Category::factory()->create(['name' => 'Tech']);
    $subcategory = Subcategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Keyboards',
    ]);

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'subcategory_id' => $subcategory->id,
        'name' => 'Apex Pro Keyboard',
        'price' => 199.99,
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Apex Pro Keyboard')
        ->assertSee('$199.99')
        ->assertSee('Keyboards')
        ->assertSee('Tech');
});

it('filters products by subcategory slug route model binding', function () {
    $category = Category::factory()->create();

    $subcategoryA = Subcategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Category A Sub',
    ]);
    $subcategoryB = Subcategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Category B Sub',
    ]);

    $productA = Product::factory()->create([
        'category_id' => $category->id,
        'subcategory_id' => $subcategoryA->id,
        'name' => 'Product in Subcategory A',
    ]);
    $productB = Product::factory()->create([
        'category_id' => $category->id,
        'subcategory_id' => $subcategoryB->id,
        'name' => 'Product in Subcategory B',
    ]);

    // Request subcategory A page
    $this->get(route('storefront.subcategory', $subcategoryA->slug))
        ->assertSuccessful()
        ->assertSee('Product in Subcategory A')
        ->assertDontSee('Product in Subcategory B');
});

it('does not trigger N+1 queries on the storefront', function () {
    // Enable lazy loading prevention to throw exceptions if N+1 happens
    // Note: Model::preventLazyLoading(! app()->isProduction()) is enabled,
    // and since testing env is not production, it is active by default.

    $category = Category::factory()->create();
    $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);

    // Create multiple products to trigger N+1 if not eager loaded
    Product::factory()->count(3)->create([
        'category_id' => $category->id,
        'subcategory_id' => $subcategory->id,
    ]);

    // Requesting home should succeed without LazyLoadingViolationException
    $this->get(route('home'))
        ->assertSuccessful();

    // Requesting subcategory filter should succeed without LazyLoadingViolationException
    $this->get(route('storefront.subcategory', $subcategory->slug))
        ->assertSuccessful();
});
