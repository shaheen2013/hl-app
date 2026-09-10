<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Get promocode info
function getPromocode($promoCode)
{
    $con = conectar();
    $promoCode = mysqli_real_escape_string($con, $promoCode);
    $sql = "SELECT transaction, users.nombre, id_oferta, id_hotel
            FROM oferta_referral_token
            LEFT JOIN users ON users.id = oferta_referral_token.id_usuario
            WHERE token = '$promoCode'";
    $row = lectura($sql, $con);
    return $row;
}

//Get offer info
function getOffer($id, $lang)
{
    //Get from cache
    $cacheName = 'getOffer_HLPCV_' . $id . '_' . $lang;
    $cache = getFromCache($cacheName);

    if(!$cache) 
    {
        $con    = conectar();
        $id     = mysqli_real_escape_string($con,$id);
        $lang   = mysqli_real_escape_string($con,$lang);
        
        $sql = "SELECT nombre FROM hotel_oferta_lang WHERE id_oferta = '$id' AND lang = '$lang'";
        $row = lectura($sql, $con);
        if($row)
        {
            $tags = array ('hotel', 'hotel_oferta', 'oferta_'.$id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    }else{
        $row = $cache->get();
    }  

    return $row['nombre'];
}

//Get langs oferta
function getOfferLangs($id)
{
    $langs = [];
    
    //Get from cache
    $cacheName = 'getOfferLangs_HLPV_' . $id;
    $cache = getFromCache($cacheName);

    if(!$cache) 
    {
        $con = conectar();
        $id = mysqli_real_escape_string($con, $id);
        $sql = "SELECT lang FROM hotel_oferta_lang WHERE lang_ok = 1 AND id_oferta = $id";
        $rows = lecturaArray($sql, $con);

        foreach($rows as $row){
            $langs[] = $row['lang'];
        }

        if($langs)
        {
            $tags = array ('hotel', 'hotel_oferta', 'oferta_'.$id);
            setToCache($cacheName, $langs, 31536000, $tags);
        }
    }else{
        $langs = $cache->get();
    }
    
    return $langs;
}

//Fx para devolver el parametro get del hotel para leer el promoBE de la URL
function getParamBE($id_hotel)
{
    //Get from cache
    $cacheName = 'getParamBE_HLPCV_' . $id_hotel;
    $cache = getFromCache($cacheName);

    if(!$cache) 
    {
        $con = conectar();
        $id_hotel = mysqli_real_escape_string($con, $id_hotel);
        $sql = "SELECT booking_engines.getParam 
        FROM hoteles 
        INNER JOIN booking_engines ON booking_engines.id=hoteles.booking_engine
        WHERE hoteles.id = '".$id_hotel."' LIMIT 1";
        $row = lectura($sql, $con);
        
        if($row)
        {
            $tags = array ('hotel', 'hotel_booking_info_'.$id_hotel);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    }else{
        $row = $cache->get();
    }
    
    return $row['getParam'];
}