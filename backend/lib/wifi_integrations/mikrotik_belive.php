<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//If those parameters exists, store them in session
if (!empty($_POST) && !empty($_POST['mac'])) {
    $_SESSION['mac'] = (!empty($_POST['mac']) ? urldecode($_POST['mac']) : '');
    $_SESSION['ip'] = (!empty($_POST['ip']) ? urldecode($_POST['ip']) : '');
    $_SESSION['host-ip'] = (!empty($_POST['host-ip']) ? urldecode($_POST['host-ip']) : '');
    $_SESSION['domain'] = (!empty($_POST['domain']) ? urldecode($_POST['domain']) : '');
    $_SESSION['server-name'] = (!empty($_POST['server-name']) ? urldecode($_POST['server-name']) : '');
    $_SESSION['server-address'] = (!empty($_POST['server-address']) ? urldecode($_POST['server-address']) : '');
    $_SESSION['hostname'] = (!empty($_POST['hostname']) ? urldecode($_POST['hostname']) : '');
    $_SESSION['identity'] = (!empty($_POST['identity']) ? urldecode($_POST['identity']) : '');
    $_SESSION['interface-name'] = (!empty($_POST['interface-name']) ? urldecode($_POST['interface-name']) : '');
    $_SESSION['link-login'] = (!empty($_POST['link-login']) ? urldecode($_POST['link-login']) : '');
    $_SESSION['link-orig'] = (!empty($_POST['link-orig']) ? urldecode($_POST['link-orig']) : '');
    $_SESSION['link-orig-esc'] = (!empty($_POST['link-orig-esc']) ? urldecode($_POST['link-orig-esc']) : '');
    $_SESSION['mac-esc'] = (!empty($_POST['mac-esc']) ? urldecode($_POST['mac-esc']) : '');
    $_SESSION['error'] = (!empty($_POST['error']) ? urldecode($_POST['error']) : '');
    $_SESSION['identity'] = (!empty($_POST['identity']) ? urldecode($_POST['identity']) : null);

    $log->info('Mikrotik belive session info', ['session' => $_SESSION, '$_POST' => $_POST]);
}

if ($url['dir1'] === $urlTree['stay-wifi-redirect'] && array_get($_SESSION,'triggered_hotspot')) {
    $log->info('Belive post', [
      'mac' => array_get($_SESSION, 'mac'),
      'habitacion' => array_get($_SESSION, 'roomNumber'),
      'documento' => array_get($_SESSION, 'card_id'),
      'last_name' => $_SESSION['user']['last_name'] ?? $_SESSION['last_name'] ?? '',
      'email' => array_get($_SESSION, 'user.email'),
      'url' => array_get($datosWifiHotel, 'form_url')
    ]); 
}


?>
<form name="redirect" class="hotelinking-wifi-login-form" action="<?php echo($datosWifiHotel['form_url'] ?? ''); ?>" method="post">
    <input type="hidden" name="mac" value="<?php echo($_SESSION['mac'] ?? '')?>">
    <input type="hidden" name="ip" value="<?php echo($_SESSION['ip'] ?? '')?>">
    <input type="hidden" name="host-ip" value="<?php echo($_SESSION['host-ip'] ?? '')?>">
    <input type="hidden" name="domain" value="<?php echo($_SESSION['domain'] ?? '')?>">
    <input type="hidden" name="server-name" value="<?php echo($_SESSION['server-name'] ?? '')?>">
    <input type="hidden" name="server-address" value="<?php echo($_SESSION['server-address'] ?? '')?>">
    <input type="hidden" name="hostname" value="<?php echo($_SESSION['hostname'] ?? '')?>">
    <input type="hidden" name="identity" value="<?php echo($_SESSION['identity'] ?? '')?>">
    <input type="hidden" name="interface-name" value="<?php echo($_SESSION['interface-name'] ?? '')?>">
    <input type="hidden" name="link-login" value="<?php echo($_SESSION['link-login'] ?? '') ?> ">
    <input type="hidden" name="link-orig" value="<?php echo($_SESSION['link-orig'] ?? '') ?> ">
    <input type="hidden" name="link-orig-esc" value="<?php echo($_SESSION['link-orig-esc'] ?? '') ?> ">
    <input type="hidden" name="mac-esc" value="<?php echo($_SESSION['mac-esc'] ?? '')?>">
    <input type="hidden" name="error" value="<?php echo($_SESSION['error'] ?? '')?>">
    <input type="hidden" name="habitacion" value="<?php echo($_SESSION['roomNumber'] ?? '')?>">
    <input type="hidden" name="documento-identidad" value="<?php echo($_SESSION['card_id'] ?? '');?>">
    <!-- Get last name from user, if not exists get from portalPro -->
    <input type="hidden" name="lastname" value="<?php echo($_SESSION['user']['last_name'] ?? $_SESSION['last_name'] ?? '');?>">
    <input type="hidden" name="email" value="<?php echo($_SESSION['user']['email'] ?? '');?>">
    <input type="hidden" name="provider" value="hotelinking">
</form>

<script src="<?php echo DIR_JS . 'cookies.min.js' ?>"></script>
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

