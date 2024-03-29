<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = json_decode(file_get_contents(database_path('seeders/src/categories.json')), 1);

        Category::query()->truncate();

        foreach (array_chunk($categories, 1000) as $chunk) {
            Category::upsert($chunk, ['id']);
        }

        $maxId = DB::table('categories')->max('id') + 1;
        DB::statement("ALTER SEQUENCE categories_id_seq RESTART WITH $maxId;");
    }

    public function reverse()
    {
        $keys = Schema::getColumnListing('categories');
        $keys = array_filter($keys, fn($v) => ! in_array($v, ['created_at', 'updated_at']));

        $categories = Category::query()
            ->orderBy('cat_level')
            ->toBase()
            ->get($keys);

        file_put_contents(database_path('seeders/src/categories.json'), json_encode($categories, JSON_UNESCAPED_UNICODE));
    }
}
