<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LIB . 'cache.php';

function asignarGoalOferta($id_oferta, $id_goal)
{
    $sql = "UPDATE referral_goal SET id_oferta = '$id_oferta' WHERE id = '$id_goal'";
    mysqli_query(conectar(), $sql);
}

//Get iframe state (on / off)
function getIframeState($hotel_id, $iframeType)
{

    $sql = "SELECT $iframeType FROM hoteles WHERE id='$hotel_id'";
    $rs = mysqli_query(conectar(1), $sql) or die(mysqli_error());
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    return $row[$iframeType];

}

function selectWifiProviders()
{
    $sql = "SELECT id, name FROM wifi_providers";
    $rs = mysqli_query(conectar(1), $sql) or die(mysqli_error());
    $i = 0;
    while ($row = mysqli_fetch_assoc($rs)) {
        $wifiProviders[$i]['id'] = $row['id'];
        $wifiProviders[$i]['name'] = $row['name'];
        $i++;
    }
    liberar($rs);
    return $wifiProviders;
}

// Guardar proveedor de wifi de un hotel
// $wifi_id: id proveedor wifi
function guardarWifiProvider($hotel_id, $wifi_id)
{
    if ($wifi_id) {
        $wifi_id = mysqli_real_escape_string(conectar(), $wifi_id);
        $sql = "INSERT INTO hotel_wifi_integrations (hotel_id, wifi_id) VALUES ('$hotel_id', '$wifi_id')
		ON DUPLICATE KEY UPDATE wifi_id='$wifi_id' ";
        mysqli_query(conectar(), $sql) or die(mysqli_error());
    } else {
        //Borrar wifi provider
        $sql = "DELETE FROM hotel_wifi_integrations WHERE hotel_id='$hotel_id' ";
        mysqli_query(conectar(), $sql) or die(mysqli_error());
        $sql = "DELETE FROM hotel_oferta_stay WHERE id_hotel='$hotel_id' ";
        mysqli_query(conectar(), $sql) or die(mysqli_error());
    }
    deleteCacheByKey('wifiStay_' . $hotel_id);
    deleteCacheByKey('wifiProv_' . $hotel_id);
}

//FX para obtener todas las ofertas de referral del hotel para pre-stay, stay, post-stay
function getAvailableOffers($hotel_id)
{
    global $log;
    $ofertas = array();
    $sql = " SELECT                  hotel_oferta.id,              
 CASE WHEN oferta_lang.nombre IS NULL 
 THEN  oferta_en.nombre 
 ELSE oferta_lang.nombre END AS nombre     
 FROM hotel_oferta  
  LEFT JOIN hotel_oferta_lang AS oferta_en ON hotel_oferta.id = oferta_en.id_oferta  AND oferta_en.lang='en' 
  LEFT JOIN hotel_oferta_lang AS oferta_lang ON hotel_oferta.id = oferta_lang.id_oferta AND oferta_lang.lang='" . $_SESSION['userLang'] . "' 
  WHERE estado!='0' AND ";
    if (hotelDeCadena($hotel_id)) {
        $chain_id = hotelIdCadena($hotel_id);
        $sql .= " (id_hotel='$hotel_id' OR id_cadena='$chain_id')";
    } else {
        $sql .= " id_hotel='$hotel_id'";
    }
    $sql .= "GROUP by nombre HAVING nombre!='' ORDER BY nombre ASC;";

    $rs = mysqli_query(conectar(), $sql);
    $i = 0;
    if ($rs) {
        while ($row = mysqli_fetch_assoc($rs)) {
            $ofertas[$i]['id'] = $row['id'];
            $ofertas[$i]['nombre'] = $row['nombre'];
            $i++;
        }
        liberar($rs);
    }
    return $ofertas;
}

// NEW OFFERS

function getHotelOffers($hotel_id)
{

    //if hotel is from chain then chain_id else null
    $chain_id = chainID();

    $sql = "SELECT
                hotel_oferta_id as hotel_offer_id,
                offers.id,
                offers.products_id,
                offers.offer_triggers_id,
                offers.offer_platforms_id,
                offers.active,
                duration,
                products.producto as product_name,
                offer_triggers.type as offer_triggers_name,
                offer_platforms.type as offer_platforms_name
            FROM hotel_oferta
           	RIGHT JOIN offers ON offers.hotel_oferta_id = hotel_oferta.id 
            LEFT JOIN products ON offers.products_id = products.id 
            LEFT JOIN offer_platforms ON offers.offer_platforms_id = offer_platforms.id
            LEFT JOIN offer_triggers ON offers.products_id = offer_triggers.id  
            WHERE hotel_oferta.id_hotel = $hotel_id ";

    $chain_sql = "OR hotel_oferta.id_cadena = $chain_id";

    //concat to $sql the chain clause if hotel is part of a chain
    $sql = $chain_id ? $sql . $chain_sql : $sql;


    return lecturaArray($sql);
}

function getOfferPlatforms($hotel_id)
{
    $sql = "SELECT * FROM offer_platforms";
    return lecturaArray($sql);
}

function getOfferTriggers($hotel_id)
{
    $sql = "SELECT * FROM offer_triggers";
    return lecturaArray($sql);
}