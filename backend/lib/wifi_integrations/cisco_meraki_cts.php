<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

/*
 * PARAMETERS
 *
 * base_grant_url       // Url to grant user access (?)
 * user_continue_url    // Url to redirect user
 * node_mac             // Mac address of node
 * client_ip            // Client Ip
 * client_mac           // Client mac
 * session_duration     // IMPORTANT
*/

$session_duration = 864000;     //10 days

//If those parameters exists, store them in session
if ($_GET && isset($_GET['base_grant_url']) && isset($_GET['user_continue_url'])) {

    $_SESSION['base_grant_url'] = $_GET['base_grant_url'];
    $_SESSION['mac'] = (!empty($_GET['client_mac']) ? urldecode($_GET['client_mac']) : '');

    if ($datosWifiHotel['url'] != '') {
        $_SESSION['user_continue_url'] = $datosWifiHotel['url'];
    } else {
        $_SESSION['user_continue_url'] = $_GET['user_continue_url'];
    }


    // TODO if not session, redirect to splash page

}

$continueUrl = isset($_SESSION['portalRedirectUrl']) ? $_SESSION['portalRedirectUrl'] : array_get($_SESSION, 'user_continue_url');

//build query
$query = array(
    'continue_url' => $continueUrl
);

?>


<script>
    $(document).ready(function() {
        //template must have a button with this class
        // $('.send-login-button').click(function () {
        HLevents.subscribe('wifi-redirect', function(obj) {
            //Send log
            window.location.href = "<?php echo array_get($_SESSION, 'base_grant_url') ?>?<?php echo http_build_query($query) ?>";
        })
        // })
    })
</script>