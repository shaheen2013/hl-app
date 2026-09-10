<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function getOffers($hotel_id)
{
    $sql = "SELECT hotel_oferta.id, 
        case when oferta_lang.nombre is null 
        then   oferta_en.nombre 
        else oferta_lang.nombre end AS nombre 
	    FROM hotel_oferta
	    LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
        LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userLang'] . "'
	    WHERE estado!='0' AND";
    if (hotelDeCadena($hotel_id)) {
        $id_cadena = hotelIdCadena($hotel_id);
        $sql .= " (id_hotel='" . $hotel_id . "' OR id_cadena='" . $id_cadena . "')";
    } else {
        $sql .= " id_hotel='" . $hotel_id . "'";
    }
    $sql .= " ORDER BY nombre ASC";
    $rs = lecturaArray($sql);
    return $rs;
}
function obtenerDatosChainDetails($chain_id)
{
    $sql = "SELECT 
                nombre,
                descripcion,
                logo, 
                email_contacto AS email,
                telefono_contacto AS numero,
                stay_time,
                loyalty_min_visits
            FROM cadena WHERE id=$chain_id";

    return lectura($sql);
}

function updateChainStayTime($chain_id,$stay_time)
{
    global $log;

    $chain_id = (int)$chain_id;
    $stay_time = (int)$stay_time;

    $sql = "
UPDATE cadena
SET stay_time = $stay_time
WHERE id=$chain_id";
    $log->debug($sql);

    return escritura($sql);
}

?>
