<?php


namespace App\Classes\Search\Elastic;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Http;

class ElasticClientHandler
{
    public function test()
    {
        $client = ClientBuilder::create()
            ->setHosts(['elasticsearch:9200'])
            ->build();

        // Info API
        //$response = $client->info();
        $params = [
            'index' => 'products',
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => 'луна'
                    ]
                ]
            ]
        ];
        $response = $client->search($params);
        df(tmr(@$this->start), $response['hits']['hits']);

    }
}
