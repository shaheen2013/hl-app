<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//If those parameters exists, store them in session
if (!empty($_POST) && !empty($_POST['mac'])) {
    //Set url login
    $_SESSION['url-login'] = (!empty($_POST['url-login']) ? urldecode($_POST['url-login']) : 'http://portal.nubifi.com/MktV4Login.asp');
    $_SESSION['hostname'] = (!empty($_POST['hostname']) ? urldecode($_POST['hostname']) : '');
    $_SESSION['identity'] = (!empty($_POST['identity']) ? urldecode($_POST['identity']) : '');
    $_SESSION['login-by'] = (!empty($_POST['login-by']) ? urldecode($_POST['login-by']) : '');
    $_SESSION['plain-passwd'] = (!empty($_POST['plain-passwd']) ? urldecode($_POST['plain-passwd']) : '');
    $_SESSION['server-address'] = (!empty($_POST['server-address']) ? urldecode($_POST['server-address']) : '');
    $_SESSION['server-name'] = (!empty($_POST['server-name']) ? urldecode($_POST['server-name']) : '');
    $_SESSION['ssl-login'] = (!empty($_POST['ssl-login']) ? urldecode($_POST['ssl-login']) : '');
    $_SESSION['interface-name'] = (!empty($_POST['interface-name']) ? urldecode($_POST['interface-name']) : '');
    $_SESSION['link-login'] = (!empty($_POST['link-login']) ? urldecode($_POST['link-login']) : '');
    $_SESSION['link-login-only'] = (!empty($_POST['link-login-only']) ? urldecode($_POST['link-login-only']) : '');
    $_SESSION['link-login-plain'] = (!empty($_POST['link-login-plain']) ? urldecode($_POST['link-login-plain']) : '');
    $_SESSION['link-logout'] = (!empty($_POST['link-logout']) ? urldecode($_POST['link-logout']) : '');
    $_SESSION['link-status'] = (!empty($_POST['link-status']) ? urldecode($_POST['link-status']) : '');
    $_SESSION['link-orig'] = (!empty($_POST['link-orig']) ? urldecode($_POST['link-orig']) : '');
    $_SESSION['domain'] = (!empty($_POST['domain']) ? urldecode($_POST['domain']) : '');
    $_SESSION['ip'] = (!empty($_POST['ip']) ? urldecode($_POST['ip']) : '');
    $_SESSION['logged-in'] = (!empty($_POST['logged-in']) ? urldecode($_POST['logged-in']) : '');
    $_SESSION['mac'] = (!empty($_POST['mac']) ? urldecode($_POST['mac']) : '');
    $_SESSION['trial'] = (!empty($_POST['trial']) ? urldecode($_POST['trial']) : '');
    $_SESSION['username'] = (!empty($_POST['username']) ? urldecode($_POST['username']) : '');
    $_SESSION['idle-timeout'] = (!empty($_POST['idle-timeout']) ? urldecode($_POST['idle-timeout']) : '');
    $_SESSION['idle-timeout-secs'] = (!empty($_POST['idle-timeout-secs']) ? urldecode($_POST['idle-timeout-secs']) : '');
    $_SESSION['limit-bytes-in'] = (!empty($_POST['limit-bytes-in']) ? urldecode($_POST['limit-bytes-in']) : '');
    $_SESSION['limit-bytes-out'] = (!empty($_POST['limit-bytes-out']) ? urldecode($_POST['limit-bytes-out']) : '');
    $_SESSION['refresh-timeout'] = (!empty($_POST['refresh-timeout']) ? urldecode($_POST['refresh-timeout']) : '');
    $_SESSION['refresh-timeout-secs'] = (!empty($_POST['refresh-timeout-secs']) ? urldecode($_POST['refresh-timeout-secs']) : '');
    $_SESSION['session-timeout'] = (!empty($_POST['session-timeout']) ? urldecode($_POST['session-timeout']) : '');
    $_SESSION['session-timeout-secs'] = (!empty($_POST['session-timeout-secs']) ? urldecode($_POST['session-timeout-secs']) : '');
    $_SESSION['session-time-left'] = (!empty($_POST['session-time-left']) ? urldecode($_POST['session-time-left']) : '');
    $_SESSION['session-time-left-secs'] = (!empty($_POST['session-time-left-secs']) ? urldecode($_POST['session-time-left-secs']) : '');
    $_SESSION['uptime'] = (!empty($_POST['uptime']) ? urldecode($_POST['uptime']) : '');
    $_SESSION['uptime-secs'] = (!empty($_POST['uptime-secs']) ? urldecode($_POST['uptime-secs']) : '');
    $_SESSION['session-id'] = (!empty($_POST['session-id']) ? urldecode($_POST['session-id']) : '');
    $_SESSION['var'] = (!empty($_POST['var']) ? urldecode($_POST['var']) : '');
    $_SESSION['error'] = (!empty($_POST['error']) ? urldecode($_POST['error']) : '');
    $_SESSION['error-orig'] = (!empty($_POST['error-orig']) ? urldecode($_POST['error-orig']) : '');
    $_SESSION['chap-id'] = (!empty($_POST['chap-id']) ? urldecode($_POST['chap-id']) : '');
    $_SESSION['chap-challenge'] = (!empty($_POST['chap-challenge']) ? urldecode($_POST['chap-challenge']) : '');
}
?>

