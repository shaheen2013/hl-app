<?php
// Prevent user to access directly to this controller
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

use Carbon\Carbon;

//////////CHECKS///////////
///////////////////////////
// In stay-share we stored in session hotel_id and hotel_guid, check if those variables are present
if (empty($_SESSION['hotel']['id']) && empty($_SESSION['guidHotel']) && empty($_SESSION['hotelInfo'])) {
    // Session variables not exists, redirect 404
    $log->warning('REDIRECT', array('location' => 'facebook-login-callback', 'destination' => '404', 'message' => 'Session hotel_id AND hotel_guid are empty'));
    header('Location: /' . $urlTree['404']);
    exit;
}

// If user cancel login process must redirect to stay share again
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'access_denied') {
        if ($_GET['error_reason'] == 'user_denied') {
            $log->warning('REDIRECT', array('location' => 'facebook-login-callback', 'destination' => 'stay-share', 'message' => 'user canceled login process'));
            header('location:' . SECURE_BASE_PATH . '/stay-share/' . $_SESSION['guidHotel'] . '/?error=user_canceled');
            exit;
        }
    }
}
if(isset($_GET['error_code'])) {
    $log->warning('FacebookLoginCallback', [
        'message' => 'error message from facebook',
        'error_code' => $_GET['error_code'],
        'error_message' => $_GET['error_message'],
    ]);
}

//////////END CHECKS///////
///////////////////////////

