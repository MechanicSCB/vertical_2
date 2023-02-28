<?php
return [
    'sort_options' => [
        'popular' => ['name' => 'Популярные', 'column' => 'image_count', 'direction' => 'desc'],
        'price_asc' => ['name' => 'Цена по возрастанию', 'column' => 'price', 'direction' => 'asc'],
        'price_desc' => ['name' => 'Цена по убыванию', 'column' => 'price', 'direction' => 'desc'],
        'discount_desc' => ['name' => 'По размеру скидки', 'column' => 'order', 'direction' => 'desc'],
        'reviews' => ['name' => 'По рейтингу', 'column' => 'reply_count', 'direction' => 'desc'],
    ],
];
