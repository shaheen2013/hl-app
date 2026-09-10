<?php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Configuración de la aplicación de Facebook
$fb = new Facebook\Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v14.0',
]);

//Login user
$helper = $fb->getRedirectLoginHelper();
$loginUrl = $helper->getLoginUrl( SECURE_BASE_PATH . 'facebook-login-callback/', FACEBOOK_PERMISSIONS );
?>