<?php
//No direct access allowed
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
//If those parameters exists, store them in session
$_SESSION['sip'] = array_get($_SESSION, 'sip', array_get($_GET, 'sip', ''));
$_SESSION['mac'] = array_get($_SESSION, 'mac', macFormatter(array_get($_GET, 'client_mac', '')));
$_SESSION['startUrl'] = (!empty($datosWifiHotel['url']) ? urldecode($datosWifiHotel['url']) : $_GET['startUrl']);


$log->debug('Ruckus Unleashed data', [  
    'session' => $_SESSION,
    '$_POST' => $_POST,
    '$GET' => $_GET
]);

?>

<form name="weblogin_form" id="logon" class="hotelinking-wifi-login-form" action="http://<?php echo array_get($_SESSION, 'sip') . ':9997/login'?>" method="post">
    <input type="hidden" name="username" value="<?php echo $datosWifiHotel['username']; ?>">
    <input type="hidden" name="password" value="<?php echo $datosWifiHotel['password']; ?>">
    <input type="hidden" name="startUrl" value="<?php echo $_SESSION['startUrl']; ?>">
</form>

<script>
    
    $(document).ready(function () {
        //when HLevents sends the wifi-redirect msg
        // create cookie and send the form to router
        HLevents.subscribe('wifi-redirect', function(obj){
            //submit the ruckus form to auth the user
            $('.hotelinking-wifi-login-form').submit();
        })
    });

    
</script>