//Include library
include_once RUTA_DIR . LIB . 'crearNuevoUsuario.php';
include_once RUTA_DIR . LIB . 'enviarEmail.php';
include_once RUTA_DIR . LIB . 'emailValidate.php';
// Get session variables defined on stay-share
$hotel_id = $_SESSION['hotel']['id'];
$brandId = $_SESSION['hotel']['brand_id'];
$hotel_guid = $_SESSION['guidHotel'];
$wifi_offer = $_SESSION['wifi_offer'];
$hotel_data = $_SESSION['hotelInfo'];
// Set control variables
$email_already_exists = false;
$permissions_fb = false; //true mandatory to give wifi
$share_fb = false;
$permissions_declined_count = 0;
$gender = array_get($_POST,'gender');
$email = array_get($_POST,'re-email');
$firstName = array_get($_POST,'firstName');
$lastName = array_get($_POST,'lastName');
$ageConsentAccepted = array_get($_POST, 'gdpr_year');
$validGenders = ['male', 'female', 'other'];
// if user is in session then is email is valid
$validEmail = array_get($_SESSION, 'user.id') ? true : false;
$firstNamePattern = $lastNamePattern = str_replace("\1" , "\\1", getNamePattern());
$firstNameLength = $lastNameLength = getNameMinLength();
//login with facebook
if ((array_get($_GET,'code') || array_get($_GET,'state')) && !array_get($_SESSION,'facebook_user')){
    // Set Facebook configuration
    $fb = new Facebook\Facebook([
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v14.0',
    ]);

    $helper = $fb->getRedirectLoginHelper();
    if (isset($_GET['state'])) {
        $helper->getPersistentDataHandler()->set('state', $_GET['state']);
    }
    // Get access token from facebook
    try {
        $accessToken = $helper->getAccessToken(SECURE_BASE_PATH . 'facebook-login-callback/');
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        $log->addError('Facebook error getting token (Response exception)', array('message' => $e->getMessage()));
        //redirect to stay and open the form
        redirectToStayWithError($hotel_guid);
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        $log->addError('Facebook error getting token (SDK exception) ', array('message' => $e->getMessage()));
        //redirect to stay and open the form
        if (!empty($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
            redirectToWifi($hotel_id, $_SESSION['user']['id']);
        } else {
            redirectToStayWithError($hotel_guid);
        }
        exit;
    }

    // If got access token, continue facebook login process
    if (!empty($accessToken)) {

        //User logged in using facebook
        $_SESSION['facebook_access_token'] = (string)$accessToken;

        // Sets the default fallback access token so we don't have to pass it to each request
        $fb->setDefaultAccessToken(array_get($_SESSION,'facebook_access_token'));

        //Get user info from facebook
        try {
            $response = $fb->get('/me?fields=id,name,first_name,last_name,email,friends,locale,location,gender,birthday');
            $facebook_user = $response->getDecodedBody();
            $log->info('facebook_user is ', $facebook_user);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            $log->addError('Facebook error getting user info (Response exception) ', array('message' => $e->getMessage()));
            //redirect to stay and open the form
            redirectToStayWithError($hotel_guid);
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            $log->addError('Facebook error getting user info (SDK exception) ', array('message' => $e->getMessage()));
            //redirect to stay and open the form
            redirectToStayWithError($hotel_guid);
            exit;
        }
        //Get user granted permissions from facebook
        try {
            $permissions = $fb->get('/me/permissions');
            $permissions = $permissions->getGraphEdge()->asArray();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $log->addError('Facebook error getting user permissions (Response exception) ', array('message' => $e->getMessage()));
            //redirect to stay and open the form
            redirectToStayWithError($hotel_guid);
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $log->addError('Facebook error getting user permissions (SDK exception) ', array('message' => $e->getMessage()));
            //redirect to stay and open the form
            redirectToStayWithError($hotel_guid);
            exit;
        }

        //Check array for permissions denied
        foreach ($permissions as $key) {
            if ($key['status'] == 'declined' && in_array($key['permission'], FACEBOOK_PERMISSIONS)) {
                // One permission is not granted.
                // Do something. Redirect with an error message.
                //echo $key['permission'] . '<br>';
                $permissions_declined_count += 1;
            }
        }
        //Check permissions count
        if ($permissions_declined_count == 0) {
            $permissions_fb = true;
            $log->info('facebook permissions accepted', ['permission' => $permissions]);
        } else {
            $log->info('facebook permissions were declined', ['permission' => $permissions]);
        }
        //if have permissions create facebook user
        if ($permissions_fb) {
            //Check user facebook
            if (!array_get($facebook_user, 'email') || !in_array(array_get($facebook_user, 'gender'), $validGenders)) {
                $check_fb_user = getFacebookUser($facebook_user['id']);
                if ($check_fb_user) {
                    $facebook_user['email'] = array_get($facebook_user,'email') ? array_get($facebook_user,'email') : array_get($check_fb_user,'email');
                    $facebook_user['gender'] = array_get($check_fb_user,'gender');
                    $facebook_user['sendex'] = array_get($check_fb_user,'sendex', 0.0);
                    $facebook_user['emailResult'] = array_get($check_fb_user,'emailResult');
                    // Check sendex to avoid emails with validation result not accepted
                    $validEmail = !(
                        (
                            $facebook_user['emailResult'] == 'undeliverable' ||
                            $facebook_user['sendex'] < LOWEST_SENDEX_SCORE ||
                            $facebook_user['email'] != $check_fb_user['email']
                        ) && VERIFY_EMAILS);

                    if (!$validEmail) {
                        $log->debug('Rechecking facebook email when recovering data from DB ', [$facebook_user['email']]);
                        $resultEmail = validateEmail($facebook_user['email']);
                        $validEmail = $resultEmail['valid'];

                        if ($validEmail) {
                            $facebook_user['sendex'] = array_get($resultEmail, 'sendex', 0.0);
                            $facebook_user['emailResult'] = array_get($resultEmail, 'result', 'risky');

                            $updateData = [
                                'fb_id'        => $facebook_user['id'],
                                'user_id'      => $check_fb_user['id'],
                                'email'        => $facebook_user['email'],
                                'sendex'       => $facebook_user['sendex'],
                                'email_result' => $facebook_user['emailResult']
                            ];

                            updateFacebookUserData($updateData);
                        }
                    }
                }
            }
            // Change birthday date format
            if (array_get($facebook_user,'birthday')) {
                $user_birthday = date_create_from_format('m/d/Y', $facebook_user['birthday']);
                $facebook_user['birthday'] = $user_birthday->format('Y-m-d');
            }

            //Create locale if is null
            if (!array_get($facebook_user,'locale')) {
                $accept_languages_arr = explode(",", array_get($_SERVER,'HTTP_ACCEPT_LANGUAGE'));
                preg_match("/^(([a-zA-Z]+)(-([a-zA-Z]+)){0,1})/", $accept_languages_arr[0], $matches);
                $locale = $matches[1];
                //reformate the locale if comes like en-gb, pt-pt to en_GB or pt_PT
                $locale_array = explode('-', $locale);
                if ($locale_array && count($locale_array) == 2) {
                    $locale = $locale_array[0] . '_' . strtoupper($locale_array[1]);
                }
                $facebook_user['locale'] = $locale;
            }

            //check if sendex and emailResult exist
            if (!array_get($facebook_user,'sendex') && !array_get($facebook_user,'emailResult') && array_get($facebook_user,'email')) {
                // Validating email against Kickbox/ZeroBouce
                $log->debug('Check email from Facebook', [$facebook_user['email']]);
                $resultEmail = validateEmail($facebook_user['email']);
                //email not valid or valid
                $validEmail = $resultEmail['valid'];

                $facebook_user['sendex'] = array_get($resultEmail,'sendex', 0.0);
                $facebook_user['emailResult'] = array_get($resultEmail, 'result', 'risky');
            }

            $facebook_user['card_id'] = array_get($_SESSION, 'card_id', null);
            $facebook_user['room_num'] = array_get($_SESSION, 'roomNumber', null);
            $facebook_user['user_hotel_id'] = array_get($_SESSION, 'user_hotel_id', null);
            $facebook_user['customer'] = array_get($_SESSION, 'customer', null);

            $facebook_user['birthday'] = !empty($_SESSION['birthday']) ? $_SESSION['birthday'] : $facebook_user['birthday'];
            $facebook_user['gender'] = !empty($_SESSION['gender']) ? $_SESSION['gender'] : array_get($facebook_user,'gender');
            $facebook_user['name'] = !empty($_SESSION['name']) ? $_SESSION['name'] : $facebook_user['name'];
            $facebook_user['first_name'] = !empty($_SESSION['first_name']) ? $_SESSION['first_name'] : $facebook_user['first_name'];
            $facebook_user['last_name'] = !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : $facebook_user['last_name'];

            $facebook_user['lang'] = $_SESSION['userNavLang'];
            $facebook_user['hotel_id'] = $hotel_id;

            //create facebook user SESSION
            if (!$validEmail) {
                // remove email if is not a valid one
                unset($facebook_user['email']);
            }

            if (!validateName($facebook_user['first_name'])) {
                unset($facebook_user['first_name']);
            }
            
            if (!validateName($facebook_user['last_name'])) {
                unset($facebook_user['last_name']);
            }

            $log->info('setting facebook user in session' . (!$validEmail ? ' without email because is not valid' : ''), $facebook_user);
            $_SESSION['facebook_user'] = $facebook_user;
        }else {
            $helper = $fb->getRedirectLoginHelper();
            $loginUrl = $helper->getReRequestUrl(SECURE_BASE_PATH . '/facebook-login-callback/', FACEBOOK_PERMISSIONS);
        }
    } else {
        $log->warning('No access token from facebook');
    }
}

//Post Template
if (in_array($gender, $validGenders) || $email || $firstName || $lastName || $ageConsentAccepted) {
    // Facebook returned undefined gender and defined email and user inserted a new email in form
    $permissions_fb = true;

    //insert facebook gender
    $facebookGender = array_get($_SESSION,'facebook_user.gender');
    $facebookFirstName = array_get($_SESSION,'facebook_user.first_name');
    $facebookLastName = array_get($_SESSION,'facebook_user.last_name');
    $facebookName = array_get($_SESSION,'facebook_user.name');
    $_SESSION['facebook_user']['gender'] = ($facebookGender && in_array($facebookGender, $validGenders)) ? $facebookGender : $gender;
    $_SESSION['facebook_user']['first_name'] = $facebookFirstName ? $facebookFirstName : $firstName;
    $_SESSION['facebook_user']['last_name'] = $facebookLastName ? $facebookLastName : $lastName;
    $_SESSION['facebook_user']['name'] = $facebookName ? $facebookName : $_SESSION['facebook_user']['first_name'] . ' ' . $_SESSION['facebook_user']['last_name'];
    // Facebook returned undefined email and user inserted a new email in form
    if ($email) {
        // Validating email against Kickbox/ZeroBouce
        $log->info('Invalid Facebook email. Checking email from post template', [$email]);
        $resultEmail = validateEmail($email);
        $validEmail = $resultEmail['valid'];

        if ($validEmail) {
            // Email is valid, no matter what happens from here, not user fault, give wifi
            // $_SESSION['facebook_user']['email'] = array_get($_SESSION,'facebook_user.email') ? array_get($_SESSION,'facebook_user.email') : $email;
            $_SESSION['facebook_user']['email'] = $email;
            $_SESSION['facebook_user']['sendex'] = array_get($resultEmail, 'sendex', 0.0);
            $_SESSION['facebook_user']['emailResult'] = array_get($resultEmail, 'result', 'risky');

            if (!array_get($_SESSION,'facebook_user.id')){
                redirectOnError('Error Facebook user is empty -> ', array('facebook_user' => $_SESSION['facebook_user']));
            }
            // return;
        } else {
            $log->info('User inserted an invalid email', array('email' => $email));
            // return;
        }
    }

    //if its a manual post for email or gender , but the email is already set because it was just set before , let pass
    if (array_get($_SESSION, 'facebook_user.email')) {
        $validEmail = true;
    }

}

//if facebook_user is in session this means it has permissions true
if(array_get($_SESSION,'facebook_user')) {
    $permissions_fb = true;
}

$isUnderConsentAge = false;
if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", array_get($_SESSION, 'facebook_user.birthday'))) {
    $age = dateDiff($_SESSION['facebook_user']['birthday'] . ' 00:00:00', date('Y-m-d H:i:s'))->y;
    $isUnderConsentAge = $age < 16;
}

