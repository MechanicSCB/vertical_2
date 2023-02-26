<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = json_decode(Storage::get('data/products.json'), 1);

        Product::query()->truncate();

        foreach (array_chunk($products, 1000) as $chunk){
            Product::upsert($chunk, ['id']);
        }
    }
}
