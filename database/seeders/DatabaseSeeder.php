<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $tech = Category::factory()->create(['name' => 'Technology']);
        $home = Category::factory()->create(['name' => 'Home & Living']);
        $fashion = Category::factory()->create(['name' => 'Fashion']);

        $phones = Subcategory::factory()->create(['category_id' => $catId = $tech->id, 'name' => 'Smartphones']);
        $laptops = Subcategory::factory()->create(['category_id' => $catId, 'name' => 'Laptops']);
        $kitchen = Subcategory::factory()->create(['category_id' => $home->id, 'name' => 'Kitchen Appliances']);
        $clothing = Subcategory::factory()->create(['category_id' => $fashion->id, 'name' => 'Clothing']);

        Product::factory()->count(5)->create([
            'category_id' => $tech->id,
            'subcategory_id' => $phones->id,
        ]);
        Product::factory()->count(4)->create([
            'category_id' => $tech->id,
            'subcategory_id' => $laptops->id,
        ]);
        Product::factory()->count(3)->create([
            'category_id' => $home->id,
            'subcategory_id' => $kitchen->id,
        ]);
        Product::factory()->count(4)->create([
            'category_id' => $fashion->id,
            'subcategory_id' => $clothing->id,
        ]);
    }
}