<form name="form1" action="<?php echo $_SESSION['url-login'] ?? 'http://portal.nubifi.com/MktV4Login.asp' ?>" method="post" class="hotelinking-wifi-login-form">
    <input type="hidden" name="hostname" value="<?php echo $_SESSION['hostname'] ?? '' ?>">
    <input type="hidden" name="identity" value="<?php echo $_SESSION['identity'] ?? '' ?>">
    <input type="hidden" name="login-by" value="<?php echo $_SESSION['login-by'] ?? '' ?>">
    <input type="hidden" name="plain-passwd" value="<?php echo $_SESSION['plain-passwd'] ?? '' ?>">
    <input type="hidden" name="server-address" value="<?php echo $_SESSION['server-address'] ?? '' ?>">
    <input type="hidden" name="server-name" value="<?php echo $_SESSION['server-name'] ?? '' ?>">
    <input type="hidden" name="ssl-login" value="<?php echo $_SESSION['ssl-login'] ?? '' ?>">
    <input type="hidden" name="interface-name" value="<?php echo $_SESSION['interface-name'] ?? '' ?>">
    <input type="hidden" name="link-login" value="<?php echo $_SESSION['link-login'] ?? '' ?>">
    <input type="hidden" name="link-login-only" value="<?php echo $_SESSION['link-login-only'] ?? '' ?>">
    <input type="hidden" name="link-login-plain" value="<?php echo $_SESSION['link-login-plain'] ?? '' ?>">
    <input type="hidden" name="link-logout" value="<?php echo $_SESSION['link-logout'] ?? '' ?>">
    <input type="hidden" name="link-status" value="<?php echo $_SESSION['link-status'] ?? '' ?>">
    <input type="hidden" name="link-orig" value="<?php echo $_SESSION['link-orig'] ?? '' ?>">
    <input type="hidden" name="domain" value="<?php echo $_SESSION['domain'] ?? '' ?>">
    <input type="hidden" name="ip" value="<?php echo $_SESSION['ip'] ?? '' ?>">
    <input type="hidden" name="logged-in" value="<?php echo $_SESSION['logged-in'] ?? '' ?>">
    <input type="hidden" name="mac" value="<?php echo $_SESSION['mac'] ?? '' ?>">
    <input type="hidden" name="trial" value="<?php echo $_SESSION['trial'] ?? '' ?>">
    <input type="hidden" name="username" value="<?php echo $_SESSION['username'] ?? '' ?>">
    <input type="hidden" name="idle-timeout" value="<?php echo $_SESSION['idle-timeout'] ?? '' ?>">
    <input type="hidden" name="idle-timeout-secs" value="<?php echo $_SESSION['idle-timeout-secs'] ?? '' ?>">
    <input type="hidden" name="limit-bytes-in" value="<?php echo $_SESSION['limit-bytes-in'] ?? '' ?>">
    <input type="hidden" name="limit-bytes-out" value="<?php echo $_SESSION['limit-bytes-out'] ?? '' ?>">
    <input type="hidden" name="refresh-timeout" value="<?php echo $_SESSION['refresh-timeout'] ?? '' ?>">
    <input type="hidden" name="refresh-timeout-secs" value="<?php echo $_SESSION['refresh-timeout-secs'] ?? '' ?>">
    <input type="hidden" name="session-timeout" value="<?php echo $_SESSION['session-timeout'] ?? '' ?>">
    <input type="hidden" name="session-timeout-secs" value="<?php echo $_SESSION['session-timeout-secs'] ?? '' ?>">
    <input type="hidden" name="session-time-left" value="<?php echo $_SESSION['session-time-left'] ?? '' ?>">
    <input type="hidden" name="session-time-left-secs" value="<?php echo $_SESSION['session-time-left-secs'] ?? '' ?>">
    <input type="hidden" name="uptime" value="<?php echo $_SESSION['uptime'] ?? '' ?>">
    <input type="hidden" name="uptime-secs" value="<?php echo $_SESSION['uptime-secs'] ?? '' ?>">
    <input type="hidden" name="session-id" value="<?php echo $_SESSION['session-id'] ?? '' ?>">
    <input type="hidden" name="var" value="<?php echo $_SESSION['var'] ?? '' ?>">
    <input type="hidden" name="error" value="<?php echo $_SESSION['error'] ?? '' ?>">
    <input type="hidden" name="error-orig" value="<?php echo $_SESSION['error-orig'] ?? '' ?>">
    <input type="hidden" name="chap-id" value="<?php echo $_SESSION['chap-id'] ?? '' ?>">
    <input type="hidden" name="chap-challenge" value="<?php echo $_SESSION['chap-challenge'] ?? '' ?>">
    <input type="hidden" name="room-number" value="<?php echo array_get($_SESSION, 'roomNumber') ?? '' ?>">
</form>
<script src="<?php echo DIR_JS . 'cookies.min.js' ?>"></script>
<script>
    $(document).ready(function () {
        //If cookie, he/she connected before, so connect again
        if (retrieve_cookie('wifiConnect')) {
            $('.stay-share-content').hide();
            $(".ripple, .connection-text").addClass("animated dblock bounceIn");
            setTimeout(function () {
                //Send log

                $('.hotelinking-wifi-login-form').submit();
            }, 1000);
        }
        //must have a button with class send-login-button in the template
        // $('.send-login-button').click(function () {
            HLevents.subscribe('wifi-redirect', function(obj){
                create_cookie('wifiConnect', 'true', 15);
                //Send log
                $('.hotelinking-wifi-login-form').submit();
            })
        // })
    })
</script>
