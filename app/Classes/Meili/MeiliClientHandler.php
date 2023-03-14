<?php


namespace App\Classes\Meili;


use Illuminate\Support\Facades\Http;
use Meilisearch\Client;

class MeiliClientHandler
{
    public function search(string $index, string $searchString, array $searchParams = []): array
    {
        $client = new Client('http://meilisearch:7700');

        $res = $client->index($index)->search($searchString, $searchParams);

        return $res->getRaw();
    }

    public function getFilterString(array $filter): string
    {
        $filterString = '';

        if (count(array_filter($filter))) {
            $filterString = 'category_id IN [' . implode(',', $filter) . ']';
        }

        return $filterString;
    }

}
