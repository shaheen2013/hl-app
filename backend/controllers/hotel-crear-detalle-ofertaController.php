<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {echo 'No direct access allowed.';exit;}

// ---> webservices/hotel-crear-detalle-oferta.php

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding(); // Si no esta logueado lo manda a la landing

$currentPage = 'offer-management';
$currentSubPage = 'offer-management';

//----------------
include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'fecha.php';

include_once LIB . 'apiGateway.php';

// Rutas para imagen de oferta
$_SESSION['ruta_tmp'] = DIR_IMG_FICHA_HOTEL . $_SESSION['h_logueado'] . '/tmp/';
$_SESSION['ruta'] = DIR_IMG_OFERTAS;

if (!file_exists($_SESSION['ruta_tmp'])) {
    mkdir($_SESSION['ruta_tmp'], 0777, true);
}

if (empty($_SESSION['requerimientos'])) {
    $_SESSION['requerimientos'] = 0;
}
if (empty($_SESSION['descuento'])) {
    $_SESSION['descuento'] = 0;
}

// NEW VERSION
$offer = null;
//if offer id is defined in url
if (!empty($_GET['id'])) {

    //get offer info and hotel languages
    $offer = getOfferById($_GET['id']);

    //get translation text for the language
    $offer_language = getOfferLangById($_GET['id'], $_GET['lang']);

}

//get all hotel languages that offer has to be mapped to
$hotel_languages = getOfferLanguages($_SESSION['hotel']['id'], !empty($_GET['id']) ? $_GET['id'] : '');

$currentSubPage = "hotel-create-reward-details";

//selected language key if exists lang in $_GET else 0
$language_selected = !empty($_GET['lang']) ? array_search($_GET['lang'], array_column($hotel_languages, 'lang')) : '0';

//array of mapped languages, used for the can_publish_offer ternary
$mapped_languages = array_filter($hotel_languages, function ($arr) {
    return array_key_exists('id_oferta', $arr) && !empty($arr['id_oferta']);
});

//check if length of mapped languages is the same as length of all languages for hotel
$can_publish_offer = (!empty($mapped_languages) && sizeof($mapped_languages) == sizeof($hotel_languages)) ? true : false;

include_once LIB . 'guardarOferta.php';
include_once LIB . 'subirArchivos.php'; //thumbnails
include_once LIB . 'rmdir.php';
include_once LIB . 'borrarSession.php';
include_once RUTA_DIR . LIB . 'fecha.php';

//publish offer
if (!empty($_GET['id']) && isset($_GET['publish']) && $offer['estado'] == '0') {
    if ($can_publish_offer) {
        //legacy state of offer that we need to duplicate
        $id_offer = publishOffer($_GET['id'], '6');
        $ok = array(true, '2036');
    } else {
        $ok = array(false, '3010'); //Could not publish offer
    }
}

if ($_POST) {
    if (!empty($_POST['name']) && !empty($_POST['startDate']) && !empty($_POST['lang']) && !empty($_POST['description']) && !empty($_POST['conditions']) && !empty($_POST['booking_engine_code'])) {

        //is there an end date? else 0000-00-00
        // $endDate = !empty($_POST['endDate']) ? $_POST['endDate'] : '0000-00-00';
        $endDate = !array_get($_POST, 'endDate', '0000-00-00');

        //check if offer is for chain or independent hotel
        if (array_has($_SESSION, 'chain.id')) {
            $id_hotel = '';
            $id_chain = array_get($_SESSION, 'chain.id');
        } else {
            $id_hotel = array_get($_SESSION, 'hotel.id');
            $id_chain = array_get($_SESSION, 'chain.id');
        }

        //if we are updating an existing offer
        if (!empty($_POST['id_offer'])) {
            $id_offer = $_POST['id_offer'];
            updateBookingEngineCode($id_offer, $_POST['booking_engine_code']);
        } else {
            //else create new offer
            $id_offer = saveOffer($id_hotel, $id_chain, girarFecha($_POST['startDate']), $endDate, $_SESSION['foto'] ?? null, $_POST['booking_engine_code']);
        }

        //create/update the traductions
        saveOfferLang($id_offer, $_POST['lang'], $_POST['name'], $_POST['description'], $_POST['conditions']);

        //create directory and files for offer image
        if (!empty($url['dir2']) && !empty($_SESSION['foto'])) {
            $filename = $_SESSION['ruta'] . $id_offer . '/' . $_SESSION['foto'];

            if (!empty($_SESSION['ruta']) && !empty($id_offer) && !file_exists($filename)) {
                mkdir($_SESSION['ruta'] . $id_offer, 0777);
            }
            copy($_SESSION['ruta_tmp'] . $_SESSION['foto'], $filename);
            crearThumbnails($_SESSION['ruta'] . $id_offer . '/' . $_SESSION['foto']);

            //Borrar carpeta temporal de imagenes
            rm_dir($_SESSION['ruta_tmp']);

            // Borramos los datos de session
            // borrarSessionCrearOferta($_SESSION['hotel']['id']);
        }

        header('Location: /' . $urlTree['hotel-crear-detalle-oferta'] . '/?id=' . $id_offer . '&lang=' . $_POST['lang']);
    }

}
