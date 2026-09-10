<?php
function hotelBookingShareData($id)
{
	//Get from cache
    $cacheName = 'hotelBookingShareData_' . $id;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
    	$con = conectar();
    	$id = mysqli_real_escape_string($con, $id);
    	$sql = "SELECT hoteles.email AS email_hotel, hoteles.hotelName, hoteles.logo, hoteles.fotoBg, 
		hoteles.website AS hotelUrl, hoteles.place_name,
		hoteles.twitterAccount, hoteles.iframe_style";
		$sql .= " FROM hoteles 
		LEFT JOIN cadena_hotel ON cadena_hotel.id_hotel='".$id."'
		WHERE hoteles.id='".$id."' ";
		$row = lectura($sql, $con, false);
        desconectar($con);
		if($row){
            $tags = array('hotel','hotel_profile','hotel_profile_'.$id, 'hotel_booking_info_'.$id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    }else{
    	//Get result from cache
        $row = $cache->get();
    }

	return $row;
}

function hotelBookingOfertaStay($id_hotel)
{
	//Get from cache
    $cacheName = 'hotelBookingOfertaStay_' . $id_hotel;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
    	$con = conectar();
    	$id_hotel = mysqli_real_escape_string($con, $id_hotel);
		$sql = "SELECT
		case when oferta_lang.nombre is null 
        then   oferta_en.nombre 
        else oferta_lang.nombre end AS nombre_oferta, hotel_oferta.img, hotel_oferta.id
		FROM hotel_oferta_prestay
		INNER JOIN hotel_oferta ON hotel_oferta.id = hotel_oferta_prestay.id_oferta
		LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
        LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userLang'] . "'
		WHERE hotel_oferta_prestay.id_hotel ='".$id_hotel."' ";
		$row = lectura($sql, $con, false);
        desconectar($con);

		if($row){
            $tags = array("hotel","hotel_goals","hotel_goals_".$id_hotel);
            setToCache($cacheName, $row, 31536000, $tags);
        }
	}else{
		//Get result from cache
        $row = $cache->get();
	}

	return $row;
}
?>
