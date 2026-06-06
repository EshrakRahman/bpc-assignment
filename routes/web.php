<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\EnsureSecureHeaders;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\ProductManager;
use App\Livewire\Admin\SubcategoryManager;
use Illuminate\Support\Facades\Route;

// Public Storefront Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/subcategories/{subcategory:slug}', [HomeController::class, 'index'])->name('storefront.subcategory');

// Guest Auth Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Auth Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Authenticated Admin Panel Routes
Route::middleware(['auth', EnsureSecureHeaders::class])->group(function () {
    Route::get('/admin/categories', CategoryManager::class)->name('admin.categories');
    Route::get('/admin/subcategories', SubcategoryManager::class)->name('admin.subcategories');
    Route::get('/admin/products', ProductManager::class)->name('admin.products');
});
