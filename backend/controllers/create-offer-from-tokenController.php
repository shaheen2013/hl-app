<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

$tokenOfferData = null;

if (!empty($_GET['tk']) && empty($_GET['type'])) {

    // Búscar token en BD (pre_oferta_token)
    $token          = $_GET['tk'];
    $tokenOfferData = getTokenData($token);
    if ($tokenOfferData['code'] == '200') {

        //Token encontrado
        if ($tokenOfferData['tipo_oferta'] == 'inmediate') {
            $tipo_oferta = '3';
            //oferta inmediate (canjeo inmediato)
            if (!empty($_POST['redeem'])) {
                //Generar oferta
                include_once LIB . 'referrer.php';
                $promo_code                    = asignarOfertaReferral($tokenOfferData['id_usuario'], $tokenOfferData['id_oferta'], $tokenOfferData['id_hotel'], 0, $tipo_oferta, '-', '', '1');
                $tokenOfferData['promocode']   = $promo_code;
                $tokenOfferData['redeem_date'] = date('l jS \of F Y');
                // canjear oferta inmediate
                include_once MODEL . 'cuponAccionesModel.php';
                canjearPromoCode($promo_code, $tokenOfferData['id_hotel'], $tokenOfferData['id_hotel'], 'hotel', 0);
                //Borrar token BD (pre_oferta_token)
                borrarTokenPreOferta($tokenOfferData['id']);
                //Cambiamos el codigo
                $tokenOfferData['code'] = '210';
                //Actualizamos cache
                deleteCacheByKey('getTokenPreOfertaData_' . $token);
                setToCache('getTokenPreOfertaData_' . $token, $tokenOfferData, 864000); //10 Days
            }
        } else {

            //oferta web (canjeo via web del hotel)
            $tipo_oferta = 'null';

            //Generar oferta
            include_once LIB . 'referrer.php';
            $promoCode = asignarOfertaReferral($tokenOfferData['id_usuario'], $tokenOfferData['id_oferta'], $tokenOfferData['id_hotel'], 0, $tipo_oferta, '-', '', '1');

            //Borrar token BD (pre_oferta_token)
            borrarTokenPreOferta($tokenOfferData['id']);

            //--------------- enviar email canjeo oferta via web ---------------
            //obtener datos usuario
            include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
            $datosUsuario = obtenerDatosUsuarioMail($tokenOfferData['id_usuario']);

            //obtener datos hotel
            include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
            $datosHotel     = getHotelData($tokenOfferData['id_hotel']);
            $guidHotel      = obtenerGUIDHotel($tokenOfferData['id_hotel']);
            $redeemOfferUrl = SECURE_BASE_PATH . $urlTree['redeem-offer'] . '/?hlhid=' . $guidHotel . '&hlpc=' . $promoCode;

            // Add HLTI cookie values
            $query          = [
                'hltr' => 'captive_portal',
                'hlho' => $tokenOfferData['id_hotel'],
                'hlch' => !empty($datosHotel['id_cadena']) ? $datosHotel['id_cadena'] : NULL,
                'hlui' => $tokenOfferData['id_usuario']
            ];
            $redeemOfferUrl .= '&' . http_build_query($query);
            // $hotelLogo = SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL . $tokenOfferData['id_hotel'] . '/logo/small_' . $datosHotel['logo'];
            $hotelLogo = $datosHotel['logo'];
            $hotelBg   = SECURE_BASE_PATH . DIR_IMG_OFERTAS . $tokenOfferData['id_oferta'] . '/big_' . $tokenOfferData['img_oferta'];//Background
            // $hotelBg = $tokenOfferData['img_oferta'];//Background
            $urlLogin = SECURE_BASE_PATH . 'login';
            $urlHotel = '';
            //Creamos enlace UNSUSCRIBE
            $hash = generate_hash($datosUsuario['email'], 'promoCodeHash');
            //URL generada
            include_once RUTA_DIR . LIB . 'make_unsuscribe_hash.php';
            $urlUnsuscribe = createUrlUnsuscribe($hash, $datosUsuario['email']);

            include_once RUTA_DIR . LIB . 'idiomas.php';
            $lang                     = mirarIdiomaPlataforma($datosUsuario['lang']);
            $userName                 = $datosUsuario['nombre'];
            $ofertaReferral['nombre'] = $tokenOfferData['nombre_oferta'];
            include_once RUTA_DIR . LANG . $lang . '/email/promo-email.php';
            include_once RUTA_DIR . LIB . 'plantillasMails/promo-email.php';
            //--------------- FIN enviar email canjeo oferta via web ---------------
        }
    }
}

if (!empty($_GET['tk']) && !empty($_GET['type']) && $_GET['type'] == 'birthday') {
    //Control
    $isBirthday   = true;
    $birthdayGift = false;
    $old          = false;

    $offer_type = '10'; // Tipo birthday
    include_once LIB . 'encrypt.php';
    include_once LIB . 'referrer.php';
    include_once LIB . 'hotelBirthdayOffers.php';    
    include_once LIB . 'obtenerdatosHotel.php';
    //decrypt offer data format user_id-hotel_id
    $data = dec_enc('decrypt', $_GET['tk']);
    //0 user_id, 1 hotel_id
    $userAndHotelIds = explode("-", $data);
    $userId          = $userAndHotelIds['0'];
    $hotelId         = $userAndHotelIds['1'];
    //datos del hotel
    $datosHotel = getHotelData($hotelId);
    //Get hotel Guid
    $guidHotel = obtenerGUIDHotel($hotelId);
    if ($guidHotel) {
        //Get birthday offer ID
        $birthdayOfferId = array_get(getBirthdayOffer($hotelId), 'oferta_id');
        //If birthday offer exists
        if ($birthdayOfferId) {
            //Check if user has an offer already for this hotel
            //Will check to if a year from one offer to another has past
            $oldOffer = CheckIfOfferTypeExists($offer_type, $userId, $hotelId);
            if ($oldOffer) {
                //is any active, checking "canjeado" then return active offer
                foreach ($oldOffer as $offer) {
                    if ($offer['canjeado'] == 0) {
                        $old       = true;
                        // FIX: Always use the unique per-user token
                        // Using booking_engine_code causes all users to share the same code,
                        // resulting in "already redeemed" errors when multiple users try to redeem
                        $promoCode = $offer['token'];
                        break;
                    }
                }
            }
            //If not old offer present create a new one
            if (!$old) {
                //create offer
                $promoCode = asignarOfertaReferral($userId, $birthdayOfferId, $hotelId, 0, $offer_type, '-', '', '1');
                // FIX: Don't override with booking_engine_code
                // Each user needs their own unique code to prevent redemption conflicts
                // The unique HL code created by asignarOfertaReferral is sufficient
            }
            $birthdayGift   = true;
            $redeemOfferUrl = SECURE_BASE_PATH . $urlTree['redeem-offer'] . '/?hlhid=' . $guidHotel . '&hlpc=' . $promoCode;
            $query          = [
                'hltr' => 'hotelinking_birthday_email',
                'hlho' => $hotelId,
                'hlch' => !empty($datosHotel['id_cadena']) ? $datosHotel['id_cadena'] : NULL,
                'hlui' => $userId
            ];
            $redeemOfferUrl .= '&' . http_build_query($query);
        }
    }
}

?>