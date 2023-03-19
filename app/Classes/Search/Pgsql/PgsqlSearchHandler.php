<?php


namespace App\Classes\Search\Pgsql;


use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PgsqlSearchHandler
{
    public function getQuickSearchResults(string $searchString, array $params = [])
    {
        $results = [];

        $categories = Category::query()
            ->with('nodes')
            ->whereFullText('title', $searchString, ['language' => 'russian'])
            ->get(['id', 'title', 'slug'])
            ->toArray();

        $results['categories']['items'] = $categories;
        $results['categories']['total'] = count($categories);

        $productsQuery = Product::query()->toBase();
        $productsQuery->whereFullText('name', $searchString, ['language' => 'russian']);
        $products = (clone($productsQuery))->get()->toArray();

        $results['hints'] = [];

        // if products not found try similar words
        if (strlen($searchString) > 2 && count($products) === 0) {
            $results['hints'] = $this->getSimilarWords($searchString);

            $hints = Arr::flatten($results['hints']);

            if(count($hints)){
                $productsQuery = Product::query()->toBase();

                foreach ($hints as $hint) {
                    $productsQuery->orWhereFullText('name', $hint, ['language' => 'russian']);
                }

                $products = (clone($productsQuery))->get()->toArray();
            }
        }

        $results['products']['items'] = array_slice($products, 0, 10);
        $results['products']['total'] = count($products);

        // FACET
        $categoriesFacet = (clone($productsQuery))
            ->selectRaw('count(id) as items_count, category_id')
            ->groupBy('category_id')
            ->get()
            ->keyBy('category_id')
            ->toArray();

        $categoriesData = Category::query()->with('nodes')->find(array_keys($categoriesFacet))->keyBy('id');

        foreach ($categoriesFacet as $categoryId => $item) {
            if (is_null($category = @$categoriesData[$categoryId])) {
                continue;
            }

            $categoriesFacet[$categoryId] = $category;
            $categoriesFacet[$categoryId]['itemsCount'] = $item->items_count;
        }

        usort($categoriesFacet, fn($a, $b) => @$a['itemsCount'] < @$b['itemsCount']);

        $results['facet'] = $categoriesFacet;

        return $results;
    }

    public function getSimilarWords(string $searchString, int $limit = 5): array
    {
        $field = 'word';
        $hints = [];

        foreach (explode(' ', $searchString) as $word) {
            //$hints =[...$hints, ...$this->getHintsForWord($word, $limit)];
            $hints[$word] = $this->getHintsForWord($word, $limit);
        }

        return $hints;
    }

    public function getHintsForWord(string $string, int $limit = 5): array
    {
        $field = 'word';

        $hints = DB::table('words')
            ->selectRaw("$field, strict_word_similarity('$string', $field) AS sml")
            ->whereRaw("'$string' % $field")
            ->orderByDesc('sml')
            ->take($limit)
            ->pluck($field)
            ->toArray();

        if (in_array($string, $hints)) {
            return [];
        }

        return $hints;
    }

}
