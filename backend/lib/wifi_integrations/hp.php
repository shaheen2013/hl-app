<?php

    // Getting hotspot parameters
    $_SESSION['mac'] = array_get($_GET, 'usermac', array_get($_SESSION, 'mac', FALSE));
    $hotspotIp = $_SESSION['hotspotIp'] = array_get($_GET, 'nasip', array_get($_SESSION, 'hotspotIp', FALSE));

    // Getting user parameters
    $username = $datosWifiHotel['username'];
    $password = $datosWifiHotel['password'];

?>

<form class="hotelinking-wifi-login-form" action="http://<?php echo $hotspotIp; ?>/portal/logon.cgi" method="post" >
    <input type="hidden" name="PtUser" value="<?php echo $username ?>">
    <input type="hidden" name="PtPwd" value="<?php echo $password ?>">
    <input type="hidden" name="PtButton" value="Logon">
</form>

<script type="text/javascript">

    $(document).ready(function () {
        HLevents.subscribe('wifi-redirect', function(obj){
            //Send log

            $('.hotelinking-wifi-login-form').submit();
        })
    })

</script>