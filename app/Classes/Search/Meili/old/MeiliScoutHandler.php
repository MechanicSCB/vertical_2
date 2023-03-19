<?php


namespace App\Classes\Search\Meili\old;


use App\Models\Product;

class MeiliScoutHandler
{
    public function search(string $index, string $searchString)
    {
        $products = Product::search($searchString)
            //->whereIn('category_id', [1745,1906,2534])
            //->where('price>', 2135)
            //->where("Бренд","джилекс")
            //->where("Цвет","бесцветный")
            //->where("Покрытие нагревательного элемента","нержавеющая сталь")
            ->take(100)
            ->raw()
            //->get()
            //->paginate(100)
        ;

        df(tmr(@$this->start),$searchString, $products);

        return $products;
    }
}
