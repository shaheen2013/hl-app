<style type="text/css">
    body {
        /*background: url(<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel_id ?>/fotoBg/big_<?php echo $hotel_info['fotoBg'] ?>);*/
        background: url(<?php echo $hotel_info['fotoBg'] ?>);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>
<div class="stay-overlayer"></div>
<div class="redirect-msg vertical-align">
    <?php if ($state['code'] === 'no_offer' || $state['code'] === 'no_hotel' ) : ?>
        <?php echo($state['code'] === 'no_offer' ? '<img src="'. $hotel_info['logo'] . '" alt="Hotel logo" class="img-thumbnail img-circle hotel-stay-share-logo" width="100" height="100"/>' : '' ) ?>
        <h2>We have a problem</h2>
        <p>We feel sorry, it seems this campaign is not available</p>
    <?php else: ?>
        <img src="<?php echo DIR_IMG . 'ripple.svg' ?>" alt="loaded spinner" width="80" height="80">
        <!-- <h2>Your campaign is ready</h2> -->
        <!-- <p>Redirecting to <strong><?php //echo $hotel_info['hotelName'] ?></strong> website</p> -->
    <?php endif; ?>
</div>