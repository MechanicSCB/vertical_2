<?php

namespace App\Http\Controllers;

use App\Classes\PaginateHandler;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class AdminCategoryController extends Controller
{
    public function index(Request $request): Response|ResponseFactory
    {
        $query = Category::query();

        if (strlen($search = $request['search'])) {
            $query->where('title', 'ilike', "%$search%");
            //$query->whereFullText('title', $search);
        }

        $query->latest();
        $query->with('nodes');

        $request['perPage'] ??= 50;
        $categories = PaginateHandler::getPaginated($query);

        return inertia('Admin/Categories/Index', compact('categories', 'search'));
    }

    public function edit(Category $category): Response|ResponseFactory
    {
        return inertia('Admin/Categories/Edit', compact('category'));
    }

    public function update(Category $category, StoreCategoryRequest $request): RedirectResponse
    {
        $category->update($request->validated());

        return back()->with('success', __('flash.successfully_updated'));
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('Admin/Categories/Create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $category = Category::query()->create(array_filter($validated));

        return redirect(route('admin.categories.edit', $category))->with('success', __('flash.successfully_created'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', __('flash.successfully_deleted'));
    }
}
