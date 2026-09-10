<script>
    //page Info
    var pageInfo = {
        page : 'pre', //statistics
        webService : '<?php echo SECURE_BASE_PATH . LIB ?>webservices/preStay-ws.php',
        statisticsWebservice : '<?php echo SECURE_BASE_PATH . LIB ?>webservices/store_iframe_statistics-ws.php',
        shareWebservice : '<?php echo SECURE_BASE_PATH . LIB ?>webservices/referral-share-actions-ws.php',
        hotelId : '<?php echo $hotel_id ?>',
        cadenaId: '<?php echo $chain_id ?>',
        userLang : '<?php echo $_SESSION['userNavLang'] ?>',
        appId : <?php echo FACEBOOK_APP_ID ?>,
        shareType : 2, // share type for store
        transaction : '<?php echo (!empty($_GET['transaction']) ? $_GET['transaction'] : null) ?>',
        generateUrl: false,
        userId: null
    };
</script>
<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
if(!empty($datosHotel)){
    //Incluye la plantilla de iframe correcta
    include TEMPLATES . 'pre-stay-iframe-1.php';
    //include TEMPLATES . 'pre-stay-iframe-'.$datosHotel['iframe_style'].'.php';
}else{
    include TEMPLATES . 'pre-stay-iframe-1.php';
    //Incluye la plantilla de iframe correcta
}

?>
<script src="<?php echo DIR_JS ?>cookies.js"></script>
<script src="<?php echo DIR_JS ?>facebook_actions_PSI1.js"></script>
<script>
    //Share itself is method dependant, so if you change method, options must change too
    window.shareObject = {
        app_id : '<?php echo FACEBOOK_APP_ID ?>',
        display : 'popup',
        method: 'feed',
        link: '<?php echo $shared_website ?>'
    };
    //put a cookie for iframe open
    if(!retrieve_cookie('hlIframeOpen')){
        //Store statistics and create cookie
        storeStatistics('iframe_opens', 'hlIframeOpen');
    }

</script>
<script src="<?php echo DIR_JS ?>facebook_init.js"></script>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script src="<?php echo DIR_JS ?>facebook_login.js"></script>
<script src="<?php echo DIR_JS ?>facebook_storeUser.js"></script>
<script src="<?php echo DIR_JS ?>facebook_share.js"></script>