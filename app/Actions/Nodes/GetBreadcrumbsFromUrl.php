<?php


namespace App\Actions\Nodes;


use App\Models\Category;
use Illuminate\Support\Str;

class GetBreadcrumbsFromUrl
{
    public function get(string $url, array $ancestorsCategories = null): array
    {
        $ancestorsCategories ??= (new GetAncestorsCategoriesFromPath())->get($url);

        $host = request()->host();
        $url = trim(Str::after($url, "$host/"), '/');
        $slugs = explode('/', $url);

        $breadcrumbs = [];
        $itemUrl = '';

        foreach ($slugs as $slug) {
            $itemUrl .= "/{$ancestorsCategories[$slug]['slug']}";

            $breadcrumbs[] = [
                'title' => $ancestorsCategories[$slug]['title'],
                'url' => $itemUrl,
            ];
        }

        return $breadcrumbs;
    }
}
