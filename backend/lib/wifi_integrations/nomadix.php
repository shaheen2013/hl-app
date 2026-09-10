<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
?>

<form method='post' action='http://1.1.1.1:1111/usg/process' class="hotelinking-wifi-login-form">
    <input type='hidden' name='username' id='username'/>
    <input type='hidden' name='password' id='password'/>
</form>

<script>
    $(document).ready(function () {
        //must have a button with class send-login-button in the template
        // $('.send-login-button').click(function () {
            HLevents.subscribe('wifi-redirect', function(obj){
                $('.hotelinking-wifi-login-form').submit();
            })
        // })
    })
</script>
