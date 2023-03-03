<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Response;
use Inertia\ResponseFactory;

class CartController extends Controller
{
    public function show(): Response|ResponseFactory
    {
        $cart = json_decode($_COOKIE['cart'] ?? '{}', 1);
        $productsIds = array_keys($cart);
        $cartProducts = Product::query()
            ->select(['id', 'code', 'name', 'slug', 'price'])
            ->find($productsIds)
            ->keyBy('id')
        ;

        $relatedProducts = Product::query()->take(3)->inRandomOrder()->get()->keyBy('id');

        return inertia('Cart/Show', compact('cartProducts', 'relatedProducts'));
    }
}
