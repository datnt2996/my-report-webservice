<?php

namespace App\Helpers;

use Exception;
use Carbon\Carbon;
use App\Product\Model\Product;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;
use App\Product\Exceptions\ProductExceptions;



class ElasticSearchHelper
{
    private static $client;
    const DEFAULT_INDEX = 'order_products';
    const DEFAULT_ALIAS = 'order_product_alias';

    public static function getClient()
    {
        if (!empty(self::$client)) {
            return self::$client;
        }
        $hosts = [
            [
                'host' => env('ELASTICSEARCH_HOST', 'localhost'),
                'port' => env('ELASTICSEARCH_PORT', '9200'),
                'scheme' => env('ELASTICSEARCH_SCHEME', 'http'),
                'user' => env('ELASTICSEARCH_USER', null),
                'pass' => env('ELASTICSEARCH_PASS', null)
            ]
        ];
        self::$client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        return self::$client;
    }
    public static function checkAttributesSearch($params, $attribute, $value)
    {
        if (!empty($value)) {
            $params['body']['query']['bool']['filter'][] = ['term' => [$attribute => $value]];
        }

        return $params;
    }
    public static function checkTradeStatus($params, $tradeStatus)
    {
        if (!empty($tradeStatus)) {
            if ($tradeStatus == 'null') {
                $params['body']['query']['bool']['must_not']['exists'] = [
                    'field' => 'trade_assurance_review_status'
                ];

                return $params;
            }
            $params['body']['query']['bool']['filter'][] = [
                'term' => ['trade_assurance_review_status' => $tradeStatus]
            ];
        }
        return $params;
    }
    public static function updateSortElastic($sortBy, $order)
    {
        $order = $order == 'asc' ? 'asc' : 'desc';
        if (!in_array($sortBy, self::arrayAttributes())) {
            return ['created_at' => $order];
        }

        return [$sortBy ?? 'created_at' => $order];
    }
    public static function filterCheck(string $check, $limit = 1, $from = 1)
    {
        $client = self::getClient();
        $params = [
            'size' => $limit,
            'from' => ($from - 1) * $limit,
            'index' =>  self::DEFAULT_ALIAS,
            'track_total_hits' => true
        ];
        if (empty($check)) {
            $params['body']['query']['bool']['must_not']['exists'] = [
                'field' => 'check'
            ];
            return $client->search($params);
        }
        $params['body']['query']['bool']['filter'][] = [
            'term' => ['check' => $check]
        ];

        return $client->search($params);
    }
    public static function search(
        $query,
        $from = 1,
        $limit = 20,
        $shopId = null,
        $reviewStatus = null,
        $tradeStatus = null,
        $sortBy = null,
        $order = 'desc'
    ) {
        $client = self::getClient();

        $params = [
            'size' => $limit,
            'from' => ($from - 1) * $limit,
            'index' =>  self::DEFAULT_ALIAS,
            'track_total_hits' => true
        ];


        if (!empty($query)) {
            $params['body']['query']['bool']['must'] = [
                'query_string' => ['query' => $query, 'default_field' => 'title']
            ];
        }


        $params = self::checkAttributesSearch($params, 'shop_id', $shopId);
        $tradeStatus == 'null' ? null : $tradeStatus;
        $params = self::checkAttributesSearch($params, 'review_status', $reviewStatus);
        $params = self::checkTradeStatus($params, $tradeStatus);

        $params['body']['sort'] = self::updateSortElastic($sortBy, $order);
        if (!empty($query)) {
            unset($params['body']['sort']);
        }

        return $client->search($params);
    }
    public static function addAliases($index, $aliasName = null)
    {
        $client = self::getClient();
        $params['body'] = [
            'actions' => [
                "add" => [
                    "index" => $index ?? env('NEW_ELASTICSEARCH_PRODUCT', self::DEFAULT_INDEX),
                    "alias" =>  $aliasName ?? self::DEFAULT_ALIAS,
                    "is_write_index" => true
                ]
            ]
        ];

        return $client->indices()->updateAliases($params);
    }
    public static function findById($id){
        $client = self::getClient();
        $params['body']['query']['terms']['_id'] = [$id];
        $params['index']= self::DEFAULT_ALIAS;

        $res = $client->search($params);
        if(!empty($res['hits']['hits'])){
            return $res['hits']['hits'][0];
        }
        return false;
    }
    public static function removeIndex($oldIndex = null)
    {
        $client = self::getClient();
        $params['body'] = [
            'actions' => [
                'remove_index' => [
                    "index" => $oldIndex ?? env('INDEX_ELASTICSEARCH_PRODUCT', self::DEFAULT_INDEX)
                ]
            ]
        ];
        return $client->indices()->updateAliases($params);
    }

