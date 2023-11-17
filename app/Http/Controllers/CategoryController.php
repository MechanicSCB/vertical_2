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
}
