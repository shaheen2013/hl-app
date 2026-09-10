<?php
/*
* 	FX para obtener (BD/cache) a partir del token los datos necesarios para esta pantalla
*
*	Params:
*		@token (string) token 
*	
*	Return:
*		@result (array) contiene los campos del resultado de la query. Todos a NULL en caso de que el code sea 404
*		@result[code] (string) campo añadido al result de la query para dar info extra
*		code = 200. Consulta devuelve datos		
*		code = 404. Consulta no devuelve nada
*/
function getTokenData($token)
{
    //Get from cache
    $cacheName = 'getTokenPreOfertaData_' . $token;
    $cache     = getFromCache($cacheName);

    if (!$cache) {
        $con   = conectar(1);
        $token = mysqli_real_escape_string($con, $token);

        $sql           = "SELECT pre_oferta_token.id, pre_oferta_token.id_usuario, pre_oferta_token.id_hotel, pre_oferta_token.id_oferta, 
		pre_oferta_token.tipo_oferta, pre_oferta_token.id_origen_oferta,
		 case when oferta_lang.nombre is null 
    then   oferta_en.nombre 
    else oferta_lang.nombre end AS nombre_oferta, 
		hotel_oferta.img AS img_oferta, hotel_oferta.booking_engine_code,
	  	hoteles.hotelName AS nombre_hotel, hoteles.logo AS logo_hotel, users.nombre AS nombre_usuario, hoteles.fotoBg, oferta_lang.lang
		FROM pre_oferta_token 
		LEFT JOIN hotel_oferta ON hotel_oferta.id = pre_oferta_token.id_oferta
		LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
        LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='" . $_SESSION['userLang'] . "'
		LEFT JOIN hoteles ON hoteles.id=pre_oferta_token.id_hotel
	  	LEFT JOIN users ON users.id = pre_oferta_token.id_usuario
		WHERE token='" . $token . "' LIMIT 1";
        $row           = lectura($sql, $con, false);
        $affected_rows = mysqli_affected_rows($con);
        desconectar($con);

        if ($affected_rows == 0) {
            // Generamos un result con los campos de la query con valor NULL
            $arrayParametros = array('id', 'id_usuario', 'id_hotel', 'id_oferta', 'tipo_oferta', 'id_origen_oferta', 'nombre_oferta', 'img_oferta', 'booking_engine_code', 'nombre_hotel', 'logo_hotel');
            foreach ($arrayParametros as $parametro) {
                $result[$parametro] = NULL;
            }
            $result['code'] = '404';
        } else {
            $result         = $row;
            $result['code'] = '200';
        }
        if ($result)
            setToCache($cacheName, $result, 864000); //10 Days
    } else {
        $result = $cache->get();
    }
    return $result;
}

/*
*	FX para borrar un token de creación de oferta a partir de su id
*
*	@id (int) id del token previo a la oferta (tabla pre_oferta_token)
*/
function borrarTokenPreOferta($id)
{
    $sql = "DELETE FROM pre_oferta_token WHERE id=$id ";
    escritura($sql);
}

/**
 * //Check offertypes and return an array of all offers by type
 * @param $offer_type
 * @param $user_id
 * @param $hotel_id
 * @return array|mixed
 */
function CheckIfOfferTypeExists($offer_type, $user_id, $hotel_id)
{
    //Get from cache
    $cacheName = 'user_' . $user_id . '_OfferType_' . $offer_type;
    $cache     = getFromCache($cacheName);
    if (!$cache) {

        $con       = conectar(1);
        $offerType = mysqli_real_escape_string($con, $offer_type);
        $uid       = mysqli_real_escape_string($con, $user_id);
        $hid       = mysqli_real_escape_string($con, $hotel_id);

        $sql = "SELECT id_tipo_share, token, oferta_referral_token.fecha, user_cupones.canjeado FROM oferta_referral_token
                LEFT JOIN user_cupones ON user_cupones.id_usuario = '$uid'
                WHERE oferta_referral_token.id_usuario = '$uid'
                AND id_tipo_share = '$offerType'
                AND id_hotel = '$hid'";

        $row = lecturaArray($sql, $con, false);

        // Para ofertas de cumpleaños, buscar también cupones manuales
        if ($offerType == '10') {
            $sqlBirthday = "SELECT
                '$offerType' as id_tipo_share,
                uc.voucher as token,
                uc.fecha,
                uc.canjeado
                FROM user_cupones uc
                JOIN hotel_oferta ho ON ho.id = uc.id_oferta
                WHERE uc.id_usuario = '$uid'
                AND ho.id_hotel = '$hid'
                AND uc.id NOT IN (
                    SELECT DISTINCT ort.id_cupon
                    FROM oferta_referral_token ort
                    WHERE ort.id_cupon IS NOT NULL
                )";

            $birthdayRow = lecturaArray($sqlBirthday, $con, false);

            if ($birthdayRow && !empty($birthdayRow)) {
                if ($row && !empty($row)) {
                    $row = array_merge($row, $birthdayRow);
                } else {
                    $row = $birthdayRow;
                }
            }
        }

        if ($row) {
            setToCache($cacheName, $row, 300);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }
    return $row;
}

function getOfferData($offer_id)
{
    $cacheName = 'getOfferData_' . $offer_id;
    $cache = getFromCache($cacheName);

    if ($cache) {
        return $cache->get();
    }

    $offer_id = (int)$offer_id;
    $sql = "SELECT * FROM hotel_oferta WHERE id = $offer_id LIMIT 1";

    $con = conectar();
    $query_result = mysqli_query($con, $sql);
    $result = [];

    if ($query_result && mysqli_num_rows($query_result) > 0) {
        $result[] = mysqli_fetch_assoc($query_result);
    }
    desconectar($con);

    if (!empty($result)) {
        $offerData = $result[0];
        setToCache($cacheName, $offerData, 3600);
        return $offerData;
    }

    return false;
}

function getOfferDataFromToken($token)
{
    $con = conectar();
    $token = mysqli_real_escape_string($con, $token);

    $sql = "SELECT ho.*
            FROM oferta_referral_token ort
            JOIN hotel_oferta ho ON ho.id = ort.id_oferta
            WHERE ort.token = '$token'
            LIMIT 1";

    $query_result = mysqli_query($con, $sql);
    $result = [];

    if ($query_result && mysqli_num_rows($query_result) > 0) {
        $result = mysqli_fetch_assoc($query_result);
    }
    desconectar($con);

    return !empty($result) ? $result : false;
}

?>

