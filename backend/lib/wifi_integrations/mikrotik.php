<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
global $log;
//If those parameters exists, store them in session
if (!empty($_POST) && !empty($_POST['mac'])) {
    global $log;
    $_SESSION['mac'] = (!empty($_POST['mac']) ? urldecode($_POST['mac']) : '');
    $_SESSION['ip'] = (!empty($_POST['ip']) ? urldecode($_POST['ip']) : '');
    $_SESSION['link-login'] = (!empty($_POST['link-login']) ? urldecode($_POST['link-login']) : '');
    $_SESSION['link-orig'] = (!empty($_POST['link-orig']) ? urldecode($_POST['link-orig']) : '');
    $_SESSION['error'] = (!empty($_POST['error']) ? urldecode($_POST['error']) : '');
    $_SESSION['chap-id'] = (!empty($_POST['chap_id']) ? urldecode($_POST['chap-id']) : '');
    $_SESSION['chap-challenge'] = (!empty($_POST['chap-challenge']) ? urldecode($_POST['chap-challenge']) : '');
    $_SESSION['link-login-only'] = (!empty($_POST['link-login-only']) ? urldecode($_POST['link-login-only']) : '');
    $_SESSION['link-orig-esc'] = (!empty($_POST['link-orig-esc']) ? urldecode($_POST['link-orig-esc']) : '');
    $_SESSION['mac-esc'] = (!empty($_POST['mac-esc']) ? urldecode($_POST['mac-esc']) : '');
    $_SESSION['identity'] = (!empty($_POST['identity']) ? urldecode($_POST['identity']) : null);

    // Store additional PMS parameters if present
    if (!empty($_POST['first_name'])) {
        $_SESSION['first_name'] = urldecode($_POST['first_name']);
    }
    if (!empty($_POST['last_name'])) {
        $_SESSION['last_name'] = urldecode($_POST['last_name']);
    }
    if (!empty($_POST['room_number'])) {
        $_SESSION['room_number'] = urldecode($_POST['room_number']);
    }

    $log->info('Mikrotik session info', ['session' => $_SESSION, '$_POST' => $_POST]);
}
//If POST trial exists
if ((!empty($_POST['trial']) && $_POST['trial'] == 'yes')) {
    //Define trial session
    $_SESSION['trial'] = 1;
}


$log->debug('Mikrotik session info', [
    'session' => $_SESSION,
    '$_POST' => $_POST,
    'form' => [
        'url' => $_SESSION['link-login-only'] ?? 'no url set',
        'portalRedirectUrl' => $_SESSION['portalRedirectUrl'] ?? null,
        'username' => $_SESSION['customer'] ?? false ? $datosWifiHotel['username'] : 'guest',
        'password' => $datosWifiHotel['password']
    ]
]);

function getUrlRedirectFromPortalConfig()
{
    $brandProducts = $_SESSION['brandProducts'];
    $portalConfigId = 24; //Corresponds to the id of the portal product
    foreach ($brandProducts as $product) {
        if ($product['id'] == $portalConfigId && !empty($product['config'])) {
            $config = json_decode($product['config'], true);
            if (isset($config['url_redirect'])) {
                return $config['url_redirect'];
            }
        }
    }
    return null;
}

function getLinkRedirectUrl()
{
    $portalConfigUrl = getUrlRedirectFromPortalConfig();
    $portalRedirectConfigUrl = !empty($_SESSION['portalRedirectUrl']) ? $_SESSION['portalRedirectUrl'] : null;
    $linkOrigUrl = !empty($_SESSION['link-orig']) ? $_SESSION['link-orig'] : null;

    if (!empty($portalRedirectConfigUrl)) {
        $_SESSION['link-redirect'] = $portalRedirectConfigUrl;
    } else if (!empty($portalConfigUrl)) {
        $_SESSION['link-redirect'] = $portalConfigUrl;
    } else if (!empty($linkOrigUrl)) {
        $_SESSION['link-redirect'] = $linkOrigUrl;
    } else {
        $_SESSION['link-redirect'] = '';
    }
}

getLinkRedirectUrl();

