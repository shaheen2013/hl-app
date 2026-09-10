<?php include LANG . $_SESSION['userLang'] . '/create-offer-from-token.php' ?>
<div class="stay-overlayer"></div>
<style type="text/css">
    body {
        background: url(<?php echo array_get($tokenOfferData, 'fotoBg',array_get($datosHotel,'fotoBg')) ?>);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>
<div class="redeem-offer_main">
    <div class="success-layer">
        <div class="waves">
            <div class="sea-wave-green wave"></div>
            <div class="sea-wave-light-green wave"></div>
        </div>
    </div>
    <div class="vertical-align text-center">
        <?php if (!empty($tokenOfferData)) : ?>
            <?php if ($tokenOfferData['code'] == '404') : ?>
                <i class="fa fa-meh-o fa-5x" aria-hidden="true"></i>
                <h3><?php echo $createOfferTokenLang['Your offer as been activated'] ?></h3>
                <p><?php echo $createOfferTokenLang['You can´t activate this offer twice.'] ?></p>
            <?php endif; ?>
            <?php if ($tokenOfferData['tipo_oferta'] == 'inmediate') : ?>
                <?php if ($tokenOfferData['code'] == '200') : ?>
                    <img src="<?php echo $tokenOfferData['logo_hotel'] ?>"
                         alt="Hotel logo" class="img-thumbnail img-circle" width="100" height="100">
                    <h3 class="animated h3-anim"><?php echo $createOfferTokenLang['Show this screen to staff'] ?></h3>
                    <p class="animated p-anim"><?php echo $createOfferTokenLang['In order to redeem your'] ?>
                        <strong><?php echo $tokenOfferData['nombre_oferta'] ?></strong> <?php echo $createOfferTokenLang['show this screen to the staff'] ?>
                    </p>
                    <button class="btn btn-warning btn-lg mt2 btn-hold animated btn-anim"
                            oncontextmenu="return false"><?php echo $createOfferTokenLang['To be clicked by staff'] ?></button>
                    <p class="mt animated small-anim">
                        <small><?php echo $createOfferTokenLang['Hold the button to redeem'] ?></small>
                    </p>
                    <h3 class="animated zoomOut h3-anim-2 hidden"><?php echo $createOfferTokenLang['Wait a second...'] ?></h3>
                    <p class="animated zoomOut p-anim-2 hidden"><?php echo $createOfferTokenLang['Validating voucher'] ?></p>
                <?php endif; ?>
                <?php if ($tokenOfferData['code'] == '210') : ?>
                    <i class="fa fa-ticket fa-5x" aria-hidden="true"></i>
                    <h3><?php echo $createOfferTokenLang['Congratulations!'] ?></h3>
                    <p><?php echo $createOfferTokenLang['Follow our staff instructions to get your'] ?>
                        <strong><?php echo $tokenOfferData['nombre_oferta'] ?></strong></p>
                    <p>
                        <span class="label label-info"><?php echo $createOfferTokenLang['Promo code guest'] ?>: <?php echo $tokenOfferData['promocode'] ?></span>
                        <span class="label label-info"><?php echo $createOfferTokenLang['Guest who redeemed'] ?>: <?php echo $tokenOfferData['nombre_usuario'] ?></span>
                        <span class="label label-info"><?php echo $createOfferTokenLang['Hotel name redeemed'] ?>: <?php echo $tokenOfferData['nombre_hotel'] ?></span>
                        <span class="label label-info"><?php echo $createOfferTokenLang['Offer redeemed name'] ?>: <?php echo $tokenOfferData['nombre_oferta'] ?></span>
                        <span class="label label-info"><?php echo $createOfferTokenLang['Redeemed on guest'] ?>: <?php echo $tokenOfferData['redeem_date'] ?></span>
                    </p>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($tokenOfferData['tipo_oferta'] == 'web') : ?>
                <?php if ($tokenOfferData['code'] == '200') : ?>
                    <img src="<?php echo $tokenOfferData['logo_hotel'] ?>"
                         alt="Hotel logo" class="img-thumbnail img-circle" width="100" height="100">
                    <h3><?php echo $createOfferTokenLang['Click on the button below to redeem it'] ?></h3>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 mt mb2">
                            <h4 class="dashed-promocode"><?php echo $tokenOfferData['booking_engine_code'] ?></h4>
                        </div>
                    </div>
                    <p><?php echo $createOfferTokenLang['We sent you an email as a reminder with this promocode and instructions on how to redeem it.'] ?></p>
                    <a href="<?php echo $redeemOfferUrl ?>" class="btn btn-warning btn-lg mt2 btn-hold"
                       title="redeem code"><?php echo $createOfferTokenLang['Or use it now!'] ?></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(!empty($isBirthday)) : ?>
            <?php if($birthdayGift) : ?>
                <img src="<?php echo $datosHotel['logo'] ?>"
                     alt="Hotel logo" class="img-thumbnail img-circle" width="100" height="100">
                <h3><?php echo $createOfferTokenLang['Click on the button below to redeem it birthday'] ?></h3>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 mt mb2">
                        <h4 class="dashed-promocode"><?php echo $promoCode ?></h4>
                    </div>
                </div>
                <p><?php echo $createOfferTokenLang['We sent you an email as a reminder with this promocode and instructions on how to redeem it.'] ?></p>
                <a href="<?php echo $redeemOfferUrl ?>" class="btn btn-warning btn-lg mt2 btn-hold"
                   title="redeem code"><?php echo $createOfferTokenLang['Or use it now!'] ?></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php if (!empty($tokenOfferData)) : ?>
    <?php if ($tokenOfferData['tipo_oferta'] == 'inmediate') : ?>
        <form id="redeem_offer_form"
              action="<?php SECURE_BASE_PATH . $urlTree['create-offer-from-token'] . '/?tk=' . $token ?>" method="post">
            <input type="hidden" name="redeem" value="1">
        </form>
        <script src="<?php echo DIR_JS ?>holding-button.min.js"></script>
    <?php endif; ?>
<?php endif; ?>


