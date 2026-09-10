<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>
<script>
    //page Info
    var pageInfo = {
        page: 'landing', //statistics
        base: '<?php echo SECURE_BASE_PATH ?>',
        webService: 'lib/webservices/landing-ws.php',
        statisticsWebservice: 'lib/webservices/store_iframe_statistics-ws.php',
        hotelId: '<?php echo $hotel_id ?>',
        cadenaId: '<?php echo $chain_id ?>',
        token: '<?php echo $token ?>',
        datosHotel: '<?php echo json_encode($arrayDatosHotel) ?>',
        ofertaReferral: '<?php echo json_encode($ofertaReferral) ?>',
        guid: '<?php echo $guid ?>',
        promo: null,
        referrerId: '<?php echo $referrer_id?>',
        userLang: '<?php echo $_SESSION['userNavLang'] ?>',
        appId: '<?php echo FACEBOOK_APP_ID ?>',
        scope: "email, public_profile, user_friends, publish_actions, user_location, user_birthday",
        shareType: 5, // share type for store
        redirectUri: window.location.href,
        generateUrl: false,
        testEmail: true
    };
</script>

<?php include LANG . $_SESSION['userLang'] . '/digital-loyalty-program.php' ?>

<?php
include TEMPLATES . 'digital-loyalty-program-1.php';
?>

<script src="<?php echo DIR_JS ?>cookies.js"></script>
<script src="<?php echo DIR_JS ?>facebook_actions_DLP1.js"></script>
<script>
    $(document).ready(function () {
        //get url parameters
        function gup(name, url) {
            if (!url) url = location.href;
            name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
            var regexS = "[\\?&]" + name + "=([^&#]*)";
            var regex = new RegExp(regexS);
            var results = regex.exec(url);
            return results == null ? null : results[1];
        }
    })
</script>
<script src="<?php echo DIR_JS ?>facebook_init.js"></script>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script src="<?php echo DIR_JS ?>facebook_login.js"></script>
<script src="<?php echo DIR_JS ?>facebook_storeUser.js"></script>