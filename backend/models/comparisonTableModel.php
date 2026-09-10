<?php

// Include libraries
include_once RUTA_DIR . LIB . 'dashboards_helpers.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';

function getHotelNamesByChain()
{
    global $log;
    $con = conectar(1);
    $chain_id = array_get($_SESSION, 'c_logueado');
    $cacheName = 'hoteles_cadena_hotel_' . $chain_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $sql = "
            SELECT
                ht.id,
                ht.hotelName AS name
            FROM
                hoteles ht
            INNER JOIN
                cadena_hotel ch
            ON
                ch.id_hotel = ht.id
            WHERE
                ch.id_cadena = " . (int)$chain_id;

        $result = lecturaArray($sql, $con);
        if ($result) {
            $tags = array('statistics', 'comparison_statistics');
            setToCache($cacheName, $result, 84600, $tags);
        }
    } else {
        $result = $cache->get();
    }

    return $result;
}
