<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function getOfferById($offer_id){
	$con = conectar();
	$offer_id = mysqli_real_escape_string($con, $offer_id);
	$sql = "SELECT 
				id,
				id_tipo_oferta AS offertype,
				id_categoria AS category,
				id_subcategoria AS subCategory, 
				adq_ret AS offerMethod,
				inicio,
				fin,
				img AS foto,
				estado,
				booking_engine_code 
			FROM hotel_oferta 
			WHERE id='$offer_id'";

	$row = lectura($sql , $con);

	return $row;
}

function getOfferLangById($offer_id, $lang){
	$con = conectar();
	$offer_id = mysqli_real_escape_string($con, $offer_id);
	$lang = mysqli_real_escape_string($con, $lang);
	$sql = "SELECT
				lang,
				nombre, 
				descripcion,
				condiciones
			FROM hotel_oferta_lang
			WHERE id_oferta='$offer_id' AND lang='$lang'
	";
	return lectura($sql, $con);
};


function saveOffer($id_hotel , $id_cadena, $start, $end, $img, $booking_engine_code){
	$con = conectar();

	$id_hotel = mysqli_real_escape_string($con, $id_hotel);
	$id_cadena = mysqli_real_escape_string($con, $id_cadena);
	$start = mysqli_real_escape_string($con, $start);
	$end = mysqli_real_escape_string($con, $end);
	$img = mysqli_real_escape_string($con, $img ?? '');
	$booking_engine_code = mysqli_real_escape_string($con, $booking_engine_code);

	if ($img == DIR_IMG . 'img-placeholder.jpg') {
		$img = "";
	}

	$fecha_creacion = datetimeHoy();

    $sql = "INSERT INTO hotel_oferta (
                id_hotel,
                id_cadena,
                inicio,
                fin,
                img,
                fecha_creacion,
                booking_engine_code,
                adq_ret)
                VALUES ('$id_hotel', '$id_cadena', '$start', '$end', '$img', '$fecha_creacion', '$booking_engine_code', 'ref')";

    $offer_id =  escritura($sql, $con);
    return $offer_id;
}

function saveOfferLang($offer_id, $lang, $name, $description, $conditions){

	global $log;
	
	$con = conectar();

	$offer_id = mysqli_real_escape_string($con, $offer_id);
	$lang = mysqli_real_escape_string($con, $lang);
	$name = mysqli_real_escape_string($con, $name);
	$description = mysqli_real_escape_string($con, $description);
	$conditions = mysqli_real_escape_string($con, $conditions);

	$log->info('CREATING OFFER', array($offer_id, $lang, $name, $description, $conditions));

	$isLangFilled = (int)(
		!empty($name) && !empty($description) && !empty($conditions)
	);

	$sql = "INSERT INTO hotel_oferta_lang (
				id_oferta,
				lang,
				nombre,
				descripcion,
				condiciones,
				lang_ok)
			VALUES ('$offer_id', '$lang', '$name', '$description', '$conditions', $isLangFilled)
			ON DUPLICATE KEY UPDATE 
				lang=VALUES(lang),
				nombre=VALUES(nombre),
				descripcion=VALUES(descripcion),
				condiciones=VALUES(condiciones),
				lang_ok=VALUES(lang_ok)
	";
    deleteCacheByKey('OOR_oferta_' . $offer_id . '_' . $lang);
    deleteCacheByTag('hotel_oferta_' . $offer_id);
	return escritura($sql, $con);

}

function updateBookingEngineCode($offerID, $bookingEngineCode) {
	$con = conectar();

	$offerID = mysqli_real_escape_string($con, $offerID);
	$bookingEngineCode = mysqli_real_escape_string($con, $bookingEngineCode);

	$updatePromocode = "UPDATE hotel_oferta SET booking_engine_code='$bookingEngineCode' WHERE id=$offerID";
	return escritura($updatePromocode, $con);
}


function publishOffer($offer_id, $state){

	$con = conectar();
	$publish_date = datetimeHoy();
	$offer_id = mysqli_real_escape_string($con, $offer_id);
	$sql = "UPDATE hotel_oferta SET estado='$state' , fecha_publicada='$publish_date' WHERE id='$offer_id'";
	return escritura($sql, $con);
}

