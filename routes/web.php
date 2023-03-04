<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NodeController;
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

Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/catalog', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/catalog/{path}', [CategoryController::class, 'show'])->where('path', '[a-zA-Z0-9/_-]+')->name('categories.show');
//Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
//Route::post('/categories',[CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/edit/{category}', [CategoryController::class, 'edit'])->name('categories.edit');
//Route::patch('/categories/{category}',[CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.destroy');

// TODO: add admin middleware
Route::get('/tree', [NodeController::class, 'index'])->name('nodes.index');
Route::post('/nodes/copy/{target_node}/to/{dest_node}', [NodeController::class, 'copy'])->name('nodes.copy');
Route::post('/nodes/move/{target_node}/to/{dest_node}', [NodeController::class, 'move'])->name('nodes.move');
Route::post('/nodes/reorder/{target_node}/to/{dest_node}', [NodeController::class, 'reorder'])->name('nodes.reorder');
Route::delete('/nodes/{node}', [NodeController::class, 'destroy'])->name('nodes.destroy');

// Products
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');

Route::post('/products_data', [ProductController::class, 'getData'])->name('products.data');

// SEARCH
Route::get('/search', [SearchController::class, 'index'])->name('search.index');


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
