<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Node;
use Illuminate\Http\RedirectResponse;
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
        $slugs = explode('/', $path);

        $categories = Category::query()
            ->whereIn('slug', $slugs)
            ->get(['id', 'slug', 'title'])
            ->keyBy('slug')
        ;

        $sep = Node::$separator;
        $nodePath = '1.';
        $breadcrumbs = [];
        $url = '/catalog';

        foreach ($slugs as $key => $slug) {
            $nodePath .= $categories[$slug]['id'] . $sep;
            $url .= "/{$categories[$slug]['slug']}";

            $breadcrumbs[] = [
                'title' => $categories[$slug]['title'],
                'url' => $url
            ];
        }

        $categoryNode = Node::query()
            ->with('category:id,slug,title') // in order not to take all category fields
            ->where('path', '=', trim($nodePath, $sep))
            ->firstOrFail()
            ->append('title');

        $subCategories = Node::query()
            ->where('parent_path', $categoryNode['path'])
            ->get()
            ->append(['image', 'title', 'url']);

        return inertia('Categories/Show', compact('categoryNode', 'subCategories','breadcrumbs'));
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
