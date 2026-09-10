<?php include_once LANG . $_SESSION['userLang'] . '/stay-share-callback.php'; ?>
<div class="stay-overlayer"></div>
<div class="landing-iframe-content">
    <img class="ss-bg-img"
         src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel_id ?>/fotoBg/<?php echo $datosHotel['fotoBg'] ?>"
         alt="Background image of hotel" class="rs-bg">
    <div class="vertical-align text-center">

        <?php if (!$email_fb) { ?>

            <i class="fa fa-meh-o fa-3x" aria-hidden="true"></i>
            <h3>Upps...</h3>
            <p>Introduce tu email</p>
            <div class="col-sm-6 col-sm-offset-3 text-left">
                <form id="re-email-form" method="post">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="re-email" placeholder="Email..." required/>
                    </div>
                    <button type="submit" class="btn btn-primary">Siguiente</button>
                </form>
            </div>

        <?php } else if (!$permissions_fb) { ?>

            <i class="fa fa-meh-o fa-3x" aria-hidden="true"></i>
            <h3>Upps...</h3>
            <p>Para poder acceder al wifi necesitamos que aceptes todos los permisos</p>
            <div class="col-sm-6 col-sm-offset-3 text-left">
                <div class="btn-container">
                    <div class="btn-group btn-facebook-group" role="group">
                        <button class="btn button-social btn-facebook"><strong>Conectar con facebook</strong></button>
                        <button class="btn button-icon btn-facebook"><i class="fa fa-facebook"></i></button>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>
</div>