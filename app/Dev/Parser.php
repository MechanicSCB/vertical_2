<?php


namespace App\Dev;


use App\Models\Product;
use DiDom\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Parser
{
    public function run()
    {
        $res = Product::query()
            ->selectRaw('jsonb_object_keys(params)')
            ->distinct()
            ->pluck('jsonb_object_keys')
            ->toArray()
        ;

        df(tmr(@$this->start),$res);
    }

    public function parseProductParams()
    {
        set_time_limit(300);
        $productsData = [];
        $htmlDir = base_path('common_data/html/');
        $htmlFileNames = scandir($htmlDir);
        $htmlFileNames = array_filter($htmlFileNames, fn($v) => str_ends_with($v, '.html'));
        //$htmlFileNames = array_slice($htmlFileNames, 0, 100);

        foreach ($htmlFileNames as $fileName) {
            $html = file_get_contents($htmlDir . $fileName);
            $document = new Document($html);

            $productData = [];

            if ($params = $document->first('div#tab-char')) {
                foreach ($params->find('div.tr') as $paramItem){
                    if(count($keyValue = ($paramItem->find('div.td'))) === 2){
                        $productData[$keyValue[0]->text()] = $keyValue[1]->text();
                    }
                }
            }

            $productsData[Str::before($fileName,'.')] = $productData;
        }

        Storage::put('data/products_params.json', json_encode($productsData,JSON_UNESCAPED_UNICODE));
        df(tmr(@$this->start),$productsData);

        //$categories = json_decode(Storage::get('data/categories.json'), 1)['categories'];
        //df(tmr(@$this->start), head($categories),$categories);

        $products = json_decode(Storage::get('data/search.json'), 1)['offers'];
        $product = array_filter($products, fn($v) => $v['offerCode'] == 246550);
        df(tmr(@$this->start), $product, head($products), array_slice($products, 1, 20));

        df(tmr(@$this->start), 'parser');
    }

    public function parseProductHtmls()
    {
        set_time_limit(300);
        $productsData = [];
        $htmlDir = base_path('common_data/html/');
        $htmlFileNames = scandir($htmlDir);
        $htmlFileNames = array_filter($htmlFileNames, fn($v) => str_ends_with($v, '.html'));
        //$htmlFileNames = array_slice($htmlFileNames, 0, 2000);

        foreach ($htmlFileNames as $fileName) {
            $html = file_get_contents($htmlDir . $fileName);
            $document = new Document($html);

            $tmp = [];
            $productData = [];

            foreach ($document->find('.slide>img') as $image) {
                if ($imgDataId = $image->getAttribute('data-id')) {
                    $tmp[] = $imgDataId;
                }
            }

            $productData['image_count'] = count($tmp);

            if ($nameEl = $document->first('h1')) {
                $productData['name'] = $nameEl->text();
            }

            if ($codeEl = $document->first('span[id$=_article_value]')) {
                $productData['code'] = $codeEl->text();
            }

            if ($repliesNumEl = $document->first('span.review-label')) {
                $productData['replies'] = intval($repliesNumEl->text());
            }

            if ($availEl = $document->first('span[id$=_avail]')) {
                $productData['avail'] = $availEl->text();
            }

            if ($oldPrice = $document->first('.item-info-price-old-value')) {
                $productData['old_price'] = $oldPrice->text();
            }

            if ($price = $document->first('.item-info-price-value')) {
                $productData['price'] = $price->text();
            }

            if ($availability = $document->first('.item-info-availability')) {
                $productData['availability'] = trim(str_replace("\n", '', $availability->text()));
            }

            if ($description = $document->first('[itemprop="description"]')) {
                $productData['description'] = $description->innerHtml();
            }

            if ($params = $document->first('div#tab-char')) {
                $productData['paramsHtml'] = $params->innerHtml();

                foreach ($params->find('div.tr') as $paramItem){
                    if(count($keyValue = ($paramItem->find('div.td'))) === 2){
                        $productData['params'][$keyValue[0]->text()] = $keyValue[1]->text();
                    }
                }
            }

            if ($reviewsHtml = $document->first('div.reviews-list')){
                $productData['reviewsHtml']= trim($reviewsHtml->innerHtml());
            }

            $productsData[Str::before($fileName,'.')] = $productData;
        }

        Storage::put('data/products_data.json', json_encode($productsData,JSON_UNESCAPED_UNICODE));
        df(tmr(@$this->start),count($productsData));

        //$categories = json_decode(Storage::get('data/categories.json'), 1)['categories'];
        //df(tmr(@$this->start), head($categories),$categories);

        $products = json_decode(Storage::get('data/search.json'), 1)['offers'];
        $product = array_filter($products, fn($v) => $v['offerCode'] == 246550);
        df(tmr(@$this->start), $product, head($products), array_slice($products, 1, 20));

        df(tmr(@$this->start), 'parser');
    }
}
