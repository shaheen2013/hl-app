<?php
//No direct access allowed
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//In this integration we take the data from the referer, so if the referer exists:
if (!empty($_SERVER['HTTP_REFERER']) && empty($_SESSION["urlLogin"]) && empty($_SESSION["url"])) {
    //Take the host
    $refererHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    $_SESSION['urlLogin'] = (!empty($refererHost) ? $refererHost : "");

    //Take the URL parameter
    $parts = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($parts['query'] ?? "", $query);
    $_SESSION['url'] = (!empty($query['url']) ? $query['url'] : "");
}
?>

<form name="weblogin_form" id="logon" class="hotelinking-wifi-login-form" action="http://<?php echo array_get($_SESSION, 'urlLogin') . '/reg.php'?>" method="post">
    <input type="hidden" name="Submit" value="Accept">
    <input type="hidden" name="checkbox" value="checkbox">
    <input type="hidden" name="url" value="<?php echo array_get($_SESSION, "url"); ?>" />
</form>

<script>
    
    $(document).ready(function () {
        //when HLevents sends the wifi-redirect msg
        // create cookie and send the form to router
        HLevents.subscribe('wifi-redirect', function(obj){
            //submit the mikrotik form to auth the user
            //Send log
;
            $('.hotelinking-wifi-login-form').submit();
        })
    });
</script>
