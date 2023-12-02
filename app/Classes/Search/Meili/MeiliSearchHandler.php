<?php


namespace App\Classes\Search\Meili;


use App\Models\Category;

class MeiliSearchHandler
{
    public function getQuickSearchResults(string $searchString, array $params = []): array
    {
        $searchResults = [];

        $client = new MeiliClientHandler();

        $categoriesRawResults = $client->search('categories', $searchString,
            [
                // 'attributesToRetrieve' => ['id']
                // 'filter' => 'parent_id IN [602,1666]', // 'parent_id = 602'
                // 'filter' => str_replace('category_id', 'parent_id', $client->getFilterString($params)),
            ]);

        $searchResults['categories']['total'] = $categoriesRawResults['estimatedTotalHits'] ?? 0;
        $searchResults['categories']['items'] = $categoriesRawResults['hits'] ?? [];
        $searchResults['categories']['time'] = $categoriesRawResults['processingTimeMs'] ?? 0;

        $productsRawResults = $client->search('products', $searchString,
            [
                'limit' => 10, 'facets' => ['category_id'],
                // 'attributesToRetrieve' => ['id']
                // 'filter' => $client->getFilterString([$params]),
            ]);

        $searchResults['products']['total'] = $productsRawResults['estimatedTotalHits'] ?? 0;
        $searchResults['products']['items'] = $productsRawResults['hits'] ?? [];
        $searchResults['products']['time'] = $productsRawResults['processingTimeMs'] ?? 0;

        $categoriesFacet = $productsRawResults['facetDistribution']['category_id'] ?? [];
        $categoriesData = Category::query()->with('nodes')->find(array_keys($categoriesFacet))->keyBy('id');

        foreach ($categoriesFacet as $categoryId => $itemsCount) {
            if (is_null($category = @$categoriesData[$categoryId])) {
                continue;
            }

            $categoriesFacet[$categoryId] = $category;
            $categoriesFacet[$categoryId]['itemsCount'] = $itemsCount;
        }

        usort($categoriesFacet, fn($a, $b) => @$a['itemsCount'] < @$b['itemsCount']);

        $searchResults['facet'] = $categoriesFacet;


        return $searchResults;
    }

}