?>
<form name="redirect" class="hotelinking-wifi-login-form" action="<?php echo (!empty($_SESSION['link-login-only']) ? $_SESSION['link-login-only'] : 'no url set') ?>" method="post">
    <input type="hidden" name="mac" value="<?php echo (!empty($_SESSION['mac']) ? $_SESSION['mac'] : '') ?>">
    <input type="hidden" name="ip" value="<?php echo (!empty($_SESSION['ip']) ? $_SESSION['ip'] : '') ?>">
    <?php if (empty($_SESSION['trial'])) : ?>
        <?php if ($datosWifiHotel['guest_enabled'] ?? false) : ?>
            <input id="username" type="hidden" name="username" value="<?php echo (($_SESSION['customer'] ?? false) ? $datosWifiHotel['username'] : 'guest') ?>">
        <?php else : ?>
            <input id="username" type="hidden" name="username" value="<?php echo ($datosWifiHotel['username']) ?>">
        <?php endif; ?>
        <input type="hidden" name="password" value="<?php echo $datosWifiHotel['password'] ?>">
    <?php else : ?>
        <input id="trial_username" type="hidden" name="username" value="T-<?php echo (!empty($_SESSION['mac']) ? $_SESSION['mac'] : '') ?>">
        <input type="hidden" name="password" value="">
    <?php endif; ?>
    <?php if (!empty($_SESSION['portalRedirectUrl'] ?? null)) { ?>
        <!-- Add DST to make redirect on mikrotik using the portalRedirectUrl -->
        <input type="hidden" name="dst" value="<?php echo ($_SESSION['portalRedirectUrl']) ?>">
    <?php } ?>
    <input type="hidden" name="link-login" value="<?php echo (!empty($_SESSION['link-login']) ? $_SESSION['link-login'] : '') ?>">
    <input type="hidden" name="link-orig" value="<?php echo (!empty($_SESSION['link-orig']) ? $_SESSION['link-orig'] : '') ?>">
    <input type="hidden" name="error" value="<?php echo (!empty($_SESSION['error']) ? $_SESSION['error'] : '') ?>">
    <input type="hidden" name="chap-id" value="<?php echo (!empty($_SESSION['chap-id']) ? $_SESSION['chap-id'] : '') ?>">
    <input type="hidden" name="chap-challenge" value="<?php echo (!empty($_SESSION['chap-challenge']) ? $_SESSION['chap-challenge'] : '') ?>">
    <input type="hidden" name="link-login-only" value="<?php echo (!empty($_SESSION['link-login-only']) ? $_SESSION['link-login-only'] : '') ?>">
    <input type="hidden" name="link-orig-esc" value="<?php echo (!empty($_SESSION['link-orig-esc']) ? $_SESSION['link-orig-esc'] : '') ?>">
    <input type="hidden" name="mac-esc" value="<?php echo (!empty($_SESSION['mac-esc']) ? $_SESSION['mac-esc'] : '') ?>">
    <input type="hidden" name="identity" value="<?php echo (!empty($_SESSION['identity']) ? $_SESSION['identity'] : '') ?>">
    <input type="hidden" name="link-redirect" value="<?php echo (!empty($_SESSION['link-redirect'] ? $_SESSION['link-redirect'] : '')) ?>">
    <input type="hidden" name="provider" value="hotelinking">
    <input type="hidden" name="popup" value="true" />
</form>
<?php if (!empty($_SESSION['link-login-only'])) : ?>
    <script src="<?php echo DIR_JS . 'cookies.min.js' ?>"></script>
    <script>
        $(document).ready(function() {
            var cookie_name = 'hlconnect';

            //when HLevents sends the wifi-redirect msg 
            HLevents.subscribe('wifi-redirect', function(obj) {
                <?php 
                // Check if portalConfig exists and qr_redirect is true AND PMS data exists
                if (isset($_SESSION['portalConfig']) && isset($_SESSION['portalConfig']->qr_redirect) && $_SESSION['portalConfig']->qr_redirect === true && !empty($_SESSION['first_name']) && !empty($_SESSION['last_name']) && !empty($_SESSION['room_number'])): 
                ?>
                    // Construct the hotspot URL by replacing /login with :60080
                    var linkLoginOnly = <?php echo json_encode($_SESSION['link-login-only'] ?? ''); ?>;
                    var hotspotUrl = linkLoginOnly.replace('/login', ':60080');
                    var redirectUrl = <?php echo json_encode(!empty($_SESSION['link-redirect']) ? $_SESSION['link-redirect'] : ''); ?>;

                    if (redirectUrl && hotspotUrl) {
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', hotspotUrl, true);
                        xhr.timeout = 500; // 0.5 second timeout
                        
                        xhr.onload = function() {
                            console.log('Request completed successfully, redirecting to:', redirectUrl);
                            window.location.href = redirectUrl;
                        };
                        
                        xhr.onerror = function() {
                            console.log('Request failed, redirecting anyway to:', redirectUrl);
                            window.location.href = redirectUrl;
                        };
                        
                        xhr.ontimeout = function() {
                            console.log('Request timed out, redirecting anyway to:', redirectUrl);
                            window.location.href = redirectUrl;
                        };
                        
                        xhr.send();
                    } 
                <?php else: ?>
                //submit the mikrotik form to auth the user
                $('.hotelinking-wifi-login-form').submit();
                <?php endif; ?>
            })

        });
    </script>
<?php endif; ?>
<?php
//When finish, unset trial session, if the user try again, a new trial session must be created
//If not, can have problems because browser thinks is in trial when it´s not.
if ($url['dir1'] == 'stay-wifi-redirect') {
    if ($trigger_integration) {
        unset($_SESSION['trial']);
    }
}
?>