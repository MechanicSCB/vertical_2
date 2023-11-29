<?php


namespace App\Classes\Search\Elastic;

use App\Models\Category;
use App\Models\Product;

class ElasticScoutHandler
{
    public function search(string $searchText)
    {
        if($searchText !== ''){
            $searchText .= '~';
        }

        //$categories = Category::search($searchText)
        //    //->whereIn('parent_id', [1744,1822])
        //    ->paginate(100);
        ////df(tmr(@$this->start),$searchText, $categories);

        $products = Product::search($searchText)
            //->whereIn('category_id', [1745,1906,2534])
            //->where('price', 2135)
            //->where("Бренд","джилекс")
            //->where("Цвет","бесцветный")
            //->where("Цвет","красный")
            //->where("Бренд","energy")
            //->where("Покрытие нагревательного элемента","нержавеющая сталь")
            ->raw()
            //->paginate(100)
            //->get()
        ;

        //df(tmr(@$this->start), $products);
        return $products;

    }
}
