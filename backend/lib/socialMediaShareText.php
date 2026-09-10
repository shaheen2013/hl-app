<?php
include_once LIB . 'obtenerdatosHotel.php';

//Fx para obtener el texto customizado de share según hotel/userLang/tipoShare
function getSocialMediaShareText($id_hotel, $userLang, $shareType)
{
	//$shareType = getShareTypeName($shareType);// id share to text (2->'pre')
	if(!empty($userLang) && idHotelCorrecto($id_hotel) && ( $shareType=='pre' || $shareType=='stay' ||  $shareType=='post') )
	{
		$stayShareText = obtenerSMTextShareIdioma1Share($id_hotel, $userLang, $shareType);
		if(!empty($stayShareText))
		{
			return $stayShareText;
		}else{
			// No hay texto en el idioma del usuario, miramos si lo hay en idioma default 'en'
			$stayShareText = obtenerSMTextShareIdioma1Share($id_hotel, 'en', $shareType);
			if(!empty($stayShareText))
			{
				return $stayShareText;
			}else{
				return null;
			}
		}
	}else{
		return null;
	}	
}

//Fx para obtener el texto customizado de share de una pantalla concreta (pre, stay, share)
function obtenerSMTextShareIdioma1Share($id_hotel, $userLang, $shareType)
{
	//Get from cache
	$cacheName = 'hotelDefaultShareText_' . $id_hotel . '_' . $userLang . '_' . $shareType;
    $cache = getFromCache($cacheName);

    if(!$cache) 
    {
    	$con = conectar(1);
	    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
	    $userLang = mysqli_real_escape_string($con, $userLang);
	    $shareType = mysqli_real_escape_string($con, $shareType);
	    desconectar($con);

		$sql = "SELECT $shareType FROM hotel_share_text WHERE id_hotel=$id_hotel AND lang='$userLang' ";
		$row = lectura($sql);

		if($row){
			$tags = array("hotel","hotel_facebook","hotel_facebook_".$id_hotel);
            setToCache($cacheName, $row, 31536000, $tags);
		}
	}else{

        $row = $cache->get();
    }

	return $row[$shareType];
}

// FX para transformar id de share en nombre
// 2 -> 'pre'
/*function getShareTypeName($id_shareType)
{
	$id_shareType = mysqli_real_escape_string(conectar(1), $id_shareType);

	$sql = "SELECT sys FROM tipos_share WHERE id='$id_shareType' ";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['sys'];
}*/
?>