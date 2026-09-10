<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Get GUID by hotel ID
function unifiGetGuidByHotelId($siteId)
{
    //Get from cache
    $cacheName = 'unifiHotel_' . str_replace(":", "-", str_replace("/", "-", $siteId));
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar();
        $siteId = mysqli_real_escape_string($con, $siteId);
        $sql = "SELECT hotel_guid.guid, hotel_oferta_stay.unifi_site_id 
                FROM hotel_guid INNER JOIN hotel_oferta_stay ON hotel_oferta_stay.unifi_site_id = '$siteId' 
                WHERE hotel_oferta_stay.id_hotel = hotel_guid.id_hotel";
        $row = lectura($sql, $con, true);

        if ($row){
            $tags = array('unifiSiteId');
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return array_get($row, 'guid');
}