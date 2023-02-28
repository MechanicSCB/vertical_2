<?php

namespace App\Http\Controllers;

use App\Actions\Nodes\GetAncestorsCategoriesFromPath;
use App\Actions\Nodes\GetBreadcrumbsFromUrl;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Response|ResponseFactory
    {
        $nodeUrl = $product->getDefaultNodeUrl();
        $breadcrumbs = (new GetBreadcrumbsFromUrl())->get($nodeUrl);
        $breadcrumbs[] = [
            'title' => $product->name,
            'url' =>  '/products/' . $product->slug,
        ];

        $category = $product->category;

        return inertia("Products/Show", compact('product', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
