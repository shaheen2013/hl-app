<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

function obtenerDatosHotel($hotel_id)
{
    $hotel_id = mysqli_real_escape_string(conectar(), $hotel_id);
    $sql = "SELECT email, password FROM hoteles WHERE id='" . $hotel_id . "' ";
    $rs = mysqli_query(conectar(), $sql);
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    return $row;
}

function borrarDatosHotel($hotel_id)
{
    $sql = "UPDATE hoteles SET email='', password='' WHERE id='$hotel_id' ";
    mysqli_query(conectar(), $sql);
}

function obtenerIdCadena($hotel_id)
{
    $sql = "SELECT id_cadena FROM cadena_hotel WHERE id_hotel='$hotel_id' ";
    $row = lectura($sql);
    return $row['id_cadena'];
}

function obtenerDatosChainDetails($chain_id)
{
    $sql = "SELECT 
    cadena.nombre,
    cadena.descripcion,
    cadena.logo, 
    cadena.email_contacto AS email,
    cadena.telefono_contacto AS numero,
    cadena.stay_time,
    cadena.loyalty_min_visits,
    brands.background_color
    FROM cadena
    LEFT JOIN brands ON cadena.id = brands.chain_id
    WHERE cadena.id = $chain_id;
    ";

    return lectura($sql);
}

function borrarLogoAnterior($chain_id)
{
    $sql = "SELECT logo FROM cadena WHERE id='$chain_id' ";
    $row = lectura($sql);
    if ($row['logo'] != '') {
        $ruta = DIR_IMG_FICHA_CADENA . $chain_id . "/logo/";
        if (file_exists($ruta . $row['logo'])) {
            unlink($ruta . $row['logo']);
            borrarThumbnail($ruta, $row['logo']);
        }
    }
}

function guardarNombreLogo($chain_id, $nombreImg)
{
    $logoUrl = SECURE_BASE_PATH . DIR_IMG_FICHA_CADENA . $chain_id . '/logo/med_' . $nombreImg;
    $sql = "UPDATE cadena SET logo=' $logoUrl' WHERE id='$chain_id' ";
    escritura($sql);
}
function insertChainCountryLangUrl($chain_id, $country_lang_id, $url =''){
    $sql = "INSERT INTO hotel_country_lang_url(chain_id, country_lang_id, url, active)
 VALUES ($chain_id, $country_lang_id,'$url' ,1) 
 ON DUPLICATE KEY UPDATE   active=1";
    return escritura($sql);
}

function updateChainCountryLangUrl($chain_id, $country_lang_id, $url, $active=1){
    $sql = "UPDATE hotel_country_lang_url SET ";
    if($url!=''){
        $sql.=" url = '$url',";
    }
    $sql.="active=$active where chain_id=$chain_id and country_lang_id=$country_lang_id";

    return escritura($sql);
}

function getChainCountryLangUrl($chain_id){
    $sql = "SELECT chain_id, country_lang_id, url, active, country   FROM hotel_country_lang_url left join country_langs on hotel_country_lang_url.country_lang_id =country_langs.id  
where chain_id=$chain_id and active= 1 order by country asc";
    return lecturaArray($sql);
}

function getCountryLangs($chain_id =null){
    $sql = "SELECT * FROM country_langs ";
    if($chain_id!=null){
        $sql.="Where id in (select country_lang_id from hotel_country_lang_url where chain_id=$chain_id)";
    }
    return lecturaArray($sql);
}


?>