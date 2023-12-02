<?php

namespace App\Http\Controllers;

use App\Classes\Search\Elastic\ElasticSearchHandler;
use App\Classes\Search\Meili\MeiliSearchHandler;
use App\Classes\Search\Pgsql\PgsqlSearchHandler;
use GuzzleHttp\Client;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function test()
    {
        $url = 'host.docker.internal:7700';

        try {
            $res = Http::timeout(2)->get($url);
        }catch (\Exception $e){
            dd(tmr(), $e);
        }

        dd(tmr(), $res);
    }
    public function getQuickSearchResults(Request $request): array
    {
        $searchString = $request['searchString'] ?? '';
        $results['elastic'] = (new ElasticSearchHandler())->getQuickSearchResults($searchString);
        $results['meili'] = (new MeiliSearchHandler())->getQuickSearchResults($searchString);
        $results['pgsql'] = (new PgsqlSearchHandler())->getQuickSearchResults($searchString);

        return $results;
    }
}
