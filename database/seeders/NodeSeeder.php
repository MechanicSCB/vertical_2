<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Node;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
