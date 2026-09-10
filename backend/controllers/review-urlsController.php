<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing

include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'generarUrlCorrecta.php';
include_once LIB . 'hotelinking_emails.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

// For front purposes
$currentSubPage = 'review-urls';

$idiomas = obtenerLangsHotel($_SESSION['h_logueado']);

$hotel_id = $_SESSION['h_logueado'];// hotel_id : id hotel asociado en la BD de emails
$brandId = $_SESSION['loggedBrandID'];
$brandProducts = obtenerProductosHotel($brandId);
$portalProProductActive = getBrandProductActive($brandProducts, 'portal_pro');

// Campos de la tabla url de la BD de emails.
function obtenerDatosPostReviewUrls()
{
    return array('tripadvisor', 'yelp', 'holidaycheck','tophotels', 'zoover', 'custom_en', 'custom_es', 'custom_de', 'custom_fr','custom_ca','custom_it', 'google');
}

if (!empty($_POST) && !empty($_SESSION['h_logueado']) && !empty($_POST['save'])) {
    //Array datos post
    $unset = (($_POST['tripgoogle'] ?? 'url-tripadvisor') == 'url-tripadvisor') ? 'google' : 'tripadvisor';
    $_POST[$unset] = "";

    $arrayData = obtenerDatosPostReviewUrls();

    //Inicializamos array urls review
    $arrayUrlsReview = array(
        'hotel_id' => $hotel_id
    );

    //Inicializamos feedback
    $feedbackNum = '2007';
    $feedbackType = true;

    //Generamos el array con todos los datos del post
    foreach ($arrayData as $dato) {
        !empty($_POST[$dato]) ? $urlActual = $_POST[$dato] : $urlActual = '';

        if (substr($dato, 0, 6) === "custom" && $urlActual != '') {
            /*echo 'es una custom no vacia ';
            if(filter_var($url, FILTER_VALIDATE_URL) === FALSE)
            {
                echo ' y la URL es incorrecta<br>';
                //Es una custom y la URL NO es correcta. No la guardamos.
                $urlActual = '';
                $feedbackNum = '4002';
                $feedbackType = false;
            }else{
                echo ' y la URL es correcta<br>';
            }*/
            $urlActual = generarURLCorecta($urlActual);
        }

        !empty($_POST[$dato]) ? $arrayUrlsReview[$dato] = $urlActual : $arrayUrlsReview[$dato] = '';
    }

    guardarUrlsReview($arrayUrlsReview);

    //Feedback
    $ok = array($feedbackType, $feedbackNum);
}

$gateway = new ApiGatewayConnection();
$endPoint = HOTELINKING_ENDPOINT . "brands/{$brandId}/products/" . getIdByProductName('review') . "/configuration";
$urlsReviews = obtnerUrlsReviewHotel($hotel_id);

if (!empty($_POST['hotelConfirmButton'])) {
    $ignoreRating = array_get($_POST, 'ignoreRating') && $_POST['reviewSendType'] === "after_check_out" || $_POST['reviewSendType'] === "after_wifi";
    $reviewData = [
        "send_after_days"   => $_POST['diasEnvioReview'],
        "ignore_rating"     => $ignoreRating,
        "send_type"         => $_POST['reviewSendType'],
    ];

    $gateway->sendRequest($reviewData, $endPoint, 'PUT');

    //Feedback
    deleteCacheByTag('hotel_review_' . $hotel_id);
    $ok = array(true, '2007');
}

$reviewConfig = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), true);

?>
