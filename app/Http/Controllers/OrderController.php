<?php

namespace App\Http\Controllers;

use App\Classes\PaginateHandler;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class OrderController extends Controller
{
    public function index(Request $request): Response|ResponseFactory
    {
        $query = Order::query();

        if (strlen($search = $request['search'])) {
            $query->where('customer_info', 'ilike', "%$search%");
        }

        $request['perPage'] ??= 50;
        $orders = PaginateHandler::getPaginated($query);

        return inertia('Admin/Orders/Index', compact('orders'));
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('Orders/Create');
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $customerInfo = $request->validated();
        $cart['products'] = $request['cart']['products'];
        $cart['discount'] = $request['cart']['discount'];
        $cart['orderSum'] = $request['cart']['orderSum'];

        Order::query()->create([
            'customer_info' => json_encode($customerInfo),
            'cart' => json_encode($cart),
        ]);

        return back()->with('success', __('flash.successfully_created'));
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return back()->with('success', __('flash.successfully_deleted'));
    }
}
