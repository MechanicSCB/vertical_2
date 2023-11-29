<?php


namespace App\Classes\Search\Meili;


use Meilisearch\Client;

class MeiliClientHandler
{
    public function search(string $index, string $searchString, array $searchParams = []): array
    {
        $client = new Client('http://meilisearch:7700');
        // $client = new Client('http://127.0.0.1:7700');

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