    public static function updateDocument( $event)
    {
        $client = self::getClient();
        $params = [
            'index' => self::DEFAULT_ALIAS,
            'id'    => $product->id,
            'body'  => [
                'doc' =>  self::configBodyIndex($product),
                'upsert' => [
                    'counter' => 1
                ],
            ],
        ];
        $res = $client->update($params);
        $product->update(['index_at' => Carbon::now()->toDateTimeString()]);
        return $res;
    }
    public static function updateDocumentByFields(string $id, array $values)
    {
        $client = self::getClient();
        $params = [
            'index' => self::DEFAULT_ALIAS,
            'id'    => $id,
            'body'  => [
                'doc' =>  $values,
            ],
        ];
        return $client->update($params);
    }

    public static function removeDocument($productId, $isCommand = false)
    {
        try {
            if(!self::findById($productId)){
                return true;
            }
            $client = self::getClient();
            $params = [
                'index' => self::DEFAULT_ALIAS,
                'id'    => $productId,
            ];

            return $client->delete($params);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if (!$isCommand) {
                ProductExceptions::deleteProductFailed($e);
            }
        }
    }
    public static function checkEmptyIndex($client)
    {
        if ($client->indices()->exists(['index' => self::DEFAULT_ALIAS])) {
            return true;
        }

        return false;
    }
    public static function indexProducts($products)
    {
        $client = self::getClient();
        if (!self::checkEmptyIndex($client)) {
            self::createMapping();
            self::addAliases(env('INDEX_ELASTICSEARCH_PRODUCT', self::DEFAULT_INDEX));
        }

        foreach ($products as $product) {
            if ($product->index) {
                continue;
            }
            $params['body'][] = [
                'index' => [
                    '_index' =>  self::DEFAULT_ALIAS,
                    '_id' => $product->id,
                ]
            ];
            $params['body'][] = self::configBodyIndex($product);
            $product->update(['index_at' => Carbon::now()->toDateTimeString()]);
        }
        if (!empty($params['body'])) {
            return $client->bulk($params);
        }
    }

    public static function index(Product $product)
    {
        if ($product->index_at) {
            return true;
        }

        $client = self::getClient();
        if (!self::checkEmptyIndex($client)) {
            self::createMapping();
            self::addAliases(env('INDEX_ELASTICSEARCH_PRODUCT', self::DEFAULT_INDEX));
        }
        $params = [
            'id'    => $product->id,
            'index' =>  self::DEFAULT_ALIAS,
            'body'  => self::configBodyIndex($product),
        ];

        $client->index($params);

        $product->update(['index_at' => Carbon::now()->toDateTimeString()]);
    }

