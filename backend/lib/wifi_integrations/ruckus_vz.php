<?php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

// Example url Ruckus_vz: https://app.hotelinking.com/stay-share/d92fdb86-8432-4eaa-9c7c-4df30fdae7ac/?sip=192.168.10.10&mac=1cb9c40639a0&client_mac=e0f84701def2&uip=10.0.10.230&lid=&dn=&url=http%3a%2f%2fwww.gstatic.com%2fgenerate_204&ssid=HPE+FREE&loc=&vlan=10

// The MAC address of client.
$_SESSION['mac'] = array_get($_SESSION, 'mac', macFormatter(array_get($_GET, 'client_mac', '')));

// The IP the Hotspot Ruckus.
$_SESSION['ruckus_vz_sip'] = array_get($_GET, 'sip', array_get($_SESSION, 'ruckus_vz_sip', FALSE));

?>

<form action="http://<?php echo array_get($_SESSION, 'ruckus_vz_sip') ?>:9997/SubscriberPortal/hotspotlogin" method="POST" class="hotelinking-wifi-login-form">
    <input type="hidden" name="username" value="<?php echo array_get($datosWifiHotel, 'username') ?>">
    <input type="hidden" name="password" value="<?php echo array_get($datosWifiHotel, 'password') ?>">
</form>

<script>
    $(document).ready(function () {
        //template must have a button with this class
        // $('.send-login-button').click(function () {
            HLevents.subscribe('wifi-redirect', function(obj){
                //Send log
                $('.hotelinking-wifi-login-form').submit();
            })
        // })
    })
</script>