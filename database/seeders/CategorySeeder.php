<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = json_decode(file_get_contents(database_path('seeders/src/categories.json')), 1)['categories'];

        $maxId = 1;

        foreach ($categories as &$category) {
            if($maxId < $category['id']){
                $maxId = $category['id'];
            }

            $category['parent_id'] = $category['parentId'] ?? 1;
            $category['slug'] ??= Str::slug($category['title'],'-','ru');
            $category['cat_level'] = $category['level'] + 1;
            unset($category['url'], $category['parentId'], $category['level']);
        }

        array_unshift($categories, [
            "id" => 1,
            "title" => "Каталог",
            "lft" => 0,
            "rgt" => 0,
            "cat_level" => 0,
            "parent_id" => null,
            "slug" => "catalog",
        ]);

        Category::query()->truncate();
        Category::query()->upsert($categories, ['id']);

        // set increment id value to next after max
        $maxId++;
        DB::statement("ALTER SEQUENCE categories_id_seq RESTART WITH $maxId;");
    }
}
