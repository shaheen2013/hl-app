<?php
//Esto es un cambio
if (!empty($_GET) && !empty($_GET['mp_idx'])) {

		
    $mp_idx = $_SESSION['mp_idx'] = (!empty($_GET['mp_idx']) ? $_GET['mp_idx'] : null);
    $loginurl = $_SESSION['gw_addr'] = (!empty($_GET['gw_addr']) ? urldecode($_GET['gw_addr']) . '/agree.cgi' : null);
    $_SESSION['mac'] = (!empty($_GET['client_mac']) ? urldecode($_GET['client_mac']) : null);

}

?>

        <form id="userAuthorizationForm" class="hotelinking-wifi-login-form" action="<?php echo array_get($_SESSION, 'gw_addr') ?>" method="POST" name="form">

            <input type="hidden" name="fieldname"  value="hotelinking">
            <input type="hidden" name="agree" value="Agree">
            <input type="hidden" name="mp_idx" size="25" value="<?php echo array_get($_SESSION, 'mp_idx') ?>">

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
