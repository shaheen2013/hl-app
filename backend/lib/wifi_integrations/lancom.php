<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//If those parameters exists, store them in session
if (!empty($_GET) && !empty($_GET['mac'])) {
    $_SESSION['mac'] = (!empty($_GET['mac']) ? urldecode($_GET['mac']) : '');
    $_SESSION['loginip'] = (!empty($_GET['loginip']) ? urldecode($_GET['loginip']) : '');
    $_SESSION['nasid'] = (!empty($_GET['nasid']) ? urldecode($_GET['nasid']) : '');
    $_SESSION['url'] = (!empty($_GET['url']) ? urldecode($_GET['url']) : '');
    $_SESSION['ssid'] = (!empty($_GET['ssid']) ? urldecode($_GET['ssid']) : '');
    $_SESSION['routetag'] = (!empty($_GET['routetag']) ? urldecode($_GET['routetag']) : '');
    $_SESSION['vlan'] = (!empty($_GET['vlan']) ? urldecode($_GET['vlan']) : '');

    $log->info('Lancom session info', ['session' => $_SESSION, '$_GET' => $_GET]);
}

$log->debug('Lancom session info', [
    'session' => $_SESSION,
    '$_GET' => $_GET,
    'form' => [
        'url'=> $_SESSION['loginip'] ?? 'no url set',
        'username' => $datosWifiHotel['username'],
        'password'=> $datosWifiHotel['password']
    ]
]);

?>
<form class="hotelinking-wifi-login-form" action="http://<?php echo(!empty($_SESSION['loginip']) ? $_SESSION['loginip'] : 'no url set') ?>/authen/login/" method="post">
    <input type="hidden" name="refreshhost" value="<?php echo(!empty($_SESSION['url']) ? parse_url($_SESSION['url'], PHP_URL_HOST) : 'google.com') ?>">
    <input type="hidden" name="userid" value="<?php echo $datosWifiHotel['username'] ?>">
    <input type="hidden" name="password" value="<?php echo $datosWifiHotel['password'] ?>">
    <input type="hidden" name="refreshssl" value="0">
</form>

<script>
    $(document).ready(function () {
        //when HLevents sends the wifi-redirect msg 
        HLevents.subscribe('wifi-redirect', function(obj){
            //Send log
            $('.hotelinking-wifi-login-form').submit();
        }) 
    });
</script>
