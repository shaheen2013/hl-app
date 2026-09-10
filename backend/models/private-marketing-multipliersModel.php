<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//if not logged in, go back
if (empty($_SESSION['private'])) {
    header('Location: /');
}

function changeMultipliers($single, $thousand, $percentage){
    $single = mysqli_real_escape_string(conectar(), $single);
    $thousand = mysqli_real_escape_string(conectar(), $thousand);
    $percentage = mysqli_real_escape_string(conectar(), $percentage);

    $sql = "UPDATE marketing_multipliers SET impressions_percents = $percentage, single_click_price = $single , thousand_impressions_price = $thousand";
    //Delete cache
    deleteCacheByTag('marketing_multipliers');
    $result = mysqli_query (conectar(), $sql);
    return $result;
}

function getMultipliers(){

    //Get from cache
    $cacheName = 'marketing_multipliers';
    $cache = getFromCache($cacheName);

    if(!$cache){

        $sql = "SELECT impressions_percents, single_click_price, thousand_impressions_price FROM marketing_multipliers";
        $rs = mysqli_query(conectar(1), $sql);
        $row = mysqli_fetch_assoc($rs);
        liberar($rs);

        if($row){
            $tags = array ('marketing_multipliers');
            setToCache($cacheName, $row, 31536000, $tags);
        }

    }else{

        $row = $cache->get();

    }

    return $row;

}