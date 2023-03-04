<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Inertia\ResponseFactory;

class SearchController extends Controller
{
    public function index(Request $request): Response|ResponseFactory
    {
        $products = DB::table('products')
            ->paginate(15);

        return inertia('Search/Index', compact('products'));
    }
}
