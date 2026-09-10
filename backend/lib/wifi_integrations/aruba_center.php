<?php
//No direct access allowed
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//If those parameters exists, store them in session
$_SESSION['aruba_cmd'] = array_get($_SESSION, 'aruba_cmd', array_get($_GET, 'cmd', ''));
$_SESSION['aruba_switchip'] = array_get($_SESSION, 'aruba_switchip', array_get($_GET, 'switchip', ''));
$_SESSION['aruba_url'] = array_get($_SESSION, 'aruba_url', array_get($_GET, 'url', ''));
$_SESSION['aruba_essid'] = array_get($_SESSION, 'aruba_essid', array_get($_GET, 'essid', ''));
$_SESSION['aruba_ip'] = array_get($_SESSION, 'aruba_ip', array_get($_GET, 'ip', ''));
$_SESSION['mac'] = array_get($_SESSION, 'mac', macFormatter(array_get($_GET, 'mac', '')));

if($url['dir1'] == 'stay-wifi-redirect' && $trigger_integration){

?>

    <form name="weblogin" class="hotelinking-wifi-login-form" action="http://<?php echo array_get($_SESSION, 'aruba_switchip').'/cgi-bin/login'; ?>" method="post">
        <input name=cmd type="hidden" value="<?php echo array_get($_SESSION, 'aruba_cmd'); ?>" />
        <input name=mac type="hidden" value="<?php echo array_get($_SESSION, 'mac'); ?>" />
        <input name=ip type="hidden" value="<?php echo array_get($_SESSION, 'aruba_ip'); ?>" />
        <input name=essid type="hidden" value="<?php echo array_get($_SESSION, 'aruba_essid'); ?>" />
        <input name=url type="hidden" value="<?php echo array_get($_SESSION, 'aruba_url'); ?>" />
        <input name="authentication" type="hidden" value="Hotel1nk1ng$">
        <input name="login" type="hidden" value="login" />
    </form>

    <script>
        
        $(document).ready(function () {
            //when HLevents sends the wifi-redirect msg
            // create cookie and send the form to router
            HLevents.subscribe('wifi-redirect', function(obj){
                //submit the mikrotik form to auth the user
                //Send log

                window.location.href = <?php echo array_get($datosWifiHotel, 'url'); ?>
            })
        });
        
    </script>

<?php

}

?>