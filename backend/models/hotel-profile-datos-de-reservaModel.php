<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Actualizar los datos de la reserva
function actualizarDatosReserva($email, $telefono, $bookingEngine, $promoCodeParam)
{

    $sql = "UPDATE hoteles SET emailReserva='" . $email . "', telefonoReservas='" . $telefono . "', promo_code_param='" . $promoCodeParam . "', booking_engine='$bookingEngine'  WHERE id='" . $_SESSION['h_logueado'] . "' ";
    mysqli_query(conectar(), $sql) or die("ERROR Insert hotel" . mysqli_error());

    //Borrar cache
    deleteCacheByTag('hotel_booking_info_' . $_SESSION['h_logueado']);
    // deleteCacheByKey('obtenerWebsiteReservaHotel_'. $_SESSION['h_logueado']);
}


function insertHotelCountryLangUrl($hotel_id, $country_lang_id, $url =''){
    $sql = "INSERT INTO hotel_country_lang_url(hotel_id, country_lang_id, url, active)
 VALUES ($hotel_id, $country_lang_id,'$url' ,1) 
 ON DUPLICATE KEY UPDATE   active=1";
    return escritura($sql);
}

function updateHotelCountryLangUrl($hotel_id, $country_lang_id, $url, $active=1){
    $sql = "UPDATE hotel_country_lang_url SET ";
    if($url!=''){
        $sql.=" url = '$url',";
    }
    $sql.="active=$active where hotel_id=$hotel_id and country_lang_id=$country_lang_id";

    return escritura($sql);
}

function getHotelCountryLangUrl($hotel_id){
    $sql = "SELECT hotel_id, country_lang_id, url, active, country   FROM hotel_country_lang_url left join country_langs on hotel_country_lang_url.country_lang_id =country_langs.id  
where hotel_id=$hotel_id and active= 1 order by country asc";
    return lecturaArray($sql);
}

function getCountryLangs($hotel_id =null){
    $sql = "SELECT * FROM country_langs ";
    if($hotel_id!=null){
       $sql.="Where id in (select country_lang_id from hotel_country_lang_url where hotel_id=$hotel_id)";
    }
    return lecturaArray($sql);
}
function obtenerDatosHotelReserva($id_hotel)
{
    $sql = "SELECT emailReserva, telefonoReservas, booking_engine, promo_code_param FROM hoteles
	WHERE id='" . $id_hotel . "' ";
    $rs = mysqli_query(conectar(), $sql);
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    return ($row);
}

//Get all booking engines
function getAllBookingEngines()
{
    $sql = "SELECT id, name FROM booking_engines ORDER BY `name` ASC";
    $rs = mysqli_query(conectar(1), $sql);
    $row = mysqli_fetch_all($rs, MYSQLI_ASSOC);
    liberar($rs);
    return ($row);
}

//onboarding
function hotelProfile2Onboarding($id_hotel)
{
    //update para el onboarding
    $sql2 = ("UPDATE onboarding SET booking_info = '1' WHERE id_hotel =" . $id_hotel );
    $query2 = mysqli_query(conectar(), $sql2);
}



?>