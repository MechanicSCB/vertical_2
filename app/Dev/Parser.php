<?php


namespace App\Dev;


use Illuminate\Support\Facades\Storage;

class Parser
{
    public function run()
    {
        //$categories = json_decode(Storage::get('data/categories.json'), 1)['categories'];
        //df(tmr(@$this->start), head($categories),$categories);

        $products = json_decode(Storage::get('data/search.json'), 1)['offers'];
        $product = array_filter($products, fn($v) => $v['offerCode'] == 246550);
        df(tmr(@$this->start),$product, head($products),array_slice($products,1,20));

        df(tmr(@$this->start), 'parser');
    }
}
