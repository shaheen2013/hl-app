<?php

use Aws\DynamoDb\DynamoDbClient;

class DynamoDbConnection
{

    public function sendRequest($table, $keyValue, $keyName)
    {
        global $log;
        $cacheName = str_replace("/", "-", $keyValue) . '- schema_name';
        $cache = getFromCache($cacheName);

        if (!$cache) {
            $log->debug("No cache, lets connect with dynamodb", ["keyValue" => $keyValue, "keyName" => $keyName, "table" => $table]);
            $client = $this->connection();

            $result = $client->getItem(array(
                "TableName" => $table,
                "ConsistentRead" => false,
                "Key" => array(
                    "$keyName" => array("S" => $keyValue)
                )));

            if ($result) {
                $schema = data_get($result, 'Item.schema.S', null);
                setToCache($cacheName, $schema, 31536000);
            }
        } else {
            $schema = $cache->get();
        }

        return $schema;
    }

    /**
     * DynamoDb standard connection
     *
     * @return DynamoDbClient
     */
    private function connection()
    {
        $config = AWS;
            
        $client = DynamoDbClient::factory(array(
            'region' => $config['region'],
            'version' => 'latest',
            'credentials' => [
                'key' => $config['credentials']['key'],
                'secret' => $config['credentials']['secret']
            ]
        ));

        return $client;
    }
}
