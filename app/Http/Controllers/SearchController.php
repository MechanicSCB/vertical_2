<?php

namespace App\Http\Controllers;

use App\Classes\Search\Meili\MeiliSearchHandler;
use App\Classes\Search\Pgsql\PgsqlSearchHandler;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function getQuickSearchResults(Request $request): array
    {
        $searchString = $request['searchString'] ?? '';
        $results['meili'] = (new MeiliSearchHandler())->getQuickSearchResults($searchString);
        $results['pgsql'] = (new PgsqlSearchHandler())->getQuickSearchResults($searchString);

        return $results;
    }
}
