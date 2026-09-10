<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//If those parameters exists, store them in session
if (!empty($_POST) && !empty($_POST['mac'])) {
    //Set url login
    $_SESSION['url-login'] = (!empty($_POST['url-login']) ? urldecode($_POST['url-login']) : (!empty($datosWifiHotel['form_url']) ? $datosWifiHotel['form_url'] : "http://portal.nubifi.com/MktV4Login.asp"));
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

//If GET parameters exist, store them in session (similar to POST but also includes guest info)
if (!empty($_GET) && !empty($_GET['mac'])) {
    //Set url login
    $_SESSION['url-login'] = (!empty($_GET['url-login']) ? urldecode($_GET['url-login']) : (!empty($datosWifiHotel['form_url']) ? $datosWifiHotel['form_url'] : "http://portal.nubifi.com/MktV4Login.asp"));
    $_SESSION['hostname'] = (!empty($_GET['hostname']) ? urldecode($_GET['hostname']) : '');
    $_SESSION['identity'] = (!empty($_GET['identity']) ? urldecode($_GET['identity']) : '');

    // Handle both naming conventions for login-by/login_by
    $_SESSION['login-by'] = (!empty($_GET['login-by']) ? urldecode($_GET['login-by']) : (!empty($_GET['login_by']) ? urldecode($_GET['login_by']) : ''));

    // Handle both naming conventions for plain-passwd/plain_passwd
    $_SESSION['plain-passwd'] = (!empty($_GET['plain-passwd']) ? urldecode($_GET['plain-passwd']) : (!empty($_GET['plain_passwd']) ? urldecode($_GET['plain_passwd']) : ''));

    // Handle both naming conventions for server-address/server_address
    $_SESSION['server-address'] = (!empty($_GET['server-address']) ? urldecode($_GET['server-address']) : (!empty($_GET['server_address']) ? urldecode($_GET['server_address']) : ''));

    // Handle both naming conventions for server-name/server_name
    $_SESSION['server-name'] = (!empty($_GET['server-name']) ? urldecode($_GET['server-name']) : (!empty($_GET['server_name']) ? urldecode($_GET['server_name']) : ''));

    // Handle both naming conventions for ssl-login/ssl_login
    $_SESSION['ssl-login'] = (!empty($_GET['ssl-login']) ? urldecode($_GET['ssl-login']) : (!empty($_GET['ssl_login']) ? urldecode($_GET['ssl_login']) : ''));

    // Handle both naming conventions for interface-name/interface_name
    $_SESSION['interface-name'] = (!empty($_GET['interface-name']) ? urldecode($_GET['interface-name']) : (!empty($_GET['interface_name']) ? urldecode($_GET['interface_name']) : ''));

    // Handle both naming conventions for link-login/link_login
    $_SESSION['link-login'] = (!empty($_GET['link-login']) ? urldecode($_GET['link-login']) : (!empty($_GET['link_login']) ? urldecode($_GET['link_login']) : ''));

    // Handle both naming conventions for link-login-only/link_login_only
    $_SESSION['link-login-only'] = (!empty($_GET['link-login-only']) ? urldecode($_GET['link-login-only']) : (!empty($_GET['link_login_only']) ? urldecode($_GET['link_login_only']) : ''));

    // Handle both naming conventions for link-login-plain/link_login_plain
    $_SESSION['link-login-plain'] = (!empty($_GET['link-login-plain']) ? urldecode($_GET['link-login-plain']) : (!empty($_GET['link_login_plain']) ? urldecode($_GET['link_login_plain']) : ''));

    // Handle both naming conventions for link-logout/link_logout
    $_SESSION['link-logout'] = (!empty($_GET['link-logout']) ? urldecode($_GET['link-logout']) : (!empty($_GET['link_logout']) ? urldecode($_GET['link_logout']) : ''));

    // Handle both naming conventions for link-status/link_status
    $_SESSION['link-status'] = (!empty($_GET['link-status']) ? urldecode($_GET['link-status']) : (!empty($_GET['link_status']) ? urldecode($_GET['link_status']) : ''));

    // Handle both naming conventions for link-orig/link_orig
    $_SESSION['link-orig'] = (!empty($_GET['link-orig']) ? urldecode($_GET['link-orig']) : (!empty($_GET['link_orig']) ? urldecode($_GET['link_orig']) : ''));

    $_SESSION['domain'] = (!empty($_GET['domain']) ? urldecode($_GET['domain']) : '');
    $_SESSION['ip'] = (!empty($_GET['ip']) ? urldecode($_GET['ip']) : '');

    // Handle both naming conventions for logged-in/logged_in
    $_SESSION['logged-in'] = (!empty($_GET['logged-in']) ? urldecode($_GET['logged-in']) : (!empty($_GET['logged_in']) ? urldecode($_GET['logged_in']) : ''));

    $_SESSION['mac'] = (!empty($_GET['mac']) ? urldecode($_GET['mac']) : '');
    $_SESSION['trial'] = (!empty($_GET['trial']) ? urldecode($_GET['trial']) : '');
    $_SESSION['username'] = (!empty($_GET['username']) ? urldecode($_GET['username']) : '');
    $_SESSION['idle-timeout'] = (!empty($_GET['idle-timeout']) ? urldecode($_GET['idle-timeout']) : '');
    $_SESSION['idle-timeout-secs'] = (!empty($_GET['idle-timeout-secs']) ? urldecode($_GET['idle-timeout-secs']) : '');
    $_SESSION['limit-bytes-in'] = (!empty($_GET['limit-bytes-in']) ? urldecode($_GET['limit-bytes-in']) : '');
    $_SESSION['limit-bytes-out'] = (!empty($_GET['limit-bytes-out']) ? urldecode($_GET['limit-bytes-out']) : '');
    $_SESSION['refresh-timeout'] = (!empty($_GET['refresh-timeout']) ? urldecode($_GET['refresh-timeout']) : '');
    $_SESSION['refresh-timeout-secs'] = (!empty($_GET['refresh-timeout-secs']) ? urldecode($_GET['refresh-timeout-secs']) : '');
    $_SESSION['session-timeout'] = (!empty($_GET['session-timeout']) ? urldecode($_GET['session-timeout']) : '');
    $_SESSION['session-timeout-secs'] = (!empty($_GET['session-timeout-secs']) ? urldecode($_GET['session-timeout-secs']) : '');
    $_SESSION['session-time-left'] = (!empty($_GET['session-time-left']) ? urldecode($_GET['session-time-left']) : '');
    $_SESSION['session-time-left-secs'] = (!empty($_GET['session-time-left-secs']) ? urldecode($_GET['session-time-left-secs']) : '');
    $_SESSION['uptime'] = (!empty($_GET['uptime']) ? urldecode($_GET['uptime']) : '');
    $_SESSION['uptime-secs'] = (!empty($_GET['uptime-secs']) ? urldecode($_GET['uptime-secs']) : '');
    $_SESSION['session-id'] = (!empty($_GET['session-id']) ? urldecode($_GET['session-id']) : '');
    $_SESSION['var'] = (!empty($_GET['var']) ? urldecode($_GET['var']) : '');
    $_SESSION['error'] = (!empty($_GET['error']) ? urldecode($_GET['error']) : '');
    $_SESSION['error-orig'] = (!empty($_GET['error-orig']) ? urldecode($_GET['error-orig']) : '');
    $_SESSION['chap-id'] = (!empty($_GET['chap-id']) ? urldecode($_GET['chap-id']) : '');
    $_SESSION['chap-challenge'] = (!empty($_GET['chap-challenge']) ? urldecode($_GET['chap-challenge']) : '');

    // Additional GET-specific parameters for guest information
    $_SESSION['first_name'] = (!empty($_GET['first_name']) ? urldecode($_GET['first_name']) : '');
    $_SESSION['last_name'] = (!empty($_GET['last_name']) ? urldecode($_GET['last_name']) : '');
    $_SESSION['room_number'] = (!empty($_GET['room_number']) ? urldecode($_GET['room_number']) : '');

    // Additional fields from the form that weren't in the original POST handler
    $_SESSION['host_ip'] = (!empty($_GET['host_ip']) ? urldecode($_GET['host_ip']) : '');
    $_SESSION['client_os'] = (!empty($_GET['client_os']) ? urldecode($_GET['client_os']) : '');
    $_SESSION['client_lang'] = (!empty($_GET['client_lang']) ? urldecode($_GET['client_lang']) : '');
    $_SESSION['client_brow'] = (!empty($_GET['client_brow']) ? urldecode($_GET['client_brow']) : '');
    $_SESSION['server_ip'] = (!empty($_GET['server_ip']) ? urldecode($_GET['server_ip']) : '');
    $_SESSION['bw'] = (!empty($_GET['bw']) ? urldecode($_GET['bw']) : '');
}
$log->info('Cyberhoteles stayapp', ['session' => $_SESSION, 'POST' => $_POST, 'GET' => $_GET]);
?>
?>

<form name="form1" action="<?php echo !empty($_SESSION['url-login']) ? $_SESSION['url-login'] : 'http://portal.nubifi.com/MktV4Login.asp'; ?>" method="post" class="hotelinking-wifi-login-form">
    <input type="hidden" name="hostname" value="<?php echo !empty($_SESSION['hostname']) ? $_SESSION['hostname'] : '' ?>">
    <input type="hidden" name="identity" value="<?php echo !empty($_SESSION['identity']) ? $_SESSION['identity'] : '' ?>">
    <input type="hidden" name="login-by" value="<?php echo !empty($_SESSION['login-by']) ? $_SESSION['login-by'] : '' ?>">
    <input type="hidden" name="plain-passwd" value="<?php echo !empty($_SESSION['plain-passwd']) ? $_SESSION['plain-passwd'] : '' ?>">
    <input type="hidden" name="server-address" value="<?php echo !empty(array_get($_SESSION, 'server-address') ? array_get($_SESSION, 'server-address') : '') ?>">
    <input type="hidden" name="server-name" value="<?php echo !empty($_SESSION['server-name']) ? $_SESSION['server-name'] : '' ?>">
    <input type="hidden" name="ssl-login" value="<?php echo !empty($_SESSION['ssl-login']) ? $_SESSION['ssl-login'] : '' ?>">
    <input type="hidden" name="interface-name" value="<?php echo !empty($_SESSION['interface-name']) ? $_SESSION['interface-name'] : '' ?>">
    <input type="hidden" name="link-login" value="<?php echo !empty($_SESSION['link-login']) ? $_SESSION['link-login'] : '' ?>">
    <input type="hidden" name="link-login-only" value="<?php echo !empty($_SESSION['link-login-only']) ? $_SESSION['link-login-only'] : '' ?>">
    <input type="hidden" name="link-login-plain" value="<?php echo !empty($_SESSION['link-login-plain']) ? $_SESSION['link-login-plain'] : '' ?>">
    <input type="hidden" name="link-logout" value="<?php echo !empty($_SESSION['link-logout']) ? $_SESSION['link-logout'] : '' ?>">
    <input type="hidden" name="link-status" value="<?php echo !empty($_SESSION['link-status']) ? $_SESSION['link-status'] : '' ?>">
    <input type="hidden" name="link-orig" value="<?php echo !empty($_SESSION['link-orig']) ? $_SESSION['link-orig'] : '' ?>">
    <input type="hidden" name="domain" value="<?php echo !empty($_SESSION['domain']) ? $_SESSION['domain']  : '' ?>">
    <input type="hidden" name="ip" value="<?php echo !empty($_SESSION['ip']) ? $_SESSION['ip'] : '' ?>">
    <input type="hidden" name="logged-in" value="<?php echo !empty($_SESSION['logged-in']) ? $_SESSION['logged-in'] : '' ?>">
    <input type="hidden" name="mac" value="<?php echo !empty($_SESSION['mac']) ? $_SESSION['mac'] : '' ?>">
    <input type="hidden" name="trial" value="<?php echo !empty($_SESSION['trial']) ? $_SESSION['trial'] : '' ?>">
    <input type="hidden" name="username" value="<?php echo !empty($_SESSION['username']) ? $_SESSION['username'] : '' ?>">
    <input type="hidden" name="idle-timeout" value="<?php echo !empty($_SESSION['idle-timeout']) ? $_SESSION['idle-timeout'] : '' ?>">
    <input type="hidden" name="idle-timeout-secs" value="<?php echo !empty($_SESSION['idle-timeout-secs']) ? $_SESSION['idle-timeout-secs'] : '' ?>">
    <input type="hidden" name="limit-bytes-in" value="<?php echo !empty($_SESSION['limit-bytes-in']) ? $_SESSION['limit-bytes-in'] : '' ?>">
    <input type="hidden" name="limit-bytes-out" value="<?php echo !empty($_SESSION['limit-bytes-out']) ? $_SESSION['limit-bytes-out'] : '' ?>">
    <input type="hidden" name="refresh-timeout" value="<?php echo !empty($_SESSION['refresh-timeout']) ? $_SESSION['refresh-timeout'] : '' ?>">
    <input type="hidden" name="refresh-timeout-secs" value="<?php echo !empty($_SESSION['refresh-timeout-secs']) ? $_SESSION['refresh-timeout-secs'] : '' ?>">
    <input type="hidden" name="session-timeout" value="<?php echo !empty($_SESSION['session-timeout']) ? $_SESSION['session-timeout'] : '' ?>">
    <input type="hidden" name="session-timeout-secs" value="<?php echo !empty($_SESSION['session-timeout-secs']) ? $_SESSION['session-timeout-secs'] : '' ?>">
    <input type="hidden" name="session-time-left" value="<?php echo !empty($_SESSION['session-time-left']) ? $_SESSION['session-time-left'] : '' ?>">
    <input type="hidden" name="session-time-left-secs" value="<?php echo !empty($_SESSION['session-time-left-secs']) ? $_SESSION['session-time-left-secs'] : '' ?>">
    <input type="hidden" name="uptime" value="<?php echo !empty($_SESSION['uptime']) ? $_SESSION['uptime'] : '' ?>">
    <input type="hidden" name="uptime-secs" value="<?php echo !empty($_SESSION['uptime-secs']) ? $_SESSION['uptime-secs'] : '' ?>">
    <input type="hidden" name="session-id" value="<?php echo !empty($_SESSION['session-id']) ? $_SESSION['session-id'] : '' ?>">
    <input type="hidden" name="var" value="<?php echo !empty($_SESSION['var']) ? $_SESSION['var'] : '' ?>">
    <input type="hidden" name="error" value="<?php echo !empty($_SESSION['error']) ? $_SESSION['error'] : '' ?>">
    <input type="hidden" name="error-orig" value="<?php echo !empty($_SESSION['error-orig']) ? $_SESSION['error-orig'] : '' ?>">
    <input type="hidden" name="chap-id" value="<?php echo !empty($_SESSION['chap-id']) ? $_SESSION['chap-id'] : '' ?>">
    <input type="hidden" name="chap-challenge" value="<?php echo !empty($_SESSION['chap-challenge']) ? $_SESSION['chap-challenge'] : '' ?>">
</form>
<script src="<?php echo DIR_JS . 'cookies.min.js' ?>"></script>
<script>
    $(document).ready(function () {
        // Check if QR redirect is enabled
        var qrRedirectEnabled = <?php echo (isset($_SESSION['portalConfig']) && !empty($_SESSION['portalConfig']->qr_redirect)) ? 'true' : 'false'; ?>;
        var portalRedirectUrl = '<?php echo !empty($_SESSION['portalRedirectUrl']) ? $_SESSION['portalRedirectUrl'] : ''; ?>';

        //must have a button with class send-login-button in the template
        HLevents.subscribe('wifi-redirect', function(obj){
            // Check if QR redirect is enabled
            if (qrRedirectEnabled && portalRedirectUrl) {
                // Redirect directly to portal redirect URL
                window.location.href = portalRedirectUrl;
            } else {
                // Send log via form submission
                $('.hotelinking-wifi-login-form').submit();
            }
        })
    })
</script>
<!-- <script>
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
</script> -->
