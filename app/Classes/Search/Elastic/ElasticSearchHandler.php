<?php


namespace App\Classes\Search\Elastic;


use App\Classes\Search\Meili\MeiliClientHandler;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ElasticSearchHandler
{
    public string $host;

    public function __construct()
    {
        $this->host = env('ELASTICSEARCH_HOST', 'host.docker.internal:9200');
    }

    public function getQuickSearchResults(string $searchString, array $params = []): array
    {
        $searchResults = [];

        $categoriesRawResults = $this->search('categories', $searchString,
            [
                // 'attributesToRetrieve' => ['id']
                // 'filter' => 'parent_id IN [602,1666]', // 'parent_id = 602'
                // 'filter' => str_replace('category_id', 'parent_id', $client->getFilterString($params)),
            ]);

        $searchResults['categories']['total'] = 0;
        $searchResults['categories']['items'] = [];
        $searchResults['categories']['time'] = 0;

        $productsRawResults = $this->search('products', $searchString);

        $items = array_map(fn($v) => $v['_source'], $productsRawResults['hits']['hits'] ?? []);

        $searchResults['products']['total'] = $productsRawResults['hits']['total']['value'] ?? 0;
        $searchResults['products']['items'] = $items;
        $searchResults['products']['time'] = $productsRawResults['took'] ?? 0;

        $searchResults['facet'] = [];

        return $searchResults;
    }


    public function createIndexWithSettings(string $index)
    {
        $url = "$this->host/$index";
        $settings = [
            "settings" => [
                "analysis" => [
                    "filter" => [
                        "ru_stop" => [
                            "type" => "stop",
                            "stopwords" => "_russian_",
                        ],
                        "ru_keywords" => [
                            "type" => "keyword_marker",
                            "keywords" => [
                                //"пример"
                            ],
                        ],
                        "ru_stemmer" => [
                            "type" => "stemmer",
                            "language" => "russian",
                        ],
                    ],
                    "analyzer" => [
                        "default" => [
                            "char_filter" => [
                                "html_strip",
                            ],
                            "tokenizer" => "standard",
                            "filter" => [
                                "lowercase",
                                "ru_stop",
                                "ru_keywords",
                                "ru_stemmer",
                            ],
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = Http::put($url, $settings)->json();
        } catch (\Exception $e) {
            $response = ['errorMessage' => $e->getMessage()];
        }

        return $response;
    }

    public function import(string $index)
    {
        $rawHandler = new ElasticSearchHandler();

        $products = Product::query()
            ->toBase()
            ->orderBy('id')
            //->skip(0)
            //->take(100)
            ->get(['id', 'category_id', 'code', 'slug', 'name', 'price', 'description', 'params', 'vendor']);

        $products = stdToArray($products);

        foreach ($products as $product) {
            $params = json_decode($product['params'], 1);
            //unset($product['params']);

            $items[] = [...$product, ...$params];
        }

        foreach (array_chunk($items, 1000) as $chunk) {
            $rawHandler->bulk($index, $chunk);
        }
    }

    public function search(string $index, string $searchString, array $options = []): array
    {
        $query = $this->getSearchQuery($index, $searchString, $options);

        try {
            return Http::withBody($query)->get("$this->host/$index/_search")->json();
        } catch (\Exception $e) {
            return ['errorMessage' => $e->getMessage()];
        }
    }

    public function getSearchQueryTest(string $index, string $searchString, array $options = []): string
    {
        $defaultOptions = [
            'size' => 50,
            'fuzziness' => 1,
            'fields' => ["name^3", "vendor^3", "params^1", "description"],
            'source' => null,
            'sort' => null,
        ];

        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        'multi_match' => [
                            'query' => $searchString,
                            "fields" => $options['fields'] ?? $defaultOptions['fields'],
                            "fuzziness" => $options['fuzziness'] ?? $defaultOptions['fuzziness'],
                        ],
                    ],
                ],
            ],
        ];

        return json_encode($query);
    }

    public function getSearchQuery(string $index, string $searchString, array $options = []): string
    {
        // for products
        $defaultOptions = [
            'size' => 50,
            'fuzziness' => 1,
            'fields' => ["name^3", "vendor^3", "params^1", "description"],
            'source' => null,
            'sort' => null,
        ];

        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        'multi_match' => [
                            'query' => $searchString,
                            "fields" => $options['fields'] ?? $defaultOptions['fields'],
                            "fuzziness" => $options['fuzziness'] ?? $defaultOptions['fuzziness'],
                        ],
                    ],
                ],
            ],
            'size' => $options['size'] ?? $defaultOptions['size'],
        ];


        foreach (@$options['filters'] ?? [] as $key => $values){
            // не применять для слов ищет только по точному совпадению с токенами 'альтернатив', 'пластишк' и т.д.
            $query['query']['bool']['filter'][] = ['terms' => [$key => $values]];
        }

        if (! is_null($source = $defaultOptions['source'] ?? @$options['source'])) {
            $query['_source'] = $source; // ["code", "name", 'description', 'params', 'price'] or false
        }

        if (! is_null($sort = $defaultOptions['sort'] ?? @$options['sort'])) {
            // TODO error Unknown key for a START_ARRAY in [_sort]
            //$query['_sort'] = $sort; //[["id" => "desc"]]
        }

        return json_encode($query);
    }

    public function searchOld(string $index, $searchString): array
    {
        $query = [
            'query' => [
                'multi_match' => [
                    'query' => $searchString,
                    "fields" => [
                        "name^3",
                        "params^1",
                        "description",
                    ],
                    'fuzziness' => 1,
                ],
                //'simple_query_string' => [
                //    'query' =>'стогл~',
                //    "fields" => [
                //        "name^3",
                //        "params^1",
                //        "description",
                //    ],
                //],
            ],
            'size' => 50,
            //'_source' => false,
            //'_source' => ["code", "name", 'description', 'params', 'price'],
            //'sort' => [["id" => "desc"]],
        ];

        $query = json_encode($query);

        try {
            return Http::withBody($query)->get("$this->host/$index/_search")->json();
        } catch (\Exception $e) {
            return ['errorMessage' => $e->getMessage()];
        }
    }

    public function check(): array
    {
        try {
            return Http::get($this->host)->json();
        } catch (\Exception $e) {
            return ['errorMessage' => $e->getMessage()];
        }
    }

    public function bulk(string $index, array $items, string $idColumn = 'id', string $type = '_doc'): string
    {
        // TODO replace curl to guzzle
        $data = [];

        foreach ($items as $item) {
            $data[] = json_encode(['index' => ['_id' => $item[$idColumn], '_index' => $index, '_type' => $type,]]);
            $data[] = json_encode($item, JSON_UNESCAPED_UNICODE);
        }

        $data = implode("\n", $data) . "\n";

        // Инициализация сеанса cURL
        $conn = curl_init();
        curl_setopt($conn, CURLOPT_URL, "$this->host/$index/$type/_bulk");
        curl_setopt($conn, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // set response as string
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, TRUE);
        // set request data
        curl_setopt($conn, CURLOPT_POSTFIELDS, $data);

        // other curl options
        //curl_setopt($conn, CURLOPT_TIMEOUT, 5);
        //curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($conn, CURLOPT_FAILONERROR, FALSE);
        //curl_setopt($conn, CURLOPT_CUSTOMREQUEST, strtoupper('POST'));
        //curl_setopt($conn, CURLOPT_FORBID_REUSE, 0);

        return curl_exec($conn);
    }

    public function putItems(string $index, array $items, string $idColumnName = 'id', string $type = '_doc'): array
    {
        $result = [];

        foreach ($items as $item) {
            $result[] = $this->put($index, $item);
        }

        return $result;
    }

    public function put(string $index, array $itemData, mixed $itemId = null, string $type = '_doc'): array
    {
        $itemId ??= $itemData['id'];
        $url = "$this->host/$index/$type/$itemId";

        try {
            $response = Http::put($url, $itemData)->json();
        } catch (\Exception $e) {
            $response = ['errorMessage' => $e->getMessage()];
        }

        return $response;
    }

    public function get(string $index, int $itemId, $fields = null, bool $onlySource = true, string $type = '_doc'): array
    {
        $url = "$this->host/$index/$type/$itemId";

        if ($onlySource) {
            $url .= '/_source';
        }

        if ($fields) {
            $url .= '?_source=' . implode(',', $fields);
        }

        try {
            return Http::get($url)->json();
        } catch (\Exception $e) {
            return ['errorMessage' => $e->getMessage()];
        }
    }

    public function deleteItem(string $index, mixed $itemId, string $type = '_doc'): array
    {
        try {
            return Http::delete("$this->host/$index/$type/$itemId")->json();
        } catch (\Exception $e) {
            return ['errorMessage' => $e->getMessage()];
        }
    }

    public function deleteAllIndexes(): array
    {
        return $this->deleteIndex('_all');
    }

    public function deleteIndex(string $index): array
    {
        try {
            return Http::delete("$this->host/$index")->json();
        } catch (\Exception $e) {
            return ['errorMessage' => $e->getMessage()];
        }
    }

    // TRASH

    public function bulkOrig(string $index, array $items, string $idColumn = 'id', string $type = '_doc')
    {
        $b = array();
        $sets = array();

        $params = [
            '_id' => null,
            '_index' => 'products',
            '_type' => '_doc',
        ];

        for ($i = 0; $i < 3; $i++) {
            $doc = [
                'id' => $i,
                'name' => 'name ' . $i,
            ];
            $set = array(
                array('create' => $params),
                $doc,
            );
            $sets[] = $set;
        }

        foreach ($sets as $set) {
            foreach ($set as $s) {
                $b[] = json_encode($s);
            }
        }
        $body = join("\n", $b) . "\n";

        $conn = curl_init();
        $requestURL = "$this->host/products/_doc/_bulk";
        curl_setopt($conn, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($conn, CURLOPT_URL, $requestURL);
        curl_setopt($conn, CURLOPT_TIMEOUT, 5);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($conn, CURLOPT_FAILONERROR, FALSE);
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, strtoupper('POST'));
        curl_setopt($conn, CURLOPT_FORBID_REUSE, 0);

        df(tmr(@$this->start), $body);
        if (is_array($body) && count($body) > 0) {
            curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($body));
        } else {
            curl_setopt($conn, CURLOPT_POSTFIELDS, $body);
        }

        //df(tmr(@$this->start), $conn);
        $response = curl_exec($conn);

        return $response;
    }

    public function putItemsTrash(string $index, array $items, string $idColumn = 'id', string $type = '_doc'): array
    {
        $data = '
        {"index" : {}}
        {"id":494167,"code":247458,"category_id":1266,"vendor":"Premium House"}
        ';
        //df(tmr(@$this->start), $data);
        //$data = "{ \"create\": {\"id\":494167,\"code\":247458,\"category_id\":1266,\"vendor\":\"Premium House\"} }\n";
        //$data .= json_encode($items[0]);
        //$data .="{\"id\":494167,\"code\":247458,\"category_id\":1266,\"vendor\":\"Premium House\"}\n";
        //df(tmr(@$this->start), $data);

        //$data = [
        //    "index" => "products",
        //    "type" => "_doc",
        //    "body" => [
        //        [
        //            "create" => [],
        //        ],
        //        [
        //            "_doc" => [
        //                "id" => 494167,
        //                "code" => 247458,
        //                "category_id" => 1266,
        //                "vendor" => "Premium House",
        //            ],
        //        ],
        //    ],
        //];
        $url = "$this->host/$index/_bulk?$data";


        try {
            $response = Http::put($url)->json();
        } catch (\Exception $e) {
            $response = ['errorMessage' => $e->getMessage()];
        }

        df(tmr(@$this->start), $response);

        return $response;
    }


}
