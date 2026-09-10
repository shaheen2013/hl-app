<?php

/**
 * @param $hotel_id
 * @return array
 * @throws Exception
 */
function getBrandEprivacyInfo($hotel_id)
{
    $cacheName = 'brand_eprivacy_' . $hotel_id;
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT company_name,company_address,company_nif,company_email, restricted_portal, CASE WHEN max(products.producto) IS NOT NULL THEN 1 ELSE 0 END as not_hotel 
            FROM 
                brand_eprivacy 
            LEFT JOIN hoteles ON brand_eprivacy.hotel_id = hoteles.id
            LEFT JOIN brands on brands.hotel_id = hoteles.id
            LEFT JOIN brand_product ON brand_product.brand_id = brands.id AND brand_product.active = 1
            LEFT JOIN products ON products.id = brand_product.product_id AND producto = 'not_hotel'
            WHERE hoteles.id = $hotel_id
            GROUP BY brands.id;";

        $row =  lectura($sql);
        if ($row) {
            $tags = array('brand_eprivacy_' . $hotel_id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row;
}

/**
 * @param $hotel_id
 * @param $chain_id
 * @param $payload
 * @throws Exception
 */
function setBrandEprivacyInfo($hotel_id, $chain_id, $payload)
{
    global $log;
    $log->debug('Sending BrandEprivacyInfoToAPi', ['hotel_id' => $hotel_id, 'chain_id' => $chain_id, 'payload' => $payload]);
    include_once APP . 'Services/Connections/ApiGatewayConnection.php';
    $gateway = new ApiGatewayConnection();
    try {
        $brand_id = $hotel_id ?? $chain_id;
        $gateway->sendRequest(
            [
                'hotelId' => $hotel_id,
                'chainId' => $chain_id,
                'info' => $payload
            ],
            HOTELINKING_ENDPOINT . "brands/$brand_id/gdpr",
            'POST'
        );
        $cacheName = 'brand_eprivacy_' . $hotel_id;
        deleteCacheByKey($cacheName);
        return ['error' => false];
    } catch (Exception $e) {
        $log->debug('setBrandEprivacyInfoError', ['error' => $e, 'hotel_id' => $hotel_id, 'chain_id' => $chain_id]);
        return ['error' => true];
    }
}

function getEprivacyPermisions($brandID)
{
    $sql = "SELECT active from brand_product INNER JOIN products ON brand_product.product_id = products.id WHERE brand_id=$brandID AND producto='eprivacy_responsible'";
    return array_get(lectura($sql), 'active', false);
}
