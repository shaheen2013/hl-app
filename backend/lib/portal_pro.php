<?php

/**
 * Check if the config is equal to the default portalProConfig
 */
function getDefaultPortalProConfig()
{
    // Default portalPro config
    return [
        'first_name' => '1',
        'last_name' => '1',
        'document_id' => '0',
        'room_number' => '1',
        'access_code' => '1',
        'premium_code' => '0',
        'radius_ticket' => '0',
        'premium_ticket' => '0',
        'max_validations' => '3',
        'restrictive' => '0'
    ];
}

/**
 * Fetch portalProConfiguration
 */
function listPortalProConfigs()
{
    $cacheName = "portalProConfigList";
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $sql = "SELECT * from portal_pro_config";
        $rows = lecturaArray($sql, $con);

        // Set in cache
        setToCache($cacheName, $rows, 2592000); // 1 Month cache
    } else {
        //Get result from cache
        $rows = $cache->get();
    }

    return $rows;
}

/**
 * Get portalPro configuration for a brand_id
 * 
 * @param $brand_id
 */
function getPortalProConfiguration($brand_id)
{
    global $log;

    //Get from cache
    $cacheName = "portalProConfig-$brand_id";
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $brand_id);
        $sql = "SELECT * FROM portal_pro_config WHERE brand_id=$brand_id";
        $row = lectura($sql, $con, true);

        // In case that is no config in database, get the default
        if (!$row) {
            // Set the config default
            $row = getDefaultPortalProConfig();
            // Log error to alert us
            $log->error("PortalPro", ['message' => "PortalPro with no configuration setted, default config returned", "brand_id" => $brand_id]);
        }
        // Set in cache
        setToCache($cacheName, $row, 2592000); // 1 Month cache
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row;
}

/**
 * Update portalProConfiguration
 */
function updatePortalProConfig($brand_id, $payload)
{
    //delete caches for portalProConfig
    deleteCacheByKey("portalProConfig-$brand_id");
    deleteCacheByKey('portalProConfigList');

    // Insert in db, if dont have the value setted, get from default values
    $defaultPortalProConfig = getDefaultPortalProConfig();
    $first_name = $payload['first_name'] ?? $defaultPortalProConfig['first_name'];
    $last_name = $payload['last_name'] ?? $defaultPortalProConfig['last_name'];
    $document_id = $payload['document_id'] ?? $defaultPortalProConfig['document_id'];
    $room_number = $payload['room_number'] ?? $defaultPortalProConfig['room_number'];
    $access_code = $payload['access_code'] ?? $defaultPortalProConfig['access_code'];
    $premium_access_code = $payload['premium_code'] ?? $defaultPortalProConfig['premium_code'];
    $radius_ticket = $payload['radius_ticket'] ?? $defaultPortalProConfig['radius_ticket'];
    $premium_ticket = $payload['premium_ticket'] ?? $defaultPortalProConfig['premium_ticket'];
    $max_validations = $payload['max_validations'] ?? $defaultPortalProConfig['max_validations'];
    $restrictive = $payload['restrictive'] ?? $defaultPortalProConfig['restrictive'];

    // Case is a config distinct from default
    $sql = "INSERT INTO portal_pro_config 
            (brand_id, room_number, first_name, last_name, document_id, access_code, max_validations, restrictive, premium_code, radius_ticket, premium_ticket)
        VALUES 
            ({$brand_id}, {$room_number}, {$first_name}, {$last_name}, {$document_id}, {$access_code}, {$max_validations}, {$restrictive}, {$premium_access_code}, {$radius_ticket}, {$premium_ticket})
        ON DUPLICATE KEY UPDATE 
            room_number=VALUES(room_number),
            first_name=VALUES(first_name),
            last_name=VALUES(last_name),
            document_id=VALUES(document_id),
            access_code=VALUES(access_code),
            max_validations=VALUES(max_validations),
            restrictive=VALUES(restrictive),
            premium_code=VALUES(premium_code),
            radius_ticket=VALUES(radius_ticket),
            premium_ticket=VALUES(premium_ticket);";

    escritura($sql);

    //delete caches for portalProConfig <-- Delete after update. Get updated info.
    deleteCacheByKey("portalProConfig-$brand_id");
    deleteCacheByKey('portalProConfigList');
}

/**
 * Delete portalProConfiguration for a brand_id
 */
function deletePortalProConfig($brand_id)
{
    //delete caches for portalProConfig
    deleteCacheByKey("portalProConfig-$brand_id");
    deleteCacheByKey('portalProConfigList');

    // Case config equal to default
    $sql = "DELETE FROM portal_pro_config
        WHERE
            brand_id = $brand_id";
    escritura($sql);
}

/**
 * Check if the config is equal to the default portalProConfig
 */
function isEqualToDefaultPortalProConfig($config)
{
    // Default portalPro config
    $defaultPortalProConfig = getDefaultPortalProConfig();

    // Check if config is equal to defaultPortalProConfig
    return $config == $defaultPortalProConfig;
}