function getOfferLanguages($hotel_id, $offer_id) {
	$con = conectar();
	$hotel_id = (int)$hotel_id;
	$offer_id = (int)$offer_id;

	$sql = "
        SELECT 
            lang.lang,
            lang.img,
            lang.country,
            hoteles.lang AS langHotel,
            IF(hoteles.lang = lang.lang, 1, 0) AS defaultLang,
            hotel_oferta_lang.id_oferta
        FROM
            lang
        INNER JOIN
            lang_hotel
        ON
            lang.id = lang_hotel.id_lang
        INNER JOIN
            hoteles
        ON
            hoteles.id = lang_hotel.id_hotel
        LEFT JOIN
            hotel_oferta_lang
        ON
            hotel_oferta_lang.id_oferta = {$offer_id} AND
            lang.lang = hotel_oferta_lang.lang
        WHERE
            lang_hotel.id_hotel = {$hotel_id} AND
            lang.content = 1
        GROUP BY
            lang.lang,
            lang.img,
            lang.country,
            hoteles.lang
        ORDER BY
            defaultLang
        DESC";

	return lecturaArray($sql, $con);
}

function obtenerOferta ($id_oferta) {
	$con = conectar();
	$id_oferta = mysqli_real_escape_string($con, $id_oferta);
	$sql = "SELECT 
				id_tipo_oferta AS offertype,
				id_categoria AS category,
				id_subcategoria AS subCategory, 
				adq_ret AS offerMethod,
				inicio,
				fin,
				cupo,
				canjeadas,
				descuento,
				coste,
				requerimientos AS requeriments,
				puntos,
				img AS foto,
				estado,
				moneda,
				booking_engine_code AS bookingEngineCode
			FROM hotel_oferta 
			WHERE id='$id_oferta' AND (estado='0' OR  estado='3' OR estado='6')";

	if(!empty($_SESSION['c_logueado'])) {
		$sql .= " AND (id_hotel='".$_SESSION['h_logueado']."' OR id_cadena='".$_SESSION['c_logueado']."') ";
	}else{
		$sql .= " AND id_hotel='".$_SESSION['h_logueado']."' ";
	}

	$row = lectura($sql);

	foreach ($row as $key=>$value){
		// Guardamos datos en SESSION que son los q inserta la función de públicar
		if ($key=='inicio' || $key=='fin'){
			$arrayOferta[$key] = $_SESSION[$key] = girarFecha($value);
		}else{
			$arrayOferta[$key] = $_SESSION[$key] = $value;
		}
	}

	//Obtenemos los campos de lang (nombre, desc., cond.) en todos los idiomas que tiene la oferta
	$sql2 = "SELECT 
				lang,
				nombre,
				descripcion,
				condiciones,
				lang_ok AS 'check'
			 FROM hotel_oferta_lang
			 WHERE id_oferta='$id_oferta'";

	$row2 = lecturaArray($sql2);

	foreach($row2 as $idioma){
		foreach ($idioma as $key=>$value){
			if($key != 'lang')
			{
				$_SESSION[$idioma['lang']][$key] = $value;
			}
		}
	}

	return $arrayOferta;
}

function obtenerDatosCadena($id_cadena)
{
	$id_cadena = mysqli_real_escape_string(conectar(), $id_cadena);
	$sql = "SELECT id, nombre, email_contacto AS email, logo FROM cadena WHERE id='$id_cadena'";
	$row = lectura($sql);
	return $row;
}

function obtenerOfferMethod($offerMethod)
{
	$con = conectar();
	$offerMethod = mysqli_real_escape_string($con, $offerMethod);
	$sql = "SELECT method_".$_SESSION['userLang']." AS offerMethod FROM offer_method WHERE id_method='$offerMethod' ";
	$row = lectura($sql, $con);
	return $row['offerMethod'];
}

function obtenerOffertype($offerMethod)
{
	$con = conectar();
	$offerMethod = mysqli_real_escape_string($con, $offerMethod);
	$sql = "SELECT tipo_adq_ret_".$_SESSION['userLang']." AS offertype FROM tipos_oferta WHERE id_tipo_oferta='$offerMethod' ";
	$row = lectura($sql, $con);
	return $row['offertype'];
}

function obtenerCategory($category)
{
	$con = conectar();
	$category = mysqli_real_escape_string($con, $category);
	$sql = "SELECT categoria_".$_SESSION['userLang']." AS categoria FROM categoria_oferta WHERE 	id_categoria_oferta='$category' ";
	$row = lectura($sql, $con);
	return $row['categoria'];
}

function obtenerSubCategory($subCategory)
{
	$con = conectar();
	$subCategory = mysqli_real_escape_string($con, $subCategory);
	$sql = "SELECT subcategoria_oferta_".$_SESSION['userLang']." AS subcategoria FROM subcategoria_oferta WHERE id_categoria_oferta='$subCategory' ";
	$row = lectura($sql, $con);
	return $row['subcategoria'];
}

?>
