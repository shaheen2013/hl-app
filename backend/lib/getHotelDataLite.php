<?php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Get hotel data
function getHotelDataLite($id)
{
    //Get from cache
    $cacheName = 'hotelDatalite_' . $id;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
        $con = conectar();
        $id = mysqli_real_escape_string($con, $id);
        $sql = "SELECT hoteles.hotelName, hoteles.logo, hoteles.fotoBg, hoteles.city, hoteles.email, hoteles.id
                FROM hoteles 
                WHERE hoteles.id = $id";
        $row = lectura($sql, $con, false);
        desconectar($con);
        if($row)
        {
            $tags = array('hotel', 'hotel_profile', 'hotel_profile_'.$id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    }else{
        //Get result from cache
        $row = $cache->get();
    }
   
    return $row;
}

