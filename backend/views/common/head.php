<?php if ($url['dir1'] == $urlTree['digital-loyalty-program']) {
    include LANG . $_SESSION['userLang'] . '/digital-loyalty-program.php';
}?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9">
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=<?php echo $_SESSION['userLang'] ?>> <!--<![endif]-->
<head>
    <?php if ($url['dir1'] == $urlTree['digital-loyalty-program']) {?>
        <title><?php echo $arrayDatosHotel['hotelName'] ?></title>
    <?php } else {?>
        <title>Hotelinking - Hotel Referral Marketing & Loyalty Program</title>
    <?php }?>

    <?php if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') { ?>
        <base href="<?php echo SECURE_BASE_PATH ?>">
    <?php } else {?>
        <base href="<?php echo BASE_PATH ?>">
    <?php }?>

    <meta charset="UTF-8">
    <meta name="keywords" content="hotelinking">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">

    <!-- <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico"> -->
    <link rel="shortcut icon" href="https://images.hotelinking.com/login/favicon.ico">
    <script src="<?php echo DIR_JS ?>jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="<?php echo DIR_CSS ?>hotel-suggest.css?v=2">

    <!-- old brand-->
    <?php if ($url['dir1'] != $urlTree['stay-share'] && $url['dir1'] != $urlTree['stay-wifi-redirect'] && $url['dir1'] != $urlTree['facebook-login-callback']): ?>
        <link rel="stylesheet" href="<?php echo DIR_CSS ?>jqueryui-1.10.4.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" async>
        <link href='<?php echo DIR_CSS ?>roboto.min.css' rel='stylesheet' type='text/css' async>
        <link rel="stylesheet" href="<?php echo DIR_CSS ?>typeahead.css">
    <?php endif;?>

    <!-- conditional loads -->
    <?php if ($url['dir1'] == $urlTree['hlpc-validator']) {?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js"></script>
    <?php }?>

    <?php if ($url['dir1'] == $urlTree['hotel-privacy'] || $url['dir1'] == $urlTree['chain-privacy']) {?>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.js"></script>
    <?php }?>

    <?php if ($url['dir1'] == $urlTree['referrals-home']) {?>
        <script src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css"
              href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>
    <?php }?>

    <?php if ($url['dir1'] == $urlTree['hotel-crear-detalle-oferta']) {?>
        <link rel="stylesheet" href="<?php echo DIR_CSS ?>dropzone.css">
    <?php }?>

    <?php if ($url['dir1'] == 'hotel') {?>
        <link rel="stylesheet" href="<?php echo DIR_CSS ?>lightbox.css">
    <?php }?>

    <?php if ($url['dir1'] == 'create-offer-from-token' || $url['dir1'] == 'stay-share' || $url['dir1'] == 'stay-wifi-redirect' || $url['dir1'] == 'pre-stay-iframe' || $url['dir1'] == 'digital-loyalty-program' || $url['dir1'] == 'satisfaction-survey' || $url['dir1'] == 'satisfaction-survey-thanks') {?>
        <link rel="stylesheet" href="<?php echo DIR_CSS ?>animated.min.css" async>
    <?php }?>

    <!-- conditional loads -->
    <link rel="stylesheet" href="<?php echo DIR_CSS ?>main.css?ver=50">

    <?php if (($url['dir1'] == $urlTree['hotel-home'] && (!empty($mostrarModal) && $mostrarModal)) || $url['dir1'] == $urlTree['hotel-crear-detalle-oferta']) {?>
        <link rel="stylesheet" href="<?php echo DIR_CSS ?>bootstrap-tour.min.css">
    <?php }?>

</head>
<body class="<?php echo $url['dir1']; ?>">
    <div id="loader">
        <img style="height: unset;position: absolute!important;top: 50%!important;left: 50%!important;transform: translate(-50%, -50%);" src="https://images.hotelinking.com/ui/Loader_black.gif" alt="loader" height="80" width="80">
    </div>