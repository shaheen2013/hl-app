<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

/*
 * continue_url     //Url to go when login
 * login_url        //Login to send form
 * ap_mac           //Mac address of AP
 * ap_name          //name of AP
 * ap_tags          //Tags of AP
 */

//If those parameters exists, store them in session
if ($_GET && isset($_GET['login_url'])) {

    $_SESSION['login_url'] = urldecode($_GET['login_url']);

    // TODO if not session, redirect to splash page

}
?>

<form method=POST action="<?php echo array_get($_SESSION, 'login_url') ?>" class="hotelinking-wifi-login-form">

    <?php if (isset($_SESSION['portalRedirectUrl'])) { ?>
        <input type="hidden" name="success_url" value="<?php echo $_SESSION['portalRedirectUrl'] ?>" />
    <?php } else { ?>
        <input type="hidden" name="success_url" value="<?php echo $datosWifiHotel['url'] ?>" />
    <?php } ?>
    <input type="hidden" name="username" value="<?php echo $datosWifiHotel['username'] ?>" />
    <input type="hidden" name="password" value="<?php echo $datosWifiHotel['password'] ?>" />

</form>

<script>
    $(document).ready(function() {
        //must have a button with class send-login-button in the template
        // $('.send-login-button').click(function () {
        HLevents.subscribe('wifi-redirect', function(obj) {
            //Send log

            $('.hotelinking-wifi-login-form').submit();
        })
        // })
    })
</script>