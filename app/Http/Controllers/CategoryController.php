<?php

namespace App\Http\Controllers;

use App\Actions\Nodes\GetAncestorsCategoriesFromPath;
use App\Actions\Nodes\GetBreadcrumbsFromUrl;
use App\Actions\Nodes\GetCategoryNodeFromPath;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Node;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
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
        $breadcrumbs = (new GetBreadcrumbsFromUrl())->get($path,$ancestorsCategories);
        $categoryNode = (new GetCategoryNodeFromPath())->get($path, $ancestorsCategories);
        $subCategories = $categoryNode->children->append(['image', 'title', 'url']);
        $productsQuery = $this->getProductsQuery($categoryNode['path']);
        $productsFilteredQuery = $this->getProductsFilteredQuery($productsQuery);
        $filterData = $this->getFilterData(clone($productsFilteredQuery));
        $products = $productsFilteredQuery->paginate()->onEachSide(1)->withQueryString();
        // TODO fix page max in Vue (если, текущая страница больше максимальной, сбросить на последнюю или первую)
        //df(tmr(@$this->start), $filterData,$products);

        return inertia('Categories/Show', compact('breadcrumbs', 'categoryNode', 'subCategories','filterData', 'products'));
    }

    public function getProductsQuery(string $categoryNodePath): Builder
    {
        $allSubCategoriesIds = Node::query()
            ->where('path', 'like', $categoryNodePath . Node::$separator . '%')
            ->orWhere('path', '=', $categoryNodePath)
            ->pluck('category_id');

        $productsQuery = DB::table('products')->whereIn('category_id', $allSubCategoriesIds);

        return $productsQuery;
    }

    public function getProductsFilteredQuery(Builder $productsQuery): Builder
    {
        $filter = request()->all();

        if (isset($filter['priceFrom'])) {
            $productsQuery->where('price', '>=', $filter['priceFrom']);
        }

        if (isset($filter['priceTo'])) {
            $productsQuery->where('price', '<=', $filter['priceTo']);
        }

        return $productsQuery;
    }

    public function getFilterData(Builder $productsQuery)
    {
        $filterData['minPrice']  = (clone($productsQuery))->orderBy('price')->first('price')->price;
        $filterData['maxPrice']  = (clone($productsQuery))->orderByDesc('price')->first('price')->price;
        $filterData['vendors']  = (clone($productsQuery))->distinct()->orderBy('vendor')->pluck('vendor')->filter()->values();

        return $filterData;
    }

    //public function create(): Response|ResponseFactory
    //{
    //    return inertia('Categories/Create');
    //}
    //
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
    //
    //public function edit(Category $category): Response|ResponseFactory
    //{
    //    $category->load('categoryNodes');
    //
    //    return inertia('Categories/Edit', compact('category'));
    //}
    //
    //public function update(Category $category, StoreCategoryRequest $request): RedirectResponse
    //{
    //    $category->update($request->validated());
    //
    //    return back()->with('success', __('flash.successfully_saved'));
    //}

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', __('flash.successfully_deleted'));
    }


}
