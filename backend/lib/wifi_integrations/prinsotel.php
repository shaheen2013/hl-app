<?php if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<form method="post" action="<?php echo $datosWifiHotel['form_url'] ?>" class="hotelinking-wifi-login-form">
    <input name="auth_user" type="hidden" value="<?php echo $datosWifiHotel['username'] ?>">
    <input name="auth_pass" type="hidden" value="<?php echo $datosWifiHotel['password'] ?>">
    <input name="auth_voucher" type="hidden" >
    <input name="redirurl" type="hidden" value="<?php echo $datosWifiHotel['url'] ?>">
    <input name="accept" type="hidden" value="Continue">
</form>


<script>
    $(document).ready(function () {
        //must have a button with class send-login-button in the template
        // $('.send-login-button').click(function () {
            HLevents.subscribe('wifi-redirect', function(obj){
                //Send log
                $('.hotelinking-wifi-login-form').submit();
            })
        // })
    })
</script>