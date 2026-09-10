<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once RUTA_DIR . LIB . 'sanitize.php';

//Generamos la URL del Hotel a partir de su id
function obtenerUrlGUIDCadena($id_cadena)
{
    $id_cadena = sqlEscape($id_cadena);

    $sql = "SELECT nombre, guid
	FROM cadena 
	INNER JOIN cadena_guid ON cadena_guid.id_cadena=cadena.id
	WHERE cadena.id='" . $id_cadena . "' ";
    $row = lectura($sql);
    global $urlTree;
    $urlHotel = BASE_PATH . $urlTree['cadena-hotelera'] . '/' . string_sanitize($row['nombre']) . '/' . $row['guid'];
    return $urlHotel;
}

function obtenerIdCadenaGUID($guid)
{
    //Get from cache
    $cachedGuid = getFromCache('idByGuidCadena_' . $guid);

    //If not in cache
    if (!$cachedGuid) {
        $guid = sqlEscape($guid);

        $sql = "SELECT id_cadena FROM cadena_guid WHERE guid='" . $guid . "' ";
        $row = lectura($sql);
        $id_cadena = $row['id_cadena'];

        if ($id_cadena) {
            //Store it in cache
            $array = [
                "id_cadena" => $id_cadena
            ];

            setToCache('idByGuidCadena_' . $guid, $array, 31536000);
        }
    } else {
        //Get result from cache
        $result = $cachedGuid->get();
        $id_cadena = $result['id_cadena'];
    }

    return $id_cadena;
}

function obtenerGUIDCadena($id)
{
    //Get from cache
    $cache = getFromCache('guidByIdCadena_' . $id);

    //If not in cache
    if (!$cache) {
        $id = sqlEscape($id);
        $sql = "SELECT guid FROM cadena_guid WHERE id_cadena='" . $id . "' ";
        $row = lectura($sql);
        $guid = $row['guid'];

        if ($guid) {
            //Store it in cache
            $array = [
                "guid" => $guid
            ];

            setToCache('guidByIdCadena_' . $id, $array, 31536000);
        }
    } else {
        //Get result from cache
        $result = $cache->get();
        $guid = $result['guid'];
    }

    return $guid;
}

//Hoteles de cadena. Solo id, nombre y nombre_san
function obtenerHotelesCadenaBasico($id_cadena)
{
    $id_cadena = sqlEscape($id_cadena);

    $sql = "SELECT DISTINCT hoteles.id, hoteles.hotelName AS nombre 
	FROM cadena_hotel
	INNER JOIN hoteles ON hoteles.id=cadena_hotel.id_hotel
	WHERE cadena_hotel.id_cadena='" . $id_cadena . "' ORDER BY hoteles.hotelName ASC";
    $rs = mysqli_query(conectar(), $sql) or die(mysqli_error());
    $i = 0;
    while ($row = mysqli_fetch_assoc($rs)) {
        foreach ($row as $key => $valor) {
            if ($key == 'nombre') {
                $hoteles[$i][$key] = $valor;
                $hoteles[$i][$key . '_san'] = string_sanitize($valor);
            } else {
                $hoteles[$i][$key] = $valor;
            }
        }
        $i++;
    }
    return $hoteles;
}

// FX para obtener datos basicos de una cadena
function obtenerDatosBasicosCadena($id_cadena)
{
    $id_cadena = sqlEscape($id_cadena);
    $sql = "SELECT nombre FROM cadena WHERE cadena.id='" . $id_cadena . "' ";
    $row = lectura($sql);
    return $row;
}

/**
 * Check if this hotel belongs to this chain
 * @param $id_hotel
 * @param $id_chain
 * @return bool
 */
function hotelBelongsToChain($id_hotel, $id_chain)
{
    global $log;
    $hotels_array = obtenerHotelesCadenaBasico($id_chain);
    if ($hotels_array) {
        $ids_hotels = array();
        foreach ($hotels_array as $hotel) {
            $ids_hotels[] = $hotel['id'];
        }
    }
    if (!in_array($id_hotel, $ids_hotels))
        return false;

    //$log->debug(session_id() . ' -> This hotel belongs to this chain');
    return true;
}

/**
 * Get chain websites
 * @param id_chain
 * @return array
 */
function getChainWebsite($id_chain, $userLang){

    $con = conectar(1);
    $id_chain = mysqli_real_escape_string($con, $id_chain);

    $sql = "SELECT url FROM brand_url_language 
    INNER JOIN brands ON brand_url_language.brand_id = brands.id 
    INNER JOIN cadena ON brands.chain_id = cadena.id
    INNER JOIN languages ON brand_url_language.language_id = languages.id
    WHERE cadena.id = $id_chain AND languages.name = '$userLang'";

    $row = lectura($sql);
    return $row['url'];
}