if ($validEmail && array_get($_SESSION, 'facebook_user.email') && in_array(array_get($_SESSION, 'facebook_user.gender'), $validGenders) && array_get($_SESSION, 'facebook_user.first_name') && array_get($_SESSION, 'facebook_user.last_name') && (!$isUnderConsentAge || $ageConsentAccepted)) {
    createUserAndSessionUser(array_get($_SESSION,'facebook_user'));

    facebookLoginHotelProductsActions($brandId);
    // Redirect wifi
    redirectToWifi($hotel_id, $_SESSION['user']['id']);
}

/**
 * redirect to next step method
 * @param $id_hotel
 * @param $user_id
 */
function redirectToWifi($id_hotel, $user_id = null)
{
    global $urlTree;
    $query = [
        'i' => $id_hotel,
        'u' => $user_id,
        'PHPSESSID' => session_id()
    ];

    $redirectUrl = SECURE_BASE_PATH . $urlTree['stay-wifi-redirect'] . '/?' . http_build_query($query, '&amp;');
    header("location: " . $redirectUrl);
    exit();
}

/**
 * redirect back to stay share method
 * @param $hotel_guid
 */
function redirectToStayWithError($hotel_guid)
{
    global $urlTree;

    $_SESSION['fbErrors'] = true;
    $_SESSION['fbLoginError'] = true;
    $redirectUrl = SECURE_BASE_PATH . $urlTree['stay-share'] . '/' . $hotel_guid . '/?error=FacebookError';
    header("location: " . $redirectUrl);
    exit();
}

