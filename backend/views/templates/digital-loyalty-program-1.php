<?php include LANG . $_SESSION['userLang'].'/digital-loyalty-program.php' ?>
<section class="digital-loyalty-program-1 text-center">
    <div class="offer-img-bg" style="background-image: url(<?php echo $ofertaReferral['img'] ?>); background-position:center; background-size: cover; color:#FFF;">
        <img class="img-circle img-thumbnail psi-logo" src="<?php echo $referrer['img'] ?>" alt="referrer img" width="100" height="100">
    </div>

    <?php if(isset($_GET['action']) && $_GET['action'] === 'dologin'): ?>
        <div class="text-offer no-error-text">
            <h3 class="title"><?php echo $digitalLoyaltyProgramLang['Title second screen']?> <strong><?php echo $ofertaReferral['nombre'] ?></strong> <?php echo $digitalLoyaltyProgramLang['Text second screen'] ?></h3>
            <div class="btn-container mt2">
                <div class="btn-group btn-facebook-group" role="group">
                    <button class="btn button-icon btn-facebook btn-lg" onclick="fb_login()"><i class="fa fa-facebook"></i></button>
                    <button class="btn button-icon btn-facebook btn-lg" onclick="fb_login()"><strong><?php echo $digitalLoyaltyProgramLang['Get your gift with Facebook'] ?></strong></button>
                </div>
            </div>
    </div>
    <?php else : ?>
        <div class="text-offer no-error-text">
            <h3 class="title"><strong><?php echo $digitalLoyaltyProgramLang['We have a gift from']?> </strong></h3>
            <p><?php echo $digitalLoyaltyProgramLang['We can reward you with an'] ?> <strong><?php echo $referrer['nombre'] ?></strong>,<?php echo $digitalLoyaltyProgramLang['Just access with facebook to get it!'] ?> <strong><?php echo $ofertaReferral['nombre'] ?></strong>.</p>
            <div class="btn-container mt2">
                <div class="btn-group btn-facebook-group" role="group">
                    <a href="<?php echo SECURE_BASE_PATH . $urlTree['digital-loyalty-program'] . '/hotel/' . $guid . '/' . $token . '/?action=dologin' ?>" target="_parent" class="btn btn-success btn-lg"><i class="fa fa-gift"></i></a>
                    <a href="<?php echo SECURE_BASE_PATH . $urlTree['digital-loyalty-program'] . '/hotel/' . $guid . '/' . $token . '/?action=dologin' ?>" target="_parent" class="btn btn-success btn-lg"><strong><?php echo $digitalLoyaltyProgramLang['I want my gift'] ?></strong></a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-user-cancelled hidden no-error-text">
        <h3 class="title"><strong><?php echo $digitalLoyaltyProgramLang['Don´t wanna gift?'] ?></strong></h3>
        <p><?php echo $digitalLoyaltyProgramLang['In order to get the gift from'] ?> <strong><?php echo $ofertaReferral['nombre'] ?></strong> <?php echo $digitalLoyaltyProgramLang['you need to login with Facebook'] ?></p>
        <div class="btn-container mt2">
            <div class="btn-group btn-facebook-group" role="group">
                <button class="btn button-icon btn-facebook btn-lg" onclick="fb_login()"><i class="fa fa-facebook"></i></button>
                <button class="btn button-icon btn-facebook btn-lg" onclick="fb_login()"><strong><?php echo $digitalLoyaltyProgramLang['Login with Facebook'] ?></strong></button>
            </div>
        </div>
    </div>

    <div class="text-user-permissions hidden no-error-text">
        <h3 class="title"><strong><?php echo $digitalLoyaltyProgramLang['We need permissions from you'] ?></strong></h3>
        <p><?php echo $digitalLoyaltyProgramLang['We really need to access some Facebook permissions in order to give you the gift:'] ?> <strong><?php echo $ofertaReferral['nombre'] ?></strong>.<br/>
            <?php echo $digitalLoyaltyProgramLang['Please give us the listed permissions by click on the button below'] ?></p>
            <span class="permissions-list"></span><br/>
        <div class="btn-container mt2">
            <div class="btn-group btn-facebook-group" role="group">
                <button class="btn button-icon btn-facebook btn-lg" onclick="fb_login(true)"><i class="fa fa-facebook"></i></button>
                <button class="btn button-social btn-facebook btn-lg" onclick="fb_login(true)"><strong><?php echo $digitalLoyaltyProgramLang['Continue with Facebook'] ?></strong></button>
            </div>
        </div>
    </div>

    <div class="error-fatal error-4011 error-4012 hidden">
        <h3 class="title"><strong><?php echo $digitalLoyaltyProgramLang['Upps... We found an internal error'] ?></strong></h3>
        <p><?php echo $digitalLoyaltyProgramLang['We are experiencing problems with your facebook account information.']?></p>
        <div class="btn-container mt2">
            <div class="btn-group btn-facebook-group" role="group">
                <button class="btn button-icon btn-facebook btn-lg" onclick="fb_login(true)"><i class="fa fa-facebook"></i></button>
                <button class="btn button-social btn-facebook btn-lg" onclick="fb_login(true)"><strong><?php echo $digitalLoyaltyProgramLang['Continue with Facebook'] ?></strong></button>
            </div>
        </div>
    </div>

    <div class="error-fatal error-4018 hidden">
        <h3 class="title"><strong><?php echo $digitalLoyaltyProgramLang['Upsss... You can´t refer yourself'] ?></strong></h3>
        <p><?php echo $digitalLoyaltyProgramLang['You can´t give yourself this promo because you are the referrer.'] ?></p>
        <a class="btn btn-primary" href="<?php echo $websiteUrlReserva ?>" title="go to hotel website"><?php echo $digitalLoyaltyProgramLang['Return to hotel website'] ?></a>
    </div>

    <div class="error-fatal error-4013 hidden">
        <h3 class="title"><strong><?php echo $digitalLoyaltyProgramLang['Offer already redeemed'] ?></strong></h3>
        <p><?php echo $digitalLoyaltyProgramLang['this kind of offers can be only redeemed once, it seems you already did it.'] ?></p>
        <a class="btn btn-primary" href="<?php echo $websiteUrlReserva ?>" title="go to hotel website"><?php echo $digitalLoyaltyProgramLang['Return to hotel website'] ?></a>
    </div>

    <div class="ask-for-email hidden">
        <h2 class="title"><strong><?php echo $digitalLoyaltyProgramLang['Please, insert your email'] ?></strong></h2>
        <form class="form-inline">
            <div class="form-group">
                <input type="email" class="form-control input-lg" id="fb-email" placeholder="<?php echo $digitalLoyaltyProgramLang['Insert your email here...'] ?>" required>
            </div>
            <button type="button" class="btn btn-primary fb-email-btn btn-lg"><?php echo $digitalLoyaltyProgramLang['Continue'] ?></button>
        </form>
    </div>

</section>

