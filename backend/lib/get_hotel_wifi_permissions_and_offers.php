<?php
require_once (RUTA_DIR . LIB . 'apiGateway.php');
//Get hotel permissions, Refactor
function getHotelWifiPermissions($hotel_id)
{

    $hotel_id = mysqli_real_escape_string(conectar(1), $hotel_id);

    $cacheName = 'hotel_wifi_offers_' . $hotel_id;
    $cache = getFromCache($cacheName);

    if(!$cache){

        $sql = "SELECT stay_time, bypass_active FROM hoteles WHERE id = '$hotel_id'";
        $row = lectura($sql, conectar(1));

        if($row){
            $tags = array ('hotel_wifi_offers_' . $hotel_id);
            setToCache($cacheName, $row, 31536000, $tags);
        }

    }else{
        $row = $cache->get();
    }

    return $row;

}

//Comprobar si el hotel tiene alguna oferta de wifi
function getActiveWifiOffer($brandID, $customerType, $lang = null)
{
    $lang = $lang ?? $_SESSION['userLang'] ?? 'en';

    $customerType = $customerType == 'client' || $customerType == '1' ?
        'accommodated' : 
        'non_accommodated';

    $now = date('Y-m-d');
    $brandOffersWifi = getBrandOffersWifi($brandID, $lang, $now, $now, $customerType, 86400);

    if (count($brandOffersWifi) > 1) {
        $brandOffersWifi = array_filter($brandOffersWifi, function ($offerWifi) {
            return !$offerWifi['is_default'];
        });
    }

    $activeOfferWifi = array_pop($brandOffersWifi);

    return $activeOfferWifi['id'] ? $activeOfferWifi : null;
}

function getDefaultOfferInsertData()
{
    return [
        'id'                => null,
        'accommodated'      => false,
        'non_accommodated'  => false,
        'offer_id'          => null,
        'condition'         => null,
        'offer_type'        => null,
        'period'            => 30,
        'valid_from'        => "",
        'valid_to'          => "",
        'is_default'        => false
    ];
}

function getDefaultOfferData()
{
    $defaultOfferData = [];
    $defaultOfferData[] = array_merge(getDefaultOfferInsertData(),
        [
            'img'         => "",
            'description' => [
                'name' => ""
            ]
        ]);

    return $defaultOfferData;
}

function getDefaultConditions()
{
    return [
        'facebook_login',
        'always',
        'facebook_share'
    ];
}

function getDefaultOfferTypes()
{
    return [
        'inmediate',
        'web'
    ];
}

function getBrandOffersWifi($brandID, $lang, $validFrom = null, $validTo = null, $customerType = null, $cacheTime = 31536000)
{
    global $log;

    $queryParams = '?' . trim(
        ($validFrom ? 'valid_from=' . $validFrom : "") . '&' 
        . ($validTo ? 'valid_to=' . $validTo : "") . '&' 
        . ($customerType ? 'customer_type=' . $customerType : "")
    , '&');
    $apiUrl = "brands/{$brandID}/offers-wifi/{$lang}" . $queryParams;
    $cacheName = str_replace("/", "-", $apiUrl);
    $cache = getFromCache($cacheName);
    if ($cache) {
        return $cache->get();
    }

    $gateway = createApiGatewayConnection();
    $request = safeJsonParser($gateway->sendRequest([], HOTELINKING_ENDPOINT . $apiUrl, 'GET'), true);
    $log->debug('GET WIFI OFFERS', [$request]);

    if (empty($request['data'])) {
        $request['data'] = getDefaultOfferData();
    }

    $tags = ['hotel', 'hotel_goals', 'brands_offers_wifi_' . $brandID];
    setToCache($cacheName, $request['data'], $cacheTime, $tags);

    return $request['data'];
}

function setBrandOffersWifi($brandID, $offersWifiData)
{
    global $log;

    $log->info('SET WIFI OFFERS', $offersWifiData);
    $response = [
        'data' => []
    ];
    $gateway = createApiGatewayConnection();
    $gateway->setResponseAll(true);

    try {
        $request = $gateway->sendRequest($offersWifiData, HOTELINKING_ENDPOINT . "brands/{$brandID}/offers-wifi", 'POST');
        if ($request->getStatusCode() == 201) {
            $response = safeJsonParser($request->getBody(), true);
        }
    } catch (Exception $exception) {
        $response = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message   
        ];
    }

    deleteCacheByTag('brands_offers_wifi_' . $brandID);

    return $response;
}

function deleteBrandOffersWifi($id, $brandID)
{
    global $log;

    $log->info('DELETE WIFI OFFER', [$id]);
    $gateway = createApiGatewayConnection();
    $gateway->setResponseAll(true);

    $request = $gateway->sendRequest(null, HOTELINKING_ENDPOINT . 'offers-wifi/' . $id, 'DELETE');
    deleteCacheByTag('brands_offers_wifi_' . $brandID);

    return $request->getStatusCode();
}

if (!function_exists('safeJsonParser')) {
    function safeJsonParser($object, $assoc = false)
    {
        if ($object) {
            return \GuzzleHttp\json_decode($object, $assoc);
        }

        return [];
    }
}
