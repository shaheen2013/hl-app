<?php 
// Libreria para seguridad CURL de acciones del hotel. 
// No bloquear acceso externo, ya que es necesaria para saber si el acceso es externo.

// FX de seguridad que devuelve el GUID a partir de un id de hotel
function obtenerGUIDHotelSec($id_hotel)
{
	$id_hotel = mysqli_real_escape_string(conectar(1), $id_hotel);
	
	//Get from cache
    $cacheName = 'obtenerGUIDHotelSec' . $id_hotel;
    $cache = getFromCache($cacheName);

    if(!$cache){
		$sql = "SELECT guid FROM hotel_guid WHERE id_hotel='".$id_hotel."' ";
		$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
		$row = mysqli_fetch_assoc($rs);
		liberar($rs);

		if($row)
            setToCache($cacheName, $row, 31536000);
    }else{
        //Get result from cache
        $row = $cache->get();
    }
	return $row['guid'];
}

// FX de seguiridad para WS de hotel (1/2) crea una cadena
function seguridadWSHotel($id_hotel=0)
{
	$guid = obtenerGUIDHotelSec($id_hotel);
	$hoy = date("Ymd");
	//creamos una cadena con el id hotel + GUID + fecha hoy 
	$wsSec = SHA1($id_hotel.$guid.$hoy);
	return $wsSec;
}

// FX de seguiridad para WS de hotel (2/2) lee una cadena
// Complementa la FX anterior, deshace la cadena para verificar si es correcta
// Devuelve: true / false
function leerSeguridadWSHotel($id_hotel=0, $wsSec='')
{
	if($id_hotel!=0){
		$wsSec2 = seguridadWSHotel($id_hotel);
		if($wsSec == $wsSec2)
		{
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
?>