<?php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//URL del webservice de Netllar, se incluye en el panel de control de Hotelinking
if (!isset($_SESSION['url'])) {
    $_SESSION['url'] = $datosWifiHotel['form_url']; // Url donde se envia el formulario de netllar
}

//Secret de Netllar, se incluye en el panel de control de Hotelinking
if (!isset($_SESSION['secret']) || $_SESSION['secret'] != $datosWifiHotel['secret']) {
    $_SESSION['secret'] = $datosWifiHotel['secret']; // secret de netllar
}

//Check if posts exists
if ($_POST && !empty($_POST['hsdata'])) {
    $_SESSION['mac'] = (!empty($_POST['mac']) ? decodeParam(urldecode($_POST['mac']), $_SESSION['secret']) : null);
    $_SESSION['hsdata'] = urldecode($_POST['hsdata']);
}

/**
 * Codificar parámetro HL
 *
 * @param  txt  cadena a codificar
 * @param  key  secret del hotel en cuestión
 *
 * @return valor codificado del parámetro
 */
function codeParam($txt, $key)
{
    $txtsize = strlen($txt);
    $keysize = strlen($key);

    $xored = '';

    for ($i = $k = 0; $i < $txtsize; ++$i) {
        $xored .= chr(ord($txt[$i]) ^ ord($key[$k]));

        if (++$k >= $keysize) {
            $k = 0;
        }
    }

    return bin2hex($xored);
}

/**
 * Decodificar parámetro HL
 *
 * @param  txt  cadena a decodificar
 * @param  key  secret del hotel en cuestión
 *
 * @return valor plano del parámetro
 */
function decodeParam($txt, $key)
{
    $txt = hex2bin($txt);

    $txtsize = strlen($txt);
    $keysize = strlen($key);

    $xored = '';

    for ($i = $k = 0; $i < $txtsize; ++$i) {
        $xored .= chr(ord($txt[$i]) ^ ord($key[$k]));

        if (++$k >= $keysize) {
            $k = 0;
        }
    }

    return $xored;
}



// if (array_get($_SESSION, 'name') || array_get($_SESSION, 'customer')) {
    $log->info('netllar insotel form', [
        'form_url' => $datosWifiHotel['form_url'],
        'secret' =>  array_get($_SESSION, 'secret'),
        'hsdata' =>  array_get($_SESSION, 'hsdata'),
        'mac' =>  array_get($_SESSION, 'mac')
    ]);
// }
?>

<form method="POST" action="<?php echo $datosWifiHotel['form_url'] ?>" class="hotelinking-wifi-login-form">
    <input type="hidden" name="secret" value="<?php echo(!empty($_SESSION['secret']) ? $_SESSION['secret'] : '') ?>">
    <input type="hidden" name="hsdata" value="<?php echo(!empty($_SESSION['hsdata']) ? $_SESSION['hsdata'] : '') ?>">
    <input type="hidden" name="finish_date" value="<?php echo date("Y-m-d H:i:s", strtotime('+7 days')); ?>">
</form>

<script>
    $(document).ready(function () {
        //must have a button with class send-login-button in the template
        // $('.send-login-button').click(function () {
        HLevents.subscribe('wifi-redirect', function (obj) {
            //Send log
            $('.hotelinking-wifi-login-form').submit();
        })
        // })
    })
</script>