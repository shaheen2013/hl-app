<?php

    // Get hotspot parameters
    if(!empty($_GET['mac']) && !empty($_GET['ip']))
    {
        $_SESSION['mac'] = $_GET['mac'] ?? "";
        $_SESSION['ip'] = $_GET['ip'] ?? "";
        $log->debug('HOTELES_LOPEZ***', $_GET);

    }

    $url_post = $datosWifiHotel['form_url'];
    $privkey = $datosWifiHotel['password'];

    // Create payload
    $payload = [
        'method' => 'nuevo',
        'params' => [
            'usuario' => str_replace(':', '', array_get($_SESSION, 'mac', '')),
            'ip' => array_get($_SESSION, 'ip'),
            'mac' => array_get($_SESSION, 'mac')
        ],
        'nonce' => strval(time())
    ];

    $payloadEncoded = $_SESSION['wifi_integration_payload'] = base64_encode(json_encode($payload));

    $sign = $_SESSION['wifi_integration_sign'] = hash_hmac('sha256', $payloadEncoded.$payload['nonce'], $privkey);
?>

<form class="hotelinking-wifi-login-form" action="<?php echo $url_post ?>" method="POST" name="form">
			
    <input type="hidden" name="modulo" value="dispositivo">
    <input type="hidden" name="payload" value="<?php echo $payloadEncoded ?>">
    <input type="hidden" name="sign" value="<?php echo $sign ?>">

</form>

<script type="text/javascript">

    $(document).ready(function () {
        HLevents.subscribe('wifi-redirect', function(obj){
            // Execute form
            $('.hotelinking-wifi-login-form').submit();
        })
    })

</script>