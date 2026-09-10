<section class="pre-stay-iframe-1 text-center">
    <div class="offer-img-bg" style="background-image: url(<?php echo $ofertaShare['img'] ?>); background-position:center; background-size: cover; color:#FFF;">
        <img class="img-circle img-thumbnail psi-logo" src="<?php echo $datosHotel['logo'] ?>" alt="Hotel logo" width="100" height="100">
    </div>
    <div class="text-offer">
        <h2 class="title"><strong><?php echo $preStayIframeLang['We have welcome gift for you'] ?></strong></h2>
        <p><?php echo $preStayIframeLang['By sharing your happiness on facebook you will receive a'] ?> <strong><?php echo $ofertaShare['nombre_oferta'] ?></strong>    <?php echo $preStayIframeLang['giveaway'] . ($ofertaReferralHotel['nombre'] != '' ? ', ' . $preStayIframeLang['and your friends will be rewarded with a'] . '<strong>' . $ofertaReferralHotel['nombre'] . '</strong>'. $preStayIframeLang['by clicking on your share.'] :'') ?>
        </p>
        <div class="btn-container mt2">
            <button class="btn btn-facebook" aria-label="Log in with Facebook" onclick="fb_login();">
                <div class="flex-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff"><path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path></svg>
                    <strong><?php echo $preStayIframeLang['Continue with facebook'] ?></strong>
                </div>
            </button>

        </div>
    </div>
    <div class="text-user-cancelled hidden">
        <h2 class="title"><strong><?php echo $preStayIframeLang['Don´t wanna gift?'] ?></strong></h2>
        <p><?php echo $preStayIframeLang['In order to have this giveaway you should use your facebook account, please click on the button below'] ?></p>
        <div class="btn-container mt2">
            <button class="btn btn-facebook" aria-label="Log in with Facebook" onclick="fb_login(true);">
                <div class="flex-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff"><path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path></svg>
                    <strong><?php echo $preStayIframeLang['Continue with facebook'] ?></strong>
                </div>
            </button>
        </div>
    </div>
    <div class="text-user-permissions hidden">
        <h2 class="title"><strong><?php echo $preStayIframeLang['We need permissions from you'] ?></strong></h2>
        <p><?php echo $preStayIframeLang['You will need to give us access to your:']?><br/>
            <span class="permissions-list"></span><br/>
            <?php echo $preStayIframeLang['Please click on the button below to continue.'] ?></p>
        <div class="btn-container mt2">
            <button class="btn btn-facebook" aria-label="Log in with Facebook" onclick="fb_login(true);">
                <div class="flex-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff"><path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path></svg>
                    <strong><?php echo $preStayIframeLang['Continue with facebook'] ?></strong>
                </div>
            </button>
        </div>
    </div>
    <div class="text-user-share hidden">
        <h2 class="title"><strong><?php echo $preStayIframeLang['Final step, share it!'] ?></strong></h2>
        <p><?php echo $preStayIframeLang['Your are a click away from your gift, please share on facebook your happiness. Don´t worry, you will see your share before share.'] ?></p>
        <div class="btn-container mt2">
            <button class="btn btn-facebook" aria-label="Log in with Facebook" onclick="fb_share(shareActions, shareObject);">
                <div class="flex-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff"><path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path></svg>
                    <strong><?php echo $preStayIframeLang['Share on facebook'] ?></strong>
                </div>
            </button>
        </div>
    </div>
    <div class="text-user-shared hidden">
        <h2 class="title"><strong><?php echo $preStayIframeLang['Thanks for helping us!'] ?></strong></h2>
        <p><?php echo $preStayIframeLang['As promised we are sending to you an email that includes your voucher to be redeemed here at the hotel. Please show your email to our staff at your arrival'] ?></p>
        <p><strong><?php echo $preStayIframeLang['You can close this popup by clicking on "X" button'] ?></strong></p>
    </div>
    <div class="text-user-not-shared hidden">
        <h2 class="title"><strong><?php echo $preStayIframeLang['Don´t wanna gift?'] ?></strong></h2>
        <p><?php echo $preStayIframeLang['It seems that you don´t wanna share, it´s ok, but that means you don´t want a'] ?> <strong><?php echo $ofertaShare['nombre_oferta'] ?></strong><?php echo $preStayIframeLang['? We are sure you´ll like it!'] ?></p>
        <div class="btn-container mt2">
            <button class="btn btn-facebook" aria-label="Log in with Facebook" onclick="fb_share(shareActions, shareObject);">
                <div class="flex-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff"><path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path></svg>
                    <strong><?php echo $preStayIframeLang['Share on facebook'] ?></strong>
                </div>
            </button>
        </div>
    </div>
    <div class="ask-for-email hidden">
        <h2 class="title"><strong><?php echo $preStayIframeLang['Please insert your email'] ?></strong></h2>
        <form class="form-inline">
            <div class="form-group">
                <input type="email" class="form-control input-lg" id="fb-email" placeholder="<?php echo $preStayIframeLang['Insert your email here'] ?>" required>
            </div>
            <button type="button" class="btn btn-primary fb-email-btn btn-lg"><?php echo $preStayIframeLang['Continue'] ?></button>
        </form>
    </div>
</section>