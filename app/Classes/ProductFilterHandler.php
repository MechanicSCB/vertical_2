<?php

namespace App\Classes;

use Illuminate\Database\Query\Builder;

class ProductFilterHandler
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
