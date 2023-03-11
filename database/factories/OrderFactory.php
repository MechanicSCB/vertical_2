<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customerInfo = [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail(),
        ];

        $cart = [
            'discount' => Arr::random([0.95,0.9,0.75]),
            'orderSum' => rand(120,10000),
            'products' => $this->getRandomCartProducts(),
        ];

        $fakeOrder = [
            'customer_info' => json_encode($customerInfo, JSON_UNESCAPED_UNICODE),
            'cart' => json_encode($cart, JSON_UNESCAPED_UNICODE),
            'status' => null,
        ];

        return $fakeOrder;
    }

    private function getRandomCartProducts(): array
    {
        $products = Product::query()
            ->inRandomOrder()
            ->take(rand(1,5))
            ->get(['id','code','name','slug','price'])
            ->each(fn($v) => $v['quantity'] = rand(1,7))
            ->toArray();

        return $products;
    }
}
