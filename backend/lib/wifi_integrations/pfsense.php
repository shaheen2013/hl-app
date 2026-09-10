<?php
//No direct access allowed
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//If those parameters exists, store them in session
if (!empty($_GET) && !empty($_GET['pfsense'])) {

    $_SESSION['action'] = (!empty($_GET['action']) ? urldecode($_GET['action']) : '');
    $_SESSION['redirurl'] = (!empty($_GET['redirurl']) ? urldecode($_GET['redirurl']) : '');
    $_SESSION['zone'] = (!empty($_GET['zone']) ? urldecode($_GET['zone']) : '');
    $_SESSION['mac'] = (!empty($_GET['client_mac']) ? urldecode($_GET['client_mac']) : '');

}

?>

<form name="weblogin" class="hotelinking-wifi-login-form" action="<?php echo array_get($_SESSION, 'action') ?>" method="post">
    <input type="hidden" name="auth_user" value="<?php echo array_get($datosWifiHotel, 'username') ?>">
	<input type="hidden" name="auth_pass" value="<?php echo array_get($datosWifiHotel, 'password') ?>">
    <input type="hidden" name="redirurl" value="<?php echo array_get($_SESSION, 'redirurl') ?>">
    <input type="hidden" name="zone" value="<?php echo array_get($_SESSION, 'zone') ?>">
    <input type="hidden" name="accept" value="Login">
</form>

<script>

    $(document).ready(function () {
        //when HLevents sends the wifi-redirect msg
        // create cookie and send the form to router
        HLevents.subscribe('wifi-redirect', function(obj){
            //submit the mikrotik form to auth the user
            //Send log

            $('.hotelinking-wifi-login-form').submit();
        })
    });

</script>