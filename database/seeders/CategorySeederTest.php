<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategorySeederTest extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [[
            'id' => 0,
            'parent_id' => null,
            'title' => "Каталог",
            'slug' => "catalog",
        ]];

        for ($i=1;$i<=7;$i++){
            $categories[] = [
                'id' => $i,
                'parent_id' => 0,
                'title' => "cat_$i",
                'slug' => "cat_$i",
            ];
        }

        Category::query()->truncate();
        Category::query()->upsert($categories, ['id']);
    }
}
