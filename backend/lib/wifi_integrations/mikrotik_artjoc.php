<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

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
    $_SESSION['username'] = (!empty($_POST['username']) ? urldecode($_POST['username']) : '');
    $_SESSION['password'] = (!empty($_POST['password']) ? urldecode($_POST['password']) : '');
    $_SESSION['identity'] = (!empty($_POST['identity']) ? urldecode($_POST['identity']) : null);
}

?>
<form name="redirect" class="hotelinking-wifi-login-form" action="<?php echo (!empty($_SESSION['link-login-only']) ? $_SESSION['link-login-only'] : 'no url set') ?>" method="post">
    <input type="hidden" name="mac" value="<?php echo (!empty($_SESSION['mac']) ? $_SESSION['mac'] : '') ?>">
    <input type="hidden" name="ip" value="<?php echo (!empty($_SESSION['ip']) ? $_SESSION['ip'] : '') ?>">
    <input type="hidden" name="username" value="<?php echo (!empty($_SESSION['username']) ? $_SESSION['username'] : '') ?>">
    <input type="hidden" name="password" value="<?php echo (!empty($_SESSION['password']) ? $_SESSION['password'] : '') ?>">
    <input type="hidden" name="link-login" value="<?php echo (!empty($_SESSION['link-login']) ? $_SESSION['link-login'] : '') ?>">
    <input type="hidden" name="link-orig" value="<?php echo (!empty($_SESSION['link-orig']) ? $_SESSION['link-orig'] : '') ?>">
    <input type="hidden" name="error" value="<?php echo (!empty($_SESSION['error']) ? $_SESSION['error'] : '') ?>">
    <input type="hidden" name="chap-id" value="<?php echo (!empty($_SESSION['chap-id']) ? $_SESSION['chap-id'] : '') ?>">
    <input type="hidden" name="chap-challenge" value="<?php echo (!empty($_SESSION['chap-challenge']) ? $_SESSION['chap-challenge'] : '') ?>">
    <input type="hidden" name="link-login-only" value="<?php echo (!empty($_SESSION['link-login-only']) ? $_SESSION['link-login-only'] : '') ?>">
    <input type="hidden" name="link-orig-esc" value="<?php echo (!empty($_SESSION['link-orig-esc']) ? $_SESSION['link-orig-esc'] : '') ?>">
    <input type="hidden" name="mac-esc" value="<?php echo (!empty($_SESSION['mac-esc']) ? $_SESSION['mac-esc'] : '') ?>">
    <input type="hidden" name="provider" value="hotelinking">
    <input type="hidden" name="popup" value="true"/>
</form>
<?php if (!empty($_SESSION['link-login-only'])) : ?>
    <script src="<?php echo DIR_JS . 'cookies.min.js' ?>"></script>
    <script>
        $(document).ready(function () {
            //If there is no trial and a cookie exists from another free connection... send form directly.
            <?php if (empty($_SESSION['trial'])) :?>
            //If cookie, he/she connected before, so connect again
            if (retrieve_cookie('wifiConnect')){
                //Send log

                $('.hotelinking-wifi-login-form').submit();
            }
            <?php endif; ?>

            //when HLevents sends the wifi-redirect msg 
            // create cookie and send the form to router
            HLevents.subscribe('wifi-redirect', function(obj){
                //Set a cookie that if mikrotik dont ask users for facebook again in 30 days
                create_cookie('wifiConnect', 'true', 15);

                //submit the mikrotik form to auth the user
                $('.hotelinking-wifi-login-form').submit();
            }) 

        });
    </script>
<?php endif; ?>