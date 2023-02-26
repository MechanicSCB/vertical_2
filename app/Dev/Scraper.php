<?php


namespace App\Dev;


use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Scraper
{
    public function getProductsImages()
    {
        df(tmr(@$this->start), 'getProductsImages');
        $existed = Storage::files('data/images/1');
        $existedIds = array_map(fn($v) => Str::before(Str::afterLast($v, '/'), '.'), $existed);

        $products = json_decode(Storage::get('data/search.json'), 1)['offers'];
        $products = array_filter($products, fn($v) => ! in_array($v['offerCode'].'_1', $existedIds));

        $products = array_slice($products,0,500);

        foreach (array_chunk($products,50) as $chunk){
            $responses = Http::pool(function (Pool $pool) use ($chunk) {
                foreach ($chunk as $product) {
                    $pool->as($product['offerCode'].'_1')->timeout(60)->get("https://vertical.ru/upload/external/{$product['offerCode']}_1.jpg");
                }
            });

            $failed = [];
            foreach ($responses as $productId => $response) {
                if (! is_a($response, 'Illuminate\Http\Client\Response')) {
                    $failed[] = $response;
                    continue;
                }

                Storage::put("data/images/1/$productId.jpg", $response->body());
            }
        }


        df(tmr(@$this->start), $failed);



        df(tmr(@$this->start), 'scraper');
    }

    public function getProductsHtml()
    {
        df(tmr(@$this->start), 'scraper');
        $existed = Storage::files('data/html');
        $existedIds = array_map(fn($v) => Str::before(Str::afterLast($v, '/'), '.'), $existed);

        $products = json_decode(Storage::get('data/search.json'), 1)['offers'];
        $products = array_filter($products, fn($v) => ! in_array($v['id'], $existedIds));

        df(tmr(@$this->start), count($products));
        $products = array_slice($products, 0, 100);

        $responses = Http::pool(function (Pool $pool) use ($products) {
            foreach ($products as $product) {
                $pool->as($product['id'])->timeout(60)->get($product['url']);
            }
        });

        $failed = [];
        foreach ($responses as $productId => $response) {
            if (! is_a($response, 'Illuminate\Http\Client\Response')) {
                $failed[] = $response;
                continue;
            }

            Storage::put("data/html/$productId.html", $response->body());
        }



        df(tmr(@$this->start), $failed);
        //$cats = Storage::get('data/categories.json');
        //$cats = json_decode($cats, 1);


        df(tmr(@$this->start), $products);

        df(tmr(@$this->start), 'scrap');
    }

}
