<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = json_decode(file_get_contents(database_path('seeders/src/products.json')), 1);

        Product::query()->truncate();

        foreach (array_chunk($products, 1000) as $chunk) {
            Product::upsert($chunk, ['id']);
        }

        $maxId = DB::table('products')->max('id') + 1;
        DB::statement("ALTER SEQUENCE products_id_seq RESTART WITH $maxId;");
    }

    public function reverse()
    {
        $keys = Schema::getColumnListing('products');
        $keys = array_filter($keys, fn($v) => ! in_array($v, ['created_at', 'updated_at']));

        $products = Product::query()
            ->toBase()
            ->get($keys);

        file_put_contents(database_path('seeders/src/products.json'), json_encode($products, JSON_UNESCAPED_UNICODE));
    }
}
