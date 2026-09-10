<?php

if (!empty($_GET) && !empty($_GET['gw_addr'])) {
		
    $mpIdx = $_SESSION['mpIdx'] = (!empty($_GET['mpIdx']) ? $_GET['mpIdx'] : null);
    $loginurl = $_SESSION['gw_addr'] = (!empty($_GET['gw_addr']) ? urldecode($_GET['gw_addr']) . '/login.cgi' : null);

}

?>

        <form id="userAuthorizationForm" class="hotelinking-wifi-login-form" action="<?php echo array_get($_SESSION, 'gw_addr') ?>" method="POST" name="form">
            <input type="hidden" name="myusername" size="25" value="<?php echo $datosWifiHotel['username'] ?>">
			<input type="hidden" name="mypassword" size="25" value="<?php echo $datosWifiHotel['password'] ?>">
            <input type="hidden" name="mpIdx" size="25" value="<?php echo array_get($_SESSION, 'mpIdx') ?>">
        </form>

        <script type="text/javascript">

            $(document).ready(function () {
                //must have a button with class send-login-button in the template
                // $('.send-login-button').click(function(){
                    HLevents.subscribe('wifi-redirect', function(obj){
                       //Send log

                        $('.hotelinking-wifi-login-form').submit();
                    })
                // })
            })

        </script>
