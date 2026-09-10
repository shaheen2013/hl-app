<?php
session_start();

//Allow direct access
!defined('INDEXCONTROLVAL') ? define("INDEXCONTROLVAL", "1") : '';

//Configuración de la aplicación de Facebook
$fb = new Facebook\Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();

/*
 * Check if user has access token
 */

try {

    //Check if user has access token
    $accessToken = $helper->getAccessToken();

} catch (Facebook\Exceptions\FacebookResponseException $e) {

    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;

} catch (Facebook\Exceptions\FacebookSDKException $e) {

    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;

}

/*
 * If user has access token, log in, if not redirect to login page
 */

if (isset($accessToken)) {

    // Logged in!
    $_SESSION['facebook_access_token'] = (string)$accessToken;

    // Now you can redirect to another page and use the
    // access token from $_SESSION['facebook_access_token']

}