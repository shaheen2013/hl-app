<?php
//Can´t access directly to this file
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.'; exit; }
//Check token
function obtenerTokenShareUsuario($id_usuario, $id_hotel)
{
    $con = conectar(1);
    $id_usuario = mysqli_real_escape_string($con, $id_usuario);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    desconectar($con);

    //Get from cache
    $cacheName = 'obtenerTokenShareUsuario_' . $id_usuario . '_' . $id_hotel;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
        $sql = "SELECT token 
        FROM referrer_tokens
        WHERE referrer_tokens.id_usuario='".$id_usuario."' AND referrer_tokens.id_hotel='".$id_hotel."' 
        ORDER BY referrer_tokens.id DESC LIMIT 1";
        $row = lectura($sql);

        if($row)
            setToCache($cacheName, $row, 31536000);
    }else{
        //Get result from cache
        $row = $cache->get();
    }

    return $row['token'];
}