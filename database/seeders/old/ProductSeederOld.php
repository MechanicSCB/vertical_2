<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeederOld extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = json_decode(Storage::get('data/search.json'), 1)['offers'];

        $toDb = [];

        foreach ($products as $product){
            $info = json_encode([@$product['labels'], @$product['score'], @$product['snippet'], @$product['categories']]);

            $toDb[] = [
                'id' => $product['id'],
                'code' => $product['offerCode'],
                'category_id' => $product['category']['id'],
                'vendor' => @$product['vendor'],
                'model' => @$product['model'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name'],'-','ru'),
                'url' => $product['url'],
                'price' => $product['price'],
                'old_price' => @$product['old_price'],
                //'description' => @$product['description'],
                //'params' => @$product['params'],
                'info' => $info,
            ];
        }

        foreach (array_chunk($toDb, 1000) as $chunk){
            Product::upsert($chunk, ['id']);
        }
    }
}
