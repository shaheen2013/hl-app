<?php

if (!defined('INDEXCONTROLVAL')) {

    echo 'No direct access allowed.';

    exit;

}



//If those parameters exists, store them in session

if (!empty($_GET) && !empty($_GET['mac'])) {

    $_SESSION['mac'] = (!empty($_GET['mac']) ? urldecode($_GET['mac']) : '');

    $_SESSION['ip'] = (!empty($_GET['ip']) ? urldecode($_GET['ip']) : '');

    $_SESSION['uamip'] = (!empty($_GET['uamip']) ? urldecode($_GET['uamip']) : '');

    $_SESSION['uamport'] = (!empty($_GET['uamport']) ? urldecode($_GET['uamport']) : '');

    $_SESSION['userurl'] = (!empty($_GET['userurl']) ? urldecode($_GET['userurl']) : '');   

}



?>

<form name="redirect" class="hotelinking-wifi-login-form" action="<?php echo array_get($_SESSION, 'uamip') . ':' . array_get($_SESSION, 'uamport') . '/login'; ?>" method="post">

    <input id="username" type="hidden" name="username" value="<?php echo $datosWifiHotel['username'] ?>">

    <input type="hidden" name="password" value="<?php echo $datosWifiHotel['password'] ?>">

</form>



<script>

    $(document).ready(function () {



        //when HLevents sends the wifi-redirect msg 

        // create cookie and send the form to router

        HLevents.subscribe('wifi-redirect', function(obj){

            //Send log

            $('.hotelinking-wifi-login-form').submit();

        }) 



    });

</script>