<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Node;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nodes = json_decode(file_get_contents(database_path('seeders/src/nodes.json')), 1);

        Node::query()->truncate();

        foreach (array_chunk($nodes, 1000) as $chunk) {
            Node::upsert($chunk, ['id']);
        }

        $maxId = DB::table('nodes')->max('id') + 1;
        DB::statement("ALTER SEQUENCE nodes_id_seq RESTART WITH $maxId;");
    }

    public function reverse()
    {
        $keys = Schema::getColumnListing('nodes');
        $keys = array_filter($keys, fn($v) => ! in_array($v, ['created_at', 'updated_at','level']));

        $items = DB::table('nodes')->get($keys);

        file_put_contents(database_path('seeders/src/nodes.json'), json_encode($items, JSON_UNESCAPED_UNICODE));
    }
}