/**
 * TODO refactor
 * @param $brandId
 */
function facebookLoginHotelProductsActions($brandId)
{
    include_once LIB . 'hotelinking_emails.php';
    $brandProducts = obtenerProductosHotel($brandId); 

    if (getBrandProductActive($brandProducts, 'satisfaction') || getBrandProductActive($brandProducts, 'review') || getBrandProductActive($brandProducts, 'wifi_offers')) {
        // Will send email at connection history time
        $_SESSION['trigger_offer']['facebook_login'] = true;
    }
}

/**
 * @param $user
 * @param $facebook_user
 * @param $sendex
 * @param $emailResult
 */
function createSessionUser($user, $facebook_user)
{
    try {

        $birthday = Carbon::createFromFormat('m/d/Y', array_get($facebook_user, 'birthday'))->toDateString();

    } catch (\Exception $exception) {

        global $log;

        $log->warning("Facebook birthday invalid format ", [
            'email'    => array_get($facebook_user, 'email'),
            'birthday' => array_get($facebook_user, 'birthday'),
            'message'  => $exception->getMessage()
        ]);

        $birthday = '';
    }

    return array(
        "id" => array_get($user, 'id') ? array_get($user, 'id') : array_get($_SESSION, 'user.id'),
        "name" => array_get($facebook_user, 'name'),
        "first_name" => array_get($facebook_user, 'first_name'),
        "last_name" => array_get($facebook_user, 'last_name'),
        "email" => array_get($facebook_user, 'email'),
        "lang" => array_get($_SESSION, 'userNavLang'),
        "gender"=> array_get($facebook_user,'gender'),
        "birthday" => array_get($user, 'fecha_nacimiento') ? array_get($user, 'fecha_nacimiento') : $birthday,
        "locale" => array_get($facebook_user,'locale'),
        "facebook_id" => array_get($facebook_user,'id'),
        "facebook_picture" => 'https://graph.facebook.com/' . array_get($facebook_user,'id') . '/picture?width=200',
        "facebook_link" => 'https://www.facebook.com/app_scoped_user_id/' . array_get($facebook_user,'id')  . '/',
        "facebook_friends" => array_get($facebook_user, 'friends.summary.total_count', 0),
        "facebook_location_name" => array_get($facebook_user, 'location.name'),
        "facebook_location_id" => array_get($facebook_user, 'location.id'),
        "card_id" => array_get($facebook_user,'card_id'),
        "room_num" => array_get($facebook_user,'room_num'),
        "isNew" => array_get($user,'isNew'),
        "user_hotel_id" => array_get($facebook_user,'user_hotel_id'),
        'source' => 'facebook',
        'sendex' => array_get($facebook_user,'sendex'),
        'emailResult' => array_get($facebook_user,'emailResult'),
    );
}

