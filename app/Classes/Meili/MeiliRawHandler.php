<?php


namespace App\Classes\Meili;


use Illuminate\Support\Facades\Http;

class MeiliRawHandler
{
    public function search()
    {
        $url =  "http://meilisearch:7700/indexes/products/settings/filterable-attributes";
        $params = ['id', 'name', 'category_id'];

        $res = Http::get($url, $params)->body();
        df(tmr(@$this->start), $res);

    }

    public function test()
    {
        $url =  "http://meilisearch:7700/indexes/products/settings/filterable-attributes";
        $params = ['id', 'name', 'category_id'];

        $res = Http::put($url, $params)->body();
        df(tmr(@$this->start), $res);

    }
}
