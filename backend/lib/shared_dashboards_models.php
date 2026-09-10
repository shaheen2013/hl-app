<?php
use Carbon\Carbon;

function getTotalUsers($datesSearch, $chainSearch)
{
    global $log;
    $con = conectar(1);
    $hotel_id = array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel'));
    $chain_id = $chainSearch ? $_SESSION['c_logueado'] : NULL;
    $hotel_ids = NULL;

    if ($datesSearch['rangeStart'])
        $datesQuery = getSystemDatesQuery('DATE(fecha)', $datesSearch);

    $cacheName = createCacheName('Total_users_from', $chain_id, $hotel_id, $datesSearch);
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT count(DISTINCT id_usuario) AS  user_total FROM user_hotels ";
        $sql .= isset($chain_id) ? "WHERE id_cadena = $chain_id" : "WHERE id_hotel = $hotel_id";
        $sql .= !empty($datesQuery) ? ' AND ' . $datesQuery : '';
        $result = lectura($sql, $con);
        if ($result) {
            $tags = array('statistics', 'shared_statistics');
            setToCache($cacheName, $result, 300, $tags);
        }
    } else {
        $result = $cache->get();
    }
    return $result['user_total'];
}

function getHotelsIdsFromChain($chain_id)
{
    global $log;
    $cacheName = 'hotels_for_chain_' . $chain_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $sql = "SELECT id_hotel FROM cadena_hotel WHERE id_cadena = $chain_id";
        $result = lecturaArray($sql, $con);

        if ($result) {
            $result = array_flatten($result);
            $result = implode(",", $result);
            $tags = array('statistics', 'shared_statistics');
            setToCache($cacheName, $result, 300, $tags);
        }

    } else {
        $result = $cache->get();
    }
    return $result;
}

function getMultipliers()
{
    //Get from cache
    $con = conectar(1);
    $cacheName = 'marketing_multipliers';
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT impressions_percents, single_click_price, thousand_impressions_price FROM marketing_multipliers";
        $row = lectura($sql, $con);
        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }
    } else {
        $row = $cache->get();
    }
    return $row;
}

function getHotelPermissions ($fields, $hotel_id) {
    $sql = "SELECT ".implode(',', $fields) ." FROM permisos_hoteles WHERE id_hotel=$hotel_id";
    return lectura($sql);
}

function insertOnDuplicateKey($table, $table_name, $con)
{
    $fields = "";
    $values = [];
    $duplicates = [];

    foreach ($table as $item) {
        $values[] = "('" . implode("', '", $item) . "')";

        if (empty($fields)) {
            $fields = '(`' . implode('`, `', array_keys($item)) . '`)';
        }

        if (empty($duplicates)) {
            foreach ($item as $key => $value) {
                $duplicates[] = "`" . $key . "` = VALUES(`" . $key . "`)";
            }
        }
    }

    $sql = "
        INSERT INTO
            {$table_name}
        {$fields}
        VALUES
        " . implode(', ', $values) . "
        ON DUPLICATE KEY UPDATE
        " . implode(', ', $duplicates);

    escritura($sql, $con);
}

function getBookingEngineIntegrated($hotel_id)
{
    $con = conectar(1);
    
    $sql = "SELECT 
                booking_engines.id
            FROM hoteles
            LEFT JOIN booking_engines on booking_engines.id = hoteles.booking_engine
            WHERE hoteles.id = '$hotel_id'";

    $row = lectura($sql,$con);

    return array_get($row, 'id');
}

function getDateRangeStart() {
    return getDateFormat($_SESSION['rangeStart']) ?? date('Y-m-d', 0);
}

function getDateRangeEnd(){
    return getDateFormat($_SESSION['rangeEnd']) ?? Carbon::tomorrow()->format("Y-m-d");
}

