<?php 

//Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function getAffiliredId ($guid)
{
	//Get from cache
    $cacheName = 'getAffiliredId_' . $guid;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
        $con = conectar();
        $guid = mysqli_real_escape_string($con, $guid);
        $sql = "SELECT affilired_id FROM hotel_guid WHERE guid = '".$guid."'";
		$row = lectura($sql, $con);

		if($row){
            $tags = array('hotel', 'hotel_affilired', 'hotel_affilired_'.$guid);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    }else{
    	//Get result from cache
        $row = $cache->get();
    }

	return ($row['affilired_id']);
}