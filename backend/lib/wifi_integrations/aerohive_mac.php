<?php
//No direct access allowed
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

// Example request received from Aerohive
// ?url=E2B8F3578D88E9B12396E14E98920F99D23B13921837C9FDC99F85D14F238887A7A8E253D2C4B2FC2A92F84B&ssid=Linking&mac=18810e3c4194&autherr=0&Called-Station-Id=f09ce940ea17&NAS-IP-Address=1.1.4.1&RADIUS-NAS-IP=192.168.8.28&Calling-Station-Id=18810e3c4194&STA-IP=192.168.8.107&NAS-ID=MIJAS-RECEPCION

//If those parameters exists, store them in session
$_SESSION['urlLogin'] = array_get($_SESSION, 'urlLogin', array_get($_GET, 'NAS-IP-Address', ''));
$_SESSION['mac'] = array_get($_SESSION, 'mac', macFormatter(array_get($_GET, 'mac', '')));
$_SESSION['ssid'] = array_get($_SESSION, 'ssid', array_get($_GET, 'ssid', ''));
$_SESSION['url'] = isset($_SESSION['portalRedirectUrl']) ? $_SESSION['portalRedirectUrl'] : array_get($_SESSION, 'url', array_get($_GET, 'url', ''));
?>

<form name="weblogin_form" id="logon" class="hotelinking-wifi-login-form" action="http://<?php echo array_get($_SESSION, 'urlLogin') . '/reg.php'?>" method="post">
    <input type="hidden" name="autherr" value="0">
    <input type="hidden" name="username" value="<?php echo $datosWifiHotel['username']; ?>">
    <input type="hidden" name="password" value="<?php echo $datosWifiHotel['password']; ?>">
    <input type="hidden" name="ssid" value="<?php echo array_get($_SESSION, 'ssid'); ?>" />
    <input type="hidden" name="url" value="<?php echo array_get($_SESSION, 'url'); ?>" />
</form>

<script>
    
    $(document).ready(function () {
        //when HLevents sends the wifi-redirect msg
        // create cookie and send the form to router
        HLevents.subscribe('wifi-redirect', function(obj){
            //submit the mikrotik form to auth the user
            $('.hotelinking-wifi-login-form').submit();
        })
    });
</script>
