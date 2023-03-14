<?php

namespace App\Http\Controllers;

use App\Classes\Meili\MeiliClientHandler;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function getSearchResults(Request $request): array
    {
        $searchString = $request['searchString'] ?? '';
        $client = new MeiliClientHandler();

        $categoriesRaw = $client->search('categories', $searchString,
            [
                //'filter' => str_replace('category_id', 'parent_id', $client->getFilterString($filter)),
            ]);

        $productsRaw = $client->search('products', $searchString,
            ['limit' => 30, 'facets' => ['category_id'],
             //'filter' => $client->getFilterString([$filter]),
            ]
        );

        $searchResults['categories'] = [
            'total' => $categoriesRaw['estimatedTotalHits'] ?? 0,
            'data' => $categoriesRaw['hits'] ?? [],
        ];

        $searchResults['products'] = [
            'total' => $productsRaw['estimatedTotalHits'] ?? 0,
            'data' => $productsRaw['hits'] ?? [],
        ];

        $categoriesFacet = $productsRaw['facetDistribution']['category_id'];
        $categoriesTitles = Category::query()->find(array_keys($categoriesFacet))->pluck('title', 'id');

        foreach ($categoriesFacet as $categoryId => $itemsCount) {
            if (is_null($categoryTitle = @$categoriesTitles[$categoryId])) {
                continue;
            }

            $categoriesFacet[$categoryId] = ['id' => $categoryId, 'title' => $categoryTitle, 'itemsCount' => $itemsCount];
        }

        usort($categoriesFacet, fn($a, $b) => @$a['itemsCount'] < @$b['itemsCount']);

        $searchResults['facet'] = $categoriesFacet;

        return $searchResults;
    }
}
