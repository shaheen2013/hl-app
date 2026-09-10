<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function tokenUsuario($token)
{
	//Get from cache
    $cacheName = 'tokenUsuarioDLP_' . $token;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
        $con = conectar();
		$token = mysqli_real_escape_string($con, $token);
        $sql="SELECT referrer_tokens.id_usuario, referrer_tokens.id_hotel
		FROM referrer_tokens WHERE token='".$token."' ";
		$row = lectura($sql, $con, false);
        desconectar($con);

		if($row)
            setToCache($cacheName, $row, 31536000);

    }else{
        //Get result from cache
        $row = $cache->get();
    }

	return $row;
}

function obtenerDatosUsuarioId($id)
{
	//Get from cache
    $cacheName = 'obtenerDatosUsuarioIdDLP_' . $id;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
        $con = conectar();
    	$id = mysqli_real_escape_string($con, $id);

        $sql = "SELECT 
                  users.id as id,
                  users.nombre as nombre,
                  CASE WHEN user_facebook.id IS NULL THEN users.img ELSE user_facebook.facebook_img END as img 
                FROM users 
                LEFT JOIN user_facebook ON user_facebook.id_usuario = users.id
                WHERE users.id=$id";

		$row = lectura($sql, $con, false);
        desconectar($con);

		if($row)
            setToCache($cacheName, $row, 31536000);
    }else{
    	//Get result from cache
        $row = $cache->get();
    }
		
	return $row;
}


?>