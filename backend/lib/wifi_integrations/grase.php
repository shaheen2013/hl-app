
<?php

if (!empty($_GET) && !empty($_GET["challenge"])) {
    $challenge = $_SESSION["challenge"] = (!empty($_GET["challenge"]) ? $_GET["challenge"] : null);
    $action = $_SESSION["action"] = "http:\/\/" . $_GET["uamip"] . "/grase/uam/nojslogin.php";
    $mac = $_SESSION["mac"] = (!empty($_GET["mac"]) ? urldecode($_GET["mac"]) : null);
    $redirurl = $_SESSION["redirurl"] = (!empty($_GET["userurl"]) ? $_GET["userurl"] : "https:\/\/www.google.com");
}

?>

<form id="userAuthorizationForm" class="hotelinking-wifi-login-form" action="<?php echo array_get($_SESSION, 'action') ?>" method="POST" name="form">

    <input type="hidden" name="username" value="<?php echo $datosWifiHotel['username']; ?>">
    <input type="hidden" name="password" value="<?php echo $datosWifiHotel['password']; ?>">
    <input type="hidden" name="challenge" value="<?php echo array_get($_SESSION, "challenge") ?>">
    <input type="hidden" name="userurl" value="<?php echo array_get($_SESSION, "redirurl") ?>">

</form>



<script>
$(document).ready(function () {

    //when HLevents sends the wifi-redirect msg
    HLevents.subscribe('wifi-redirect', function (obj) {

        //Send log
        $('.hotelinking-wifi-login-form').submit();

    })

});
</script>