<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NodeController;
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

Route::get('/test', [\App\Dev\Parser::class,'run']);
//Route::get('/seed', [\Database\Seeders\ProductSeeder::class,'run']);

Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/catalog', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/catalog/{path}', [CategoryController::class, 'show'])->where('path', '[a-zA-Z0-9/_-]+')->name('categories.show');
//Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
//Route::post('/categories',[CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/edit/{category}', [CategoryController::class, 'edit'])->name('categories.edit');
//Route::patch('/categories/{category}',[CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.destroy');

Route::get('/tree', [NodeController::class, 'index'])->name('nodes.index');
Route::post('/nodes/copy/{target_node}/to/{dest_node}', [NodeController::class, 'copy'])->name('nodes.copy');
Route::post('/nodes/move/{target_node}/to/{dest_node}', [NodeController::class, 'move'])->name('nodes.move');
Route::delete('/nodes/{node}', [NodeController::class, 'destroy'])->name('nodes.destroy');


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
