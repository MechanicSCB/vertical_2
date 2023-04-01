<?php

namespace App\Classes;

use App\Classes\Search\Pgsql\PgsqlSearchHandler;
use App\Models\Node;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductFilterHandler
{
    private array $form;
    private array $sortOptions;
    private array $allProductsParamsNamesAndValues;
    private array $nodeFilterData;

    public function __construct(protected Node $categoryNode, Request $request)
    {
        $this->form = $request->all();
        $this->sortOptions = config('filter.sort_options');
        $this->allProductsParamsNamesAndValues = Cache::rememberForever('allProductsParamsNamesAndValues', fn() => $this->allProductsParamsNamesAndValues());
        $this->nodeFilterData = Cache::rememberForever("nodeFilterData:{$categoryNode['path']}", fn() => $this->getNodeFilterData());
    }

    public function getPaginatedProducts(): LengthAwarePaginator
    {
        $productsFilteredQuery = $this->getProductsFilteredQuery();

        $products = (clone ($productsFilteredQuery))
            ->select(['id', 'code', 'price', 'slug', 'name', 'availability'])
            ->paginate(34)->onEachSide(1)->withQueryString();

        // if products not found try similar words
        if (strlen(request('search') ?? '') > 2 && $products->total() === 0) {
            $filterData['hints'] = (new PgsqlSearchHandler())->getSimilarWords(request('search'));

            $hints = Arr::flatten($filterData['hints']);

            if(count($hints)){
                request()['search'] = $hints;
                $productsFilteredQuery = $this->getProductsFilteredQuery();

                $products = (clone ($productsFilteredQuery))
                    ->select(['id', 'code', 'price', 'slug', 'name', 'availability'])
                    ->paginate(34)->onEachSide(1)->withQueryString();
            }
        }

        return $products;
    }

    public function getFilterData(Builder $query = null): array
    {
        $filterData = $this->nodeFilterData;

        // dd(tmr(), $filterData);
        return $filterData;
    }

    protected function getQueryFilterData(Builder $query = null): array
    {
        $query ??= $this->getProductsFilteredQuery();
        $filterData['sort_options'] = $this->sortOptions;
        $filterData['minPrice'] = (clone($query))->reorder()->orderBy('price')->first('price')?->price ?? 0;
        $filterData['maxPrice'] = (clone($query))->reorder()->orderByDesc('price')->first('price')?->price ?? 0;

        $allParams = (clone($query))->pluck('params')->toArray();

        $filterData['params'] = [];

        foreach ($allParams as $productParams) {
            $productParams = json_decode($productParams, 1);

            foreach ($productParams as $param => $value) {
                if(array_key_exists($value, $filterData['params'][$param] ?? [])){
                    $filterData['params'][$param][$value]['node_count'] += 1;
                }else{
                    $filterData['params'][$param][$value]['value'] = $value;
                    $filterData['params'][$param][$value]['node_count'] = 1;
                }
            }
        }

        $filterData['params'] = array_map('array_values', $filterData['params']);
        $filterData['form'] = $this->form;

        return $filterData;
    }

    protected function getNodeProductsQuery(): Builder
    {
        $categoryNodePath = $this->categoryNode['path'];

        $allSubCategoriesIds = Node::query()
            ->where('path', 'like', $categoryNodePath . Node::$separator . '%')
            ->orWhere('path', '=', $categoryNodePath)
            ->pluck('category_id');

        $productsQuery = DB::table('products')->whereIn('category_id', $allSubCategoriesIds);

        return $productsQuery;
    }

    protected function getProductsFilteredQuery(): Builder
    {
        $productsQuery = $this->getNodeProductsQuery();

        if (isset($this->form['sortBy'])) {
            $column = @$this->sortOptions[$this->form['sortBy']]['column'];
            $direction = @$this->sortOptions[$this->form['sortBy']]['direction'];

            if ($column && $direction) {
                $productsQuery->orderBy($column, $direction);
            }
        }

        if (isset($this->form['search'])) {
            $searchStrings = Arr::wrap($this->form['search']);

            $productsQuery->where(function (Builder $q) use ($searchStrings){
                foreach ($searchStrings as $searchStr){
                    $q->orWhereFullText('name', $searchStr, ['language' => 'russian']);
                    $q->orWhereFullText('description', $searchStr, ['language' => 'russian']);
                    $q->orWhereFullText('params', $searchStr, ['language' => 'russian']);
                }

                return $q;
            }
            );
        }

        if (isset($this->form['priceFrom'])) {
            $productsQuery->where('price', '>=', $this->form['priceFrom']);
        }

        if (isset($this->form['priceTo'])) {
            $productsQuery->where('price', '<=', $this->form['priceTo']);
        }

        foreach ($this->form['params'] ?? [] as $paramName => $values) {
            // Validation query paramName
            if (! isset($this->allProductsParamsNamesAndValues['names'][$paramName])) {
                break;
            }

            $paramValues = [];

            foreach ($values as $value) {
                // Validation query paramValues
                if (! isset($this->allProductsParamsNamesAndValues['values'][$value])) {
                    break;
                }

                $paramValues[] = "'$value'";
            }

            $productsQuery->whereRaw("params->>'" . $paramName . "' in (" . implode(",", $paramValues) . ")");
        }

        return $productsQuery;
    }

    protected function allProductsParamsNamesAndValues(): array
    {
        $productsParamsData = Product::query()->pluck('params')->toArray();

        $allProductsParamsNamesAndValues = [];

        foreach ($productsParamsData as $productParamsData){
            $productParamsData = json_decode($productParamsData, 1);

            foreach ($productParamsData as $param => $value) {
                $allProductsParamsNamesAndValues['names'][$param] = $param;
                $allProductsParamsNamesAndValues['values'][$value] = $value;
            }
        }

        return $allProductsParamsNamesAndValues;
    }

    protected function getNodeFilterData(): array
    {
        return $this->getQueryFilterData($this->getNodeProductsQuery());
    }
}