    public static function recreateIndex($newIndex = 'order_product')
    {
        $params = [
            'body' => [
                'source' => [
                    'index'  => env('INDEX_ELASTICSEARCH_PRODUCT', self::DEFAULT_INDEX),
                ],
                'dest' => [
                    'index' => $newIndex
                ]
            ]
        ];
        $client = self::getClient();

        return $client->reindex($params);
    }
    public static function createMapping($strIndex = null)
    {
        $client = self::getClient();
        $params = [
            'index' => $strIndex ?? env('INDEX_ELASTICSEARCH_PRODUCT', self::DEFAULT_INDEX),
            'body'  => [
                'settings' => [
                    'number_of_shards' => 4
                ],
                'mappings' => [
                    'properties' => self::configMapping(),
                ]
            ],
        ];

        return $client->indices()->create($params);
    }
    public static function updateMapping()
    {
        $client = self::getClient();
        $params = [
            'index' =>  self::DEFAULT_ALIAS,
            'body'  => [
                'properties' => self::configMapping(),
            ]
        ];
        return $client->indices()->putMapping($params);
    }
    public static function getMapping()
    {
        $client = self::getClient();
        $params = [
            'index' => [env('INDEX_ELASTICSEARCH_PRODUCT', self::DEFAULT_INDEX)]
        ];
        return $client->indices()->getMapping($params);
    }
    public static function arrayAttributes()
    {
        return [
            'user_id', 'address', 'number_phone', 'title', 'description','handle'
            ,'represent_position','organization_name','time_start','time_end',
            'price','total_person', 'category_lv1','category_lv2', 'trade_assurance', 'is_trade_assurance',
            'trade_assurance_status', 'trade_assurance_reason', 'total_rate', 'total_star', 
            'published_at', 'created_at','updated_at'
        ];
    }
    public static function configBodyIndex($product)
    {
        return [
            'user_id' => $product->user_id,
            'address' => $product->address,
            'number_phone' => $product->number_phone,
            'title' => $product->title,
            'unit_id' => $product->unit_id,
            'description' => $product->description,
            'handle' => $product->handle,
            'represent_position' => $product->represent_position,
            'organization_name' => $product->organization_name,
            'time_start' => $product->time_start,
            'time_end' => $product->time_end,
            'price' => $product->price,
            'total_person' => $product->total_person,
            'category_lv1' => $product->category_lv1,
            'category_lv2' => $product->category_lv2,
            'trade_assurance' => $product->trade_assurance,
            'is_trade_assurance' => $product->is_trade_assurance,
            'trade_assurance_status' => $product->trade_assurance_status ,
            'trade_assurance_reason' => $product->trade_assurance_reason,
            'total_rate' => $product->total_rate,
            'total_star' => $product->total_star,
            'published_at' => $product->published_at,
            'created_at' => (int) $product->created_at,
            'updated_at' => $product->updated_at
        ];
    }
    
   
    


    public static function configMapping()
    {
        return [
            'user_id' => [
                'type' => 'text',
            ],
            'address' => [
                'type' => 'text',
            ],
            'number_phone' => [
                'type' => 'text',
            ],
            'title' => [
                'type' => 'text',
            ],
            'description' => [
                'type' => 'text',
                'index' => false
            ],
            'handle' => [
                'type' => 'text',
                'index' => false
            ],
            'represent_position' => [
                'type' => 'text'
            ],
            'organization_name' => [
                'type' => 'text'
            ],
            'time_start' => [
                'type' => 'text'
            ],
            'time_end' => [
                'type' => 'text'
            ],
            'price' => [
                'type' => 'long'
            ],
            'total_person' => [
                'type' => 'long'
            ],
            'category_lv1' => [
                'type' => 'text'
            ],
            'category_lv2' => [
                'type' => 'text'
            ],
            'trade_assurance' => [
                'type' => 'text',
                'index' => false
            ],
            'is_trade_assurance' => [
                'type' => 'bool',
                'index' => false
            ],
            'trade_assurance_status' => [
                'type' => 'text'
            ],
            'trade_assurance_reason' => [
                'type' => 'text'
            ],
            'total_rate' => [
                'type' => 'long'
            ],
            'total_star' => [
                'type' => 'long'
            ],
            'published_at' => [
                'type' => 'text',
                'index' => false
            ],
            'created_at' => [
                'type' => 'text'
            ],
            'updated_at' => [
                'type' => 'text'
            ]
        ];
    }
}