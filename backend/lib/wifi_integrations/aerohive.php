<?php
//No direct access allowed
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//If those parameters exists, store them in session
// http://app.hotelinking.com/stay-share/b379f4a6-899a-4ead-bb81-d4fee67bf8b1/?urllogin=1.1.1.1&mypath=reg.php&mac=c0:ee:fb:26:62:4b
$_SESSION['urlLogin'] = array_get($_SESSION, 'urlLogin', array_get($_GET, 'urllogin', ''));
$_SESSION['myPath'] = array_get($_SESSION, 'mypath', array_get($_GET, 'mypath', ''));
$_SESSION['mac'] = array_get($_SESSION, 'mac', macFormatter(array_get($_GET, 'mac', '')));

?>

<form name="weblogin" class="hotelinking-wifi-login-form" action="http://<?php echo array_get($_SESSION, 'urlLogin') . '/' . array_get($_SESSION, 'myPath')?>" method="post">
    <input type="hidden" name="Submit" value="Accept">
    <input type="hidden" name="checkbox" id="checkbox" value="checkbox" />
</form>

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
