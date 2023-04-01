<?php

namespace App\Http\Controllers;

use App\Actions\Nodes\GetAncestorsCategoriesFromPath;
use App\Actions\Nodes\GetBreadcrumbsFromUrl;
use App\Actions\Nodes\GetCategoryNodeFromPath;
use App\Classes\ProductFilterHandler;
use App\Classes\Search\Pgsql\PgsqlSearchHandler;
use App\Models\Node;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Inertia\ResponseFactory;

class CategoryController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $mainCategories = Node::query()->whereNull('parent_path')->first()->children->append(['image', 'title', 'url']);

        return inertia('Categories/Index', compact('mainCategories'));
    }

    public function show(string $path): Response|ResponseFactory
    {
        $path = "catalog/$path";
        $ancestorsCategories = (new GetAncestorsCategoriesFromPath())->get($path);
        $breadcrumbs = (new GetBreadcrumbsFromUrl())->get($path, $ancestorsCategories);
        $categoryNode = (new GetCategoryNodeFromPath())->get($path, $ancestorsCategories);
        $subCategories = $categoryNode->children->append(['image', 'title', 'url']);

        $filterHandler = new ProductFilterHandler($categoryNode, request());

        $products = $filterHandler->getPaginatedProducts();
        $filterData = $filterHandler->getFilterData();

        $time = str_replace('time = ', '', tmr());

        return inertia('Categories/Show', compact('breadcrumbs', 'categoryNode', 'subCategories', 'filterData', 'products', 'time'));
    }


    public function show2(string $path): Response|ResponseFactory
    {
        $path = "catalog/$path";
        $ancestorsCategories = (new GetAncestorsCategoriesFromPath())->get($path);
        $breadcrumbs = (new GetBreadcrumbsFromUrl())->get($path, $ancestorsCategories);
        $categoryNode = (new GetCategoryNodeFromPath())->get($path, $ancestorsCategories);
        $subCategories = $categoryNode->children->append(['image', 'title', 'url']);

        $productsQuery = $this->getProductsQuery($categoryNode['path']);

        $filterHandler = new ProductFilterHandler();
        $filterData = $filterHandler->getFilterData(clone($productsQuery));
        $filterData['form'] = request()->all();

        $productsFilteredQuery = $this->getProductsFilteredQuery(clone($productsQuery), $filterData);

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

        $filterData['after'] = $filterHandler->getFilterData(clone($productsFilteredQuery));
        // dd(tmr(),$filterData['after']);

        $time = str_replace('time = ', '', tmr());

        return inertia('Categories/Show', compact('breadcrumbs', 'categoryNode', 'subCategories', 'filterData', 'products', 'time'));
    }


    // public function showOld(string $path): Response|ResponseFactory
    // {
    //     // TODO ref - replace from controller
    //     $path = "catalog/$path";
    //     $ancestorsCategories = (new GetAncestorsCategoriesFromPath())->get($path);
    //     $breadcrumbs = (new GetBreadcrumbsFromUrl())->get($path, $ancestorsCategories);
    //     $categoryNode = (new GetCategoryNodeFromPath())->get($path, $ancestorsCategories);
    //     $subCategories = $categoryNode->children->append(['image', 'title', 'url']);
    //     $productsQuery = $this->getProductsQuery($categoryNode['path']);
    //     //Cache::forget("{$categoryNode['path']}_filterData");
    //     $filterData = Cache::rememberForever("{$categoryNode['path']}_filterData", fn() => $this->getFilterData(clone($productsQuery)));
    //     $filterData['form'] = request()->all();
    //     $productsFilteredQuery = $this->getProductsFilteredQuery(clone($productsQuery), $filterData);
    //     // TODO ref to cache filter after
    //     //$filterData['after'] = $this->getFilterData(clone($productsFilteredQuery));
    //
    //     $products = clone ($productsFilteredQuery)
    //         ->select(['id', 'code', 'price', 'slug', 'name', 'availability'])
    //         ->paginate(34)->onEachSide(1)->withQueryString();
    //
    //     // if products not found try similar words
    //     if (strlen(request('search') ?? '') > 2 && $products->total() === 0) {
    //         $filterData['hints'] = (new PgsqlSearchHandler())->getSimilarWords(request('search'));
    //
    //         $hints = Arr::flatten($filterData['hints']);
    //
    //         if(count($hints)){
    //             request()['search'] = $hints;
    //             $productsFilteredQuery = $this->getProductsFilteredQuery(clone($productsQuery), $filterData);
    //
    //             $products = clone ($productsFilteredQuery)
    //                 ->select(['id', 'code', 'price', 'slug', 'name', 'availability'])
    //                 ->paginate(34)->onEachSide(1)->withQueryString();
    //         }
    //     }
    //
    //     $time = str_replace('time = ', '', tmr());
    //
    //     return inertia('Categories/Show', compact('breadcrumbs', 'categoryNode', 'subCategories', 'filterData', 'products', 'time'));
    // }
    //
    // public function getFilterData(Builder $productsQuery): array
    // {
    //     // TODO deactivate unavailable options after applying the filter
    //     $filterData['sort_options'] = config('filter.sort_options');
    //     $filterData['minPrice'] = (clone($productsQuery))->reorder()->orderBy('price')->first('price')?->price ?? 0;
    //     $filterData['maxPrice'] = (clone($productsQuery))->reorder()->orderByDesc('price')->first('price')?->price ?? 0;
    //     //$filterData['vendors'] = (clone($productsQuery))->distinct()->orderBy('vendor')->pluck('vendor')->filter()->values();
    //
    //     $allParams = (clone($productsQuery))->pluck('params')->toArray();
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
    //     if (count($filterData['params']) > 20) {
    //         $mainParamKeys = ['Бренд', 'Тип товара', 'Серия'];
    //         $filterData['params'] = array_filter($filterData['params'],
    //             fn($v, $k) => count($v) > 15
    //                 || in_array($k, $mainParamKeys), ARRAY_FILTER_USE_BOTH);
    //     }
    //
    //     return $filterData;
    // }

    //public function store(StoreCategoryRequest $request): RedirectResponse
    //{
    //    $validated = $request->validated();
    //    //df(tmr(@$this->start), $request->all(), $validated);
    //
    //    $category = Category::query()->create([
    //        'title' => $validated['title'],
    //        'slug' => $validated['slug'],
    //    ]);
    //
    //    $parentNodesIds = array_column($validated['parent_nodes'], 'id');
    //    $parentNodes = Node::query()->find($parentNodesIds);
    //
    //    foreach ($parentNodes as $key => $parentNode) {
    //        $categoryNode = [
    //            'parent_id' => $parentNode['id'],
    //            'path' => $parentNode['path'] . Node::$separator . $parentNode['category_slug'],
    //            'category_slug' => $category->slug,
    //        ];
    //
    //        if ($order = @$validated['parent_nodes'][$key]['order_inside']) {
    //            $categoryNode['order'] = $order;
    //            $order = null;
    //        }
    //
    //        // Not insert nodes due to different row lengths possibility (has order or not)
    //        Node::query()->create($categoryNode);
    //    }
    //
    //    return back()->with('success', __('flash.successfully_saved'));
    //}
}
