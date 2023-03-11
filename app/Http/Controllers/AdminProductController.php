<?php

namespace App\Http\Controllers;

use App\Classes\PaginateHandler;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class AdminProductController extends Controller
{
    public function index(Request $request): Response|ResponseFactory
    {
        $query = Product::query();

        if(strlen($search = $request['search'])){
            $query->where('name', 'ilike',"%$search%"); // add? pr_trgm
            //$query->whereFullText('name', $search); // TODO why slow?
        }

        $query->orderByDesc('updated_at');
        $request['perPage'] ??= 50;
        $products = PaginateHandler::getPaginated($query);

        return inertia('Admin/Products/Index', compact('products','search'));
    }

    public function edit(Product $product): Response|ResponseFactory
    {
        return inertia('Admin/Products/Edit', compact('product'));
    }

    public function update(Product $product, StoreProductRequest $request): RedirectResponse
    {
        $product->update($request->validated());

        return back()->with('success', __('flash.successfully_updated'));
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('Admin/Products/Create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = array_filter($request->validated());

        //df(tmr(@$this->start), $validated);
        $product = Product::query()->create($validated);
        df(tmr(@$this->start), $product);

        return redirect(route('admin.products.edit',$product))->with('success', __('flash.successfully_created'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('success', __('flash.successfully_deleted'));
    }
}
