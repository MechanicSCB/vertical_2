<?php

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminNodeController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/test', [\App\Dev\Parser::class,'run']);
Route::get('/reverse', [\Database\Seeders\OrderSeeder::class,'run']);
//Route::get('/factory', [\Database\Factories\OrderFactory::class,'definition']);

Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/catalog', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/catalog/{path}', [CategoryController::class, 'show'])->where('path', '[a-zA-Z0-9/_-]+')->name('categories.show');
//Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
//Route::post('/categories',[CategoryController::class, 'store'])->name('categories.store');
//Route::get('/categories/edit/{category}', [CategoryController::class, 'edit'])->name('categories.edit');


// Products
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
// get products data from api
Route::post('/products_data', [ProductController::class, 'getData'])->name('products.data');

// Order
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders',[OrderController::class, 'store'])->name('orders.store');


// SEARCH
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// ADMIN PANEL
Route::get('/admin', [AdminCategoryController::class, 'index'])->name('admin.index');

// ADMIN CATEGORIES
Route::get('/admin/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
Route::get('/admin/categories/edit/{category}', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
Route::patch('/admin/categories/{category}',[AdminCategoryController::class, 'update'])->name('admin.categories.update');
Route::get('/admin/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
Route::post('/admin/categories',[AdminCategoryController::class, 'store'])->name('admin.categories.store');
Route::delete('/admin/categories/{category}',[AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');

// ADMIN NODES
Route::get('/admin/nodes', [NodeController::class, 'index'])->name('admin.nodes.index');
//Route::get('/admin/nodes/edit/{node}', [AdminNodeController::class, 'edit'])->name('admin.nodes.edit');
//Route::patch('/admin/nodes/{node}',[AdminNodeController::class, 'update'])->name('admin.nodes.update');
//Route::get('/admin/nodes/create', [AdminNodeController::class, 'create'])->name('admin.nodes.create');
//Route::post('/admin/nodes',[AdminNodeController::class, 'store'])->name('admin.nodes.store');
//Route::delete('/admin/nodes/{node}',[AdminNodeController::class, 'destroy'])->name('admin.nodes.destroy');
// TODO: add admin middleware
Route::get('/tree', [NodeController::class, 'index'])->name('nodes.index');
Route::post('/nodes/copy/{target_node}/to/{dest_node}', [NodeController::class, 'copy'])->name('nodes.copy');
Route::post('/nodes/move/{target_node}/to/{dest_node}', [NodeController::class, 'move'])->name('nodes.move');
Route::post('/nodes/reorder/{target_node}/to/{dest_node}', [NodeController::class, 'reorder'])->name('nodes.reorder');
Route::delete('/nodes/{node}', [NodeController::class, 'destroy'])->name('nodes.destroy');

// ADMIN PRODUCTS
Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');
Route::get('/admin/products/edit/{product}', [AdminProductController::class, 'edit'])->name('admin.products.edit');
Route::patch('/admin/products/{product}',[AdminProductController::class, 'update'])->name('admin.products.update');
Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
Route::post('/admin/products',[AdminProductController::class, 'store'])->name('admin.products.store');
Route::delete('/admin/products/{product}',[AdminProductController::class, 'destroy'])->name('admin.products.destroy');

// ADMIN ORDERS
Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::delete('/admin/orders/{order}',[OrderController::class, 'destroy'])->name('admin.orders.destroy');


// JETSTREAM
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
