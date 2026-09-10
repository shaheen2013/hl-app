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
    $_SESSION['mac'] = (!empty($_POST['mac']) ? urldecode($_POST['mac']) : null);
    $_SESSION['hsdata'] = urldecode($_POST['hsdata']);

    if (!empty($_POST['card_id'])) {
        $_SESSION['card_id'] = decodeParam($_POST['card_id'], $_SESSION['secret']);
    }
    if (!empty($_POST['room_number'])) {
        $_SESSION['roomNumber'] = decodeParam($_POST['room_number'], $_SESSION['secret']);
    }
    if (!empty($_POST['guest_id'])) {
        $_SESSION['user_hotel_id'] = decodeParam($_POST['guest_id'], $_SESSION['secret']);
    }

    if (!empty($_POST['birthday'])) {
        $_SESSION['birthday'] = decodeParam($_POST['birthday'], $_SESSION['secret']);
    }
    if (!empty($_POST['gender'])) {
        $_SESSION['gender'] = decodeParam($_POST['gender'], $_SESSION['secret']);
    }
    if (!empty($_POST['lang'])) {
        $_SESSION['location'] = decodeParam($_POST['lang'], $_SESSION['secret']);
    }
    if (!empty($_POST['name'])) {
        $_SESSION['name'] = decodeParam($_POST['name'], $_SESSION['secret']);
    }
    if (!empty($_POST['customer'])) {
        $_SESSION['customer'] = decodeParam($_POST['customer'], $_SESSION['secret']);
    }
    // $_SESSION['gender'] = (!empty($_POST['gender']) ? decodeParam($_POST['gender'], $_SESSION['secret']) : NULL);
    // $_SESSION['location'] = (!empty($_POST['lang']) ? decodeParam($_POST['lang'], $_SESSION['secret']) : NULL);
    // $_SESSION['name'] = (!empty($_POST['name']) ? decodeParam($_POST['name'], $_SESSION['secret']) : NULL);
    // $_SESSION['customer'] = (!empty($_POST['customer']) ? decodeParam($_POST['customer'], $_SESSION['secret']) : NULL);


    if (!empty($_SESSION['gender'])) {
        $_SESSION['gender'] = array_get($_SESSION, 'gender') == 'm' ? 'male' : 'female';
    }
    if (!empty($_SESSION['customer'])) {
        $_SESSION['customer'] = array_get($_SESSION, 'customer') == 'y' ? true : false;
    }
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
        $xored .= chr(ord($txt[$i]) ^ ord($key[$k] ?? ''));

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

function createNetllarFinishedDate($date)
{
    // netllar access dates in following format : 2019-05-25 23:59:00
    if ($date) {
        return $date . ' 23:59:00';
    } else {
        //the server is at UTC time , so UTC+2 + 2hours is just UTC + 4hours
        return date("Y-m-d H:i:s", strtotime('+4 hours'));
    }
}

// if (array_get($_SESSION, 'name') || array_get($_SESSION, 'customer')) {
    $log->info('netllar form', [
    'form_url' => $datosWifiHotel['form_url'],
    'secret' =>  array_get($_SESSION, 'secret'),
    'hsdata' =>  array_get($_SESSION, 'hsdata'),
    'card_id' =>  !empty($_SESSION['card_id']) ? codeParam($_SESSION['card_id'], $_SESSION['secret']) : '',
    'room_number' =>  !empty($_SESSION['roomNumber']) ? codeParam($_SESSION['roomNumber'], $_SESSION['secret']) : '',
    'mac' =>  array_get($_SESSION, 'mac'),
    'birthday' =>  array_get($_SESSION, 'birthday'),
    'gender' =>  array_get($_SESSION, 'gender'),
    'lang' =>  array_get($_SESSION, 'userLang'),
    'name' =>  array_get($_SESSION, 'name'),
    'customer' =>  array_get($_SESSION, 'customer'),
    'finish_date' =>  createNetllarFinishedDate(array_get($_SESSION, 'check_out')),
    'guest_id' =>  $_SESSION['user_hotel_id'] ??  array_get($_SESSION, 'mac')
]);
// }
?>

<form method="POST" action="<?php echo $datosWifiHotel['form_url'] ?>" class="hotelinking-wifi-login-form">
    <input type="hidden" name="secret" value="<?php echo(!empty($_SESSION['secret']) ? $_SESSION['secret'] : '') ?>">
    <input type="hidden" name="hsdata" value="<?php echo(!empty($_SESSION['hsdata']) ? $_SESSION['hsdata'] : '') ?>">
    <input type="hidden" name="card_id" value="<?php echo(!empty($_SESSION['card_id']) ? codeParam($_SESSION['card_id'], $_SESSION['secret']) : '') ?>">
    <input type="hidden" name="room_number" value="<?php echo(!empty($_SESSION['roomNumber']) ? codeParam($_SESSION['roomNumber'], $_SESSION['secret']) : '') ?>">
    <input type="hidden" name="guest_id" value="<?php echo($_SESSION['user_hotel_id'] ??  array_get($_SESSION, 'mac'))?>">
    <input type="hidden" name="mac" value="<?php echo(!empty($_SESSION['mac']) ? $_SESSION['mac'] : '') ?>">
    <input type="hidden" name="hotelinking" value="true">
    <input type="hidden" name="birthday" value="<?php echo(array_get($_SESSION, 'birthday'))?>">
    <input type="hidden" name="gender" value="<?php echo(array_get($_SESSION, 'gender'))?>">
    <input type="hidden" name="lang" value="<?php echo(array_get($_SESSION, 'userLang'))?>">
    <input type="hidden" name="name" value="<?php echo(array_get($_SESSION, 'name'))?>">
    <input type="hidden" name="customer" value="<?php echo(array_get($_SESSION, 'customer') ? 'y' : 'n')?>">
    <input type="hidden" name="finish_date" value="<?php echo(createNetllarFinishedDate(array_get($_SESSION, 'check_out')))?>">

    <?php if (array_get($_SESSION, 'user.email') && !array_get($_SESSION, 'user.facebook_id')) {
    ?>
        <input type="hidden" name="email" value="<?php echo(array_get($_SESSION, 'user.email'))?>">
    <?php
} ?>
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