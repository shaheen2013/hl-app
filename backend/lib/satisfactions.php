<?php
use Carbon\Carbon;

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once 'obtenerdatosHotel.php';

function getCustomizedSatisfactionConfig($gateway, $key, $brand_id)
{
    if (!$cache = getFromCache($key)) {
        $products = safeJsonParser($gateway->sendRequest([], HOTELINKING_ENDPOINT . "products", 'GET'), true);
        $customizedSatisfactionProduct = array_first($products['data'] ?? [], function ($key, $product) {
            return array_get($product, 'name') == 'customized_satisfaction_surveys';
        });
        $customizedSatisfactionProductId = array_get($customizedSatisfactionProduct, 'id');
        $configuration = safeJsonParser($gateway->sendRequest([], HOTELINKING_ENDPOINT . "brands/$brand_id/products/$customizedSatisfactionProductId/configuration", 'GET'), true);
        $questionsConfiguration = safeJsonParser($gateway->sendRequest([], HOTELINKING_ENDPOINT . "brands/$brand_id/survey-questions", 'GET'), true);

        $cacheObject = [
            'customizedSatisfactionProductId' => $customizedSatisfactionProductId,
            'configuration'                   => $configuration,
            'questionsConfiguration'          => $questionsConfiguration
        ];

        setToCache($key, $cacheObject);
    } else {
        $cacheObject = $cache->get();
    }

    return $cacheObject;
}

function getSatisfactionConfig($gateway, $key, $brandID)
{
    $endPoint = HOTELINKING_ENDPOINT . "brands/{$brandID}/products/" . getIdByProductName('satisfaction') . "/configuration";

    if (!$cache = getFromCache($key)) {
        $satisfactionHotel = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), true);
        setToCache($key, $satisfactionHotel);
    } else {
        $satisfactionHotel = $cache->get();
    }

    return $satisfactionHotel;
}

function getDiffInHours($firstDay, $firstHour, $secondDay, $secondHour)
{
    $firstDate = Carbon::parse('1970-01-01')->addDays((int)$firstDay)->addHours((int)$firstHour);
    $secondDate = Carbon::parse('1970-01-01')->addDays((int)$secondDay)->addHours((int)$secondHour);

    return $firstDate->diffInHours($secondDate, false);
}
