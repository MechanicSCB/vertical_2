<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Response;
use Inertia\ResponseFactory;

class CartController extends Controller
{
    public function show(): Response|ResponseFactory
    {
        $cart = json_decode($_COOKIE['cart'] ?? [], 1);
        $productsIds = array_keys($cart);
        $products = Product::query()
            ->select(['id', 'code', 'name', 'slug', 'price'])
            ->find($productsIds)
            ->each(fn($v) => $v['quantity'] = $cart[$v['id']])
            ->keyBy('id')
        ;

        return inertia('Cart/Show', compact('products'));
    }
}