/**
 * @param $message
 * @param $object to log
 * @return void
 */
function redirectOnError($message, $array)
{
    global $log;
    global $urlTree;


    $log->addError($message, $array);
    header('location: ' . SECURE_BASE_PATH . $urlTree['stay-wifi-redirect'] . '/?i=' . $_SESSION['hotel']['id'] . '&PHPSESSID='. session_id() .'&action=skip');
    exit();
}

function createUserAndSessionUser($facebook_user)
{
    global $log;
    $log->info('facebook_user', $facebook_user);
    $user = createNewUser($facebook_user, array_get($facebook_user,'sendex'), array_get($facebook_user,'emailResult'), 'facebook');
    $_SESSION['user'] = createSessionUser($user, $facebook_user);
    if (!empty($_SESSION['user']['id'])) {
        // Create or update facebook user
        $result = upsertFacebookUser($_SESSION['user']);
        if (!$result) {
            redirectOnError('Error upserting Facebook user ', $_SESSION['user']);
        }

                $friends = data_get($facebook_user, 'friends.data');
                // $likes = array_pluck(data_get($facebook_user, 'likes.data'), 'name');

                if (is_countable($friends) && count($friends) > 0) {
                    $_SESSION['has_friends'] = true;
                    $_SESSION['friends'] = array_slice($friends, 0, 3);
                    $log->info('facebook friends', [$_SESSION['friends']]);
                }

                $log->debug('friends', [$friends]);
                // $log->debug('likes', [$likes]);

    } else {
        redirectOnError('SESSION user id is empty', array('session_user' => $_SESSION['user']));
    }
}
