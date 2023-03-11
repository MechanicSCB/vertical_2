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
    }

    public function reverse()
    {
        $keys = Schema::getColumnListing('nodes');
        $keys = array_filter($keys, fn($v) => ! in_array($v, ['created_at', 'updated_at','level']));

        $items = DB::table('nodes')->get($keys);

        file_put_contents(database_path('seeders/src/nodes.json'), json_encode($items, JSON_UNESCAPED_UNICODE));
    }

    // TRASH
    public function runOld(): void
    {
        $this->categories = Category::query()->orderBy('cat_level')->get(['id', 'parent_id', 'slug'])->keyBy('id')->toArray();

        $nodes = [];
        $sep = Node::$separator;

        $orders = [];

        foreach ($this->categories as $category) {
            $parentPath = Str::beforeLast($this->getCategoryPath($category), $sep);
            $parentPath = trim($parentPath, $sep);

            if($parentPath === ''){
                $parentPath = null;
            }

            $path = $parentPath.$sep.$category['id'];
            $path = trim($path, $sep);

            $orders[$parentPath] = @$orders[$parentPath] + 1;

            $nodes[] = [
                'path' => $path,
                'parent_path' => $parentPath,
                'category_id' => $category['id'],
                'order' => $orders[$parentPath],
            ];
        }

        Node::query()->truncate();
        Node::query()->upsert($nodes, ['path']);
    }

    private function getCategoryPath(array $category): string
    {
        if ($category['parent_id'] === null) {
            return Node::$separator . $category['id'];
        }

        return $this->getCategoryPath($this->categories[$category['parent_id']]) . Node::$separator . $category['id'];
    }

}
