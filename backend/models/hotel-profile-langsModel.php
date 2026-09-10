<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'idiomas.php';

//return available langs
function getLangList($tipo)
{
	$langs = array();
	//SELECT
	$sql = "SELECT * FROM `lang`
			WHERE `".$tipo."` = '1'
			ORDER BY `country` ASC";
	//QUERY
	$rs = mysqli_query (conectar(), $sql);
	//CREATE ARRAY
	while ($row = mysqli_fetch_assoc($rs)){
		$langs[] = $row;
	};

	liberar($rs);
	return $langs;
}

function borrarLangsHotel($id_hotel)
{
	$sql = "DELETE FROM lang_hotel WHERE id_hotel='".$id_hotel."' ";
	mysqli_query (conectar(), $sql);
}

//Langs (varios) de ofertas hotel
function guardarLangsHotel($id_hotel, $langs)
{
    $id_hotel = (int)$id_hotel;
	borrarLangsHotel($id_hotel);

	if (!empty($langs) && $id_hotel) {
		$values = [];

		foreach (array_unique($langs) as $lang) {
		    $lang = (int)$lang;

		    if ($lang) {
               $values[] = "({$lang}, {$id_hotel})";
            }
		}

		if (!empty($values)) {
            $sql = "INSERT INTO lang_hotel (id_lang, id_hotel) VALUES " . implode(',', $values);
            mysqli_query (conectar(), $sql);
        }
	}

	//Borrar cache
	deleteCacheByTag('hotel_langs_' . $id_hotel);
}

//Lang (1) del hotel. Idioma en el que ve la plataforma
function guardarLangHotel($id_hotel, $id_lang)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$lang = mysqli_real_escape_string(conectar(), $id_lang);

	$sql2 = "SELECT lang FROM lang WHERE id='".$id_lang."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	//Miramos si el idioma seleccionado lo tenemos en la plataforma (default 'en')
	$idioma = mirarIdiomaPlataforma($row2['lang']);
	$sql = "UPDATE hoteles SET lang='".$row2['lang']."' WHERE id='".$id_hotel."' ";
	mysqli_query (conectar(), $sql) or die (mysqli_error());

	//Borrar cache
	deleteCacheByTag('hotel_default_lang_'.$id_hotel);

	return $idioma;
}

//Devuelve id numerico
function obtenerIdLangsHotel($id_hotel)
{
    $id_hotel = sqlEscape($id_hotel);
    $langs = array();
    $sql = "SELECT id_lang
    FROM lang_hotel
    WHERE lang_hotel.id_hotel='" . $id_hotel . "' ";
    $rs = mysqli_query(conectar(), $sql);

    while ($row = mysqli_fetch_assoc($rs)) {
        $langs[$row['id_lang']] = '1';
    }
    liberar($rs);

    return $langs;
}
?>
