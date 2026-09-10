<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

/*
 * Set facebook flow to false until all tests are made
 * Problems we can face: slow internet connections can make tokens to expire
 */

$login_fb = false;
$email_fb = false;
$permissions_fb = false;

$permissions_declined_count = 0;

//Configuración de la aplicación de Facebook
$fb = new Facebook\Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();

/*
 * 0 GET ACCESS TOKEN
 */

try {

    $accessToken = $helper->getAccessToken();

} catch (Facebook\Exceptions\FacebookResponseException $e) {

    //When Graph returns an error
    echo '0 Graph returned an error: ' . $e->getMessage();

} catch (Facebook\Exceptions\FacebookSDKException $e) {

    // When validation fails or other local issues
    echo '0 Facebook SDK returned an error: ' . $e->getMessage();
    
}

/*
 * IF GOT ACCESS TOKEN
 */

if (isset($accessToken)) {

    // Logged in!
    $login_fb = true;
    $_SESSION['facebook_access_token'] = (string)$accessToken;

    // Sets the default fallback access token so we don't have to pass it to each request
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);

    /*
     *  0.5 ask for info about user
     */

    try {

        $response = $fb->get('/me?fields=id,name,email,friends,locale,gender,birthday');
        $userNode = $response->getDecodedBody();
        //$userNode['email']='undefined'; //test

    } catch (Facebook\Exceptions\FacebookResponseException $e) {

        // When Graph returns an error
        echo '1 Graph returned an error: ' . $e->getMessage();

    } catch (Facebook\Exceptions\FacebookSDKException $e) {

        // When validation fails or other local issues
        echo '1 Facebook SDK returned an error: ' . $e->getMessage();
        
    }

    /*
     * 1 Check if user granted all permissions
     */

    try {

        $permissions = $fb->get('/me/permissions');
        $permissions = $permissions->getGraphEdge()->asArray();

    } catch (Facebook\Exceptions\FacebookResponseException $e) {

        // When Graph returns an error
        echo '2 Graph returned an error: ' . $e->getMessage();

    } catch (Facebook\Exceptions\FacebookSDKException $e) {

        // When validation fails or other local issues
        echo '2 Facebook SDK returned an error: ' . $e->getMessage();

    }

    //Check array for permissions denied
    foreach ($permissions as $key) {

        if ($key['status'] == 'declined') {

            // One permission is not granted.
            // Do something. Redirect with an error message.
            //echo $key['permission'] . '<br>';
            $permissions_declined_count += 1;
        }

    }

    //Check permissions count
    if ($permissions_declined_count == 0) {

        $permissions_fb = true;
    }
}

//Check valid Email
//If all permissions ok -> check if user data is OK
$facebookEmailValid = checkFacebookEmail($userNode);

//If email not valid : @boolean
$email_fb = $facebookEmailValid;


//All Done?
if ($login_fb && $email_fb && $permissions_fb) {

    //Save user
    $user_id = crearActualizarUsuario($userNode, $hotel_id);

} else {

    if (!$permissions_fb) {

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['public_profile', 'email', 'user_friends', 'publish_actions', 'user_birthday'];
        $loginUrl = $helper->getReRequestUrl(SECURE_BASE_PATH . 'facebook-login-callback/', $permissions);

    }
}