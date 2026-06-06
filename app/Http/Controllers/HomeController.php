<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display the storefront home page with optional subcategory filtering.
     */
    public function index(?Subcategory $subcategory = null): View
    {
        $productsQuery = Product::with(['category', 'subcategory'])->latest();

        if ($subcategory && $subcategory->exists) {
            $productsQuery->where('subcategory_id', $subcategory->id);
        }

        $products = $productsQuery->paginate(12);

        // Eager load subcategories and their products count to prevent N+1 queries in sidebar
        $categories = Category::with(['subcategories' => function ($query) {
            $query->withCount('products');
        }])->orderBy('name')->get();

        return view('home', compact('products', 'categories', 'subcategory'));
    }
}
