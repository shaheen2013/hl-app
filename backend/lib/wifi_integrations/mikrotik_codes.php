<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
$premium_start = "PRM";
$premium_large_start = "PREMIUM";
$internal_start = "PIO";
$staff_start = "STAFF";
$guest_start = "GUEST";

//If those parameters exists, store them in session
if (!empty($_POST) && !empty($_POST['mac'])) {
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

    $log->info('Mikrotik codes session info', ['session' => $_SESSION, '$_POST' => $_POST]);
}
//If POST trial exists
if ((!empty($_POST['trial']) && $_POST['trial'] == 'yes')) {
    //Define trial session
    $_SESSION['trial'] = 1;
}
$_SESSION['room_number'] = array_get($_SESSION, 'hotel_access_codes') ? array_get($_SESSION, 'hotel_access_codes') : (array_get($_POST, 'hotelAccessCodes') ? array_get($_POST, 'hotelAccessCodes') : array_get($_SESSION, 'roomNumber'));

$log->debug('Mikrotik codes session info', [
    'session' => $_SESSION,
    '$_POST' => $_POST,
    'form' => [
        'url'=> $_SESSION['link-login-only'] ?? 'no url set',
        'username' => $_SESSION['customer'] ?? false ? $datosWifiHotel['username'] : 'guest',
        'password'=> $datosWifiHotel['password']
    ]
]);

?>
<form name="redirect" class="hotelinking-wifi-login-form" action="<?php echo(!empty($_SESSION['link-login-only']) ? $_SESSION['link-login-only'] : 'no url set') ?>" method="post">
    <input type="hidden" name="mac" value="<?php echo(!empty($_SESSION['mac']) ? $_SESSION['mac'] : '') ?>">
    <input type="hidden" name="ip" value="<?php echo(!empty($_SESSION['ip']) ? $_SESSION['ip'] : '') ?>">
    <?php if (empty($_SESSION['trial'])) :?>
        <?php if ($datosWifiHotel['guest_enabled'] ?? false) :?>
            <input id="username" type="hidden" name="username" value="<?php echo(($_SESSION['customer'] ?? false) ? $datosWifiHotel['username'] : 'guest') ?>">
        <?php elseif (substr(strtoupper($_SESSION['room_number']), 0, strlen($premium_start)) === $premium_start): ?>
            <input id="username" type="hidden" name="username" value="hotelinking_premium">
        <?php elseif (substr(strtoupper($_SESSION['room_number']), 0, strlen($premium_large_start)) === $premium_large_start): ?>
            <input id="username" type="hidden" name="username" value="hotelinking_premium">
        <?php elseif (substr(strtoupper($_SESSION['room_number']), 0, strlen($internal_start)) === $internal_start): ?>
            <input id="username" type="hidden" name="username" value="hotelinking_internal">
        <?php elseif (substr(strtoupper($_SESSION['room_number']), 0, strlen($staff_start)) === $staff_start): ?>
            <input id="username" type="hidden" name="username" value="hotelinking_internal">
        <?php elseif (substr(strtoupper($_SESSION['room_number']), 0, strlen($guest_start)) === $guest_start): ?>
            <input id="username" type="hidden" name="username" value="guest">
        <?php else: ?>
            <input id="username" type="hidden" name="username" value="<?php echo($datosWifiHotel['username']) ?>">
        <?php endif; ?>
        <input type="hidden" name="password" value="<?php echo $datosWifiHotel['password'] ?>">
    <?php else: ?>
        <input id="trial_username" type="hidden" name="username" value="T-<?php echo(!empty($_SESSION['mac']) ? $_SESSION['mac'] : '') ?>">
        <input type="hidden" name="password" value="">
    <?php endif; ?>
    <input type="hidden" name="link-login" value="<?php echo(!empty($_SESSION['link-login']) ? $_SESSION['link-login'] : '') ?>">
    <input type="hidden" name="link-orig" value="<?php echo(!empty($_SESSION['link-orig']) ? $_SESSION['link-orig'] : '') ?>">
    <input type="hidden" name="error" value="<?php echo(!empty($_SESSION['error']) ? $_SESSION['error'] : '') ?>">
    <input type="hidden" name="chap-id" value="<?php echo(!empty($_SESSION['chap-id']) ? $_SESSION['chap-id'] : '') ?>">
    <input type="hidden" name="chap-challenge" value="<?php echo(!empty($_SESSION['chap-challenge']) ? $_SESSION['chap-challenge'] : '') ?>">
    <input type="hidden" name="link-login-only" value="<?php echo(!empty($_SESSION['link-login-only']) ? $_SESSION['link-login-only'] : '') ?>">
    <input type="hidden" name="link-orig-esc" value="<?php echo(!empty($_SESSION['link-orig-esc']) ? $_SESSION['link-orig-esc'] : '') ?>">
    <input type="hidden" name="mac-esc" value="<?php echo(!empty($_SESSION['mac-esc']) ? $_SESSION['mac-esc'] : '') ?>">
    <input type="hidden" name="provider" value="hotelinking">
    <input type="hidden" name="popup" value="true"/>
</form>
<?php if (!empty($_SESSION['link-login-only'])) : ?>
    <script src="<?php echo DIR_JS . 'cookies.min.js' ?>"></script>
    <script>
        $(document).ready(function () {
            // when HLevents sends the wifi-redirect msg 
            // send the form to router
            HLevents.subscribe('wifi-redirect', function(obj){
                //submit the mikrotik form to auth the user

                $('.hotelinking-wifi-login-form').submit();
            }) 

        });
    </script>
<?php endif; ?>
<?php
    // When finish, unset trial session, if the user try again, a new trial session must be created
    // If not, can have problems because browser thinks is in trial when it´s not.
    if ($url['dir1'] == 'stay-wifi-redirect') {
        if ($trigger_integration) {
            unset($_SESSION['trial']);
        }
    }
?>
