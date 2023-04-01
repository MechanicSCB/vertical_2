<?php

namespace App\Classes;

use App\Classes\Search\Pgsql\PgsqlSearchHandler;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

class ProductFilterHandlerOld
{
    public function getFilterData(Builder $productsQuery): array
    {
        $filterData['sort_options'] = config('filter.sort_options');
        $filterData['minPrice'] = (clone($productsQuery))->reorder()->orderBy('price')->first('price')?->price ?? 0;
        $filterData['maxPrice'] = (clone($productsQuery))->reorder()->orderByDesc('price')->first('price')?->price ?? 0;

        $allParams = (clone($productsQuery))->pluck('params')->toArray();

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

        // dd(tmr(),$filterData,$filterData['params']['Бренд']);

        return $filterData;
    }

    public function getPaginatedProducts()
    {
        $products = (clone ($productsFilteredQuery))
            ->select(['id', 'code', 'price', 'slug', 'name', 'availability'])
            ->paginate(34)->onEachSide(1)->withQueryString();

        // if products not found try similar words
        if (strlen(request('search') ?? '') > 2 && $products->total() === 0) {
            $filterData['hints'] = (new PgsqlSearchHandler())->getSimilarWords(request('search'));

            $hints = Arr::flatten($filterData['hints']);

            if(count($hints)){
                request()['search'] = $hints;
                $productsFilteredQuery = $this->getProductsFilteredQuery(clone($productsQuery), $filterData);

                $products = (clone ($productsFilteredQuery))
                    ->select(['id', 'code', 'price', 'slug', 'name', 'availability'])
                    ->paginate(34)->onEachSide(1)->withQueryString();
            }
        }

        return $products;
    }
    public function getProductsFilteredQuery(Builder $productsQuery, array $filterData, bool $withSimilar = false): Builder
    {
        $filter = $filterData['form'];

        if (isset($filter['sortBy'])) {
            $column = @$filterData['sort_options'][$filter['sortBy']]['column'];
            $direction = @$filterData['sort_options'][$filter['sortBy']]['direction'];

            if ($column && $direction) {
                $productsQuery->orderBy($column, $direction);
            }
        }

        if (isset($filter['search'])) {
            $searchStrings = Arr::wrap($filter['search']);

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

        if (isset($filter['priceFrom'])) {
            $productsQuery->where('price', '>=', $filter['priceFrom']);
        }

        if (isset($filter['priceTo'])) {
            $productsQuery->where('price', '<=', $filter['priceTo']);
        }

        foreach ($filter['params'] ?? [] as $paramName => $values) {
            // Validation query paramName
            if (! isset($filterData['params'][$paramName])) {
                break;
            }

            // Validation query paramValues
            $paramValues = [];

            foreach ($values as $value) {
                if (! in_array($value, array_column($filterData['params'][$paramName], 'value') ?? [])) {
                    continue;
                }

                $paramValues[] = "'$value'";
            }

            $productsQuery->whereRaw("params->>'" . $paramName . "' in (" . implode(",", $paramValues) . ")");
        }

        return $productsQuery;
    }



    // public function getFilterDataOld(Builder $productsQuery): array
    // {
    //     $filterData['sort_options'] = config('filter.sort_options');
    //     $filterData['minPrice'] = (clone($productsQuery))->reorder()->orderBy('price')->first('price')?->price ?? 0;
    //     $filterData['maxPrice'] = (clone($productsQuery))->reorder()->orderByDesc('price')->first('price')?->price ?? 0;
    //     //$filterData['vendors'] = (clone($productsQuery))->distinct()->orderBy('vendor')->pluck('vendor')->filter()->values();
    //
    //     $allParams = (clone($productsQuery))->pluck('params')->toArray();
    //
    //     $productCount = (clone($productsQuery))->count();
    //
    //     $filterData['params'] = [];
    //
    //     foreach ($allParams as $productParams) {
    //         $productParams = json_decode($productParams, 1);
    //
    //         foreach ($productParams as $param => $value) {
    //             $filterData['params'][$param][$value] = $value;
    //         }
    //     }
    //
    //     $filterData['params'] = array_map('array_values', $filterData['params']);
    //
    //     // TODO sort the parameters by importance and change the condition for limiting the number of parameters
    //     // if (count($filterData['params']) > 20) {
    //     //     $mainParamKeys = ['Бренд', 'Тип товара', 'Серия'];
    //     //     $filterData['params'] = array_filter($filterData['params'],
    //     //         fn($v, $k) => count($v) > 15
    //     //             || in_array($k, $mainParamKeys), ARRAY_FILTER_USE_BOTH);
    //     // }
    //
    //     return $filterData;
    // }
}
