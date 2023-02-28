<?php


namespace App\Actions\Nodes;


use App\Models\Category;

class GetAncestorsCategoriesFromPath
{
    public function get(string $path): array
    {
        return Category::query()
            ->whereIn('slug', explode('/', $path))
            ->get(['id', 'slug', 'title'])
            ->toBase()
            ->keyBy('slug')
            ->toArray();
    }

}
