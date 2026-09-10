<?php

include_once LIB . 'obtenerdatosHotel.php';

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Configuración de la aplicación de Facebook
$fb = new Facebook\Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v2.10',
]);

//Si las variables no existen, enviarlo a 404...
if (isset($_SESSION['hotel']['id']) && isset($_SESSION['guidHotel']) && isset($_SESSION['user_id'])) {

    include_once LIB . 'getHotelDataLite.php';

    $hotel_id = $_SESSION['hotel']['id']; // Hotel id from SESSION
    $hotel_guid = $_SESSION['guidHotel'];

    //Recoge la información para pintar la pantalla de stay share
    $datosHotel = getHotelDataLite($hotel_id);

} else {

    error_log('No hotel_id or guidHote in facebook-share-callback : redirecting');
    header('Location: /' . $urlTree['404']);
    exit;
}

/*
 * 0 GET ACCESS TOKEN
 */

$helper = $fb->getRedirectLoginHelper();

try {

    $accessToken = $helper->getAccessToken();

} catch (Facebook\Exceptions\FacebookResponseException $e) {

    // When Graph returns an error
    echo '0 Graph returned an error: ' . $e->getMessage();


} catch (Facebook\Exceptions\FacebookSDKException $e) {

    // When validation fails or other local issues
    echo '0 Facebook SDK returned an error: ' . $e->getMessage();


}

if (isset($accessToken)) {

    $_SESSION['facebook_access_token'] = (string)$accessToken;

    //Create URL for Invite
    $urlInvite = SECURE_BASE_PATH . $urlTree['login'];

    //Send additional share email
//    include_once LIB . 'sendAdditionalShareEmail.php';
//    $result = json_decode(sendAdditionalShareEmail($hotel_id, $datosHotel, $_SESSION['userNavLang'], $user_id, $urlInvite, 'stay'), true);

    try {
        $response = $fb->post(
            'me/feed/',
            array(
                'message' => $datosHotel['city'] . '!! :)',
                'link' => $result['urlShare']['url'],
                'picture' => SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL . $hotel_id . '/fotoBg/' . $datosHotel['fotoBg'],
                'caption' => getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], $_SESSION['userLang']),   
                'name' => $datosHotel['hotelName'],
                'description' => '',
            ),
            $accessToken
        );

        // Convert response into array to reach share id
        $share_data = $response->getDecodedBody();

        // Registar share
        include_once LIB . 'referral-share-actions.php';
        shareStayGoalActions($user_id, $hotel_id, 'fb', $share_data['id'], '3', '');

    } catch (Facebook\Exceptions\FacebookResponseException $e) {

        // When Graph returns an error
        echo '3 Graph returned an error: ' . $e->getMessage();
        //exit;
        return false;

    } catch (Facebook\Exceptions\FacebookSDKException $e) {

        // When validation fails or other local issues
        echo '3 Facebook SDK returned an error: ' . $e->getMessage();
        //exit;
        return false;
    }
    //No problems, shared true
    //Remove session variables
    unset($_SESSION['user_id']);
    unset($_SESSION['hotel']['id']);
    unset($_SESSION['guidHotel']);
    return true;
}
