<?php

namespace Database\Seeders;

use App\Models\Order;
use Database\Factories\OrderFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$orders = json_decode(file_get_contents(database_path('seeders/src/orders.json')), 1);
        $orders = Order::factory()->count(50)->make()->toArray();

        Order::query()->truncate();

        foreach (array_chunk($orders, 1000) as $chunk) {
            Order::upsert($chunk, ['id']);
        }

        $maxId = DB::table('orders')->max('id') + 1;
        DB::statement("ALTER SEQUENCE orders_id_seq RESTART WITH $maxId;");
    }


    public function reverse()
    {
        $keys = Schema::getColumnListing('orders');
        //$keys = array_filter($keys, fn($v) => ! in_array($v, ['created_at', 'updated_at']));

        $items = DB::table('orders')->get($keys);

        file_put_contents(database_path('seeders/src/orders.json'), json_encode($items, JSON_UNESCAPED_UNICODE));
    }

}
