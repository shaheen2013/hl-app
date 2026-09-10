<div class="vertical-align hlpc-validator-content <?php echo(!$validPromo ? 'hlpc-validator-promo-Invalid' : 'hlpc-validator-promo-valid') ?>">
    <div class="copied-confirmation-msg"> <i class="fa fa-clipboard" aria-hidden="true"></i> <?php echo $hlpcValidatorLang['Código promocional copiado al portapapeles']?></div>
    <div class="text-center blanco mt15">
        <i class="fa <?php echo(!$validPromo ? 'fa-exclamation' : 'fa-check-circle-o') ?> fa-5x" aria-hidden="true"></i>
        <h1> <?php echo(!$validPromo ? $hlpcValidatorLang['Ohhh dear...'] : $hlpcValidatorLang['Good news']) ?> <?php echo $username ?></h1>
        <?php if ($validPromo) { ?>
            <p><?php echo $hlpcValidatorLang['Este es tu código promocional para tu oferta'] ?></p>
            <h3><strong><?php echo $offerName ?></strong></h3>
            <p><?php echo $hlpcValidatorLang['Haz click sobre el botón para copiarlo y pégalo en el campo de código promocional del buscador']?></p>
            <div class="input-group">
                <input type="text" id="post-shortlink" class="form-control" value="<?php echo $BEPromo ?>">
                <span class="input-group-btn">
                    <button id="copy-button" class="btn btn-primary" type="button" data-clipboard-target="#post-shortlink"><?php echo $hlpcValidatorLang['copia en portapapeles']?></button>
                </span>
            </div>
            <p><?php echo $hlpcValidatorLang['Una vez copiado ya puedes cerrar esta ventana.']?></p>

        <?php } else { ?>
            <p><?php echo $hlpcValidatorLang['You dont have a valid promocode for your booking, but it´s not the end'] ?></p>
            <p><?php echo $hlpcValidatorLang['You can still have a nice stay at our hotels and in the end of booking proccess we will offer you a reward.'] ?></p>
        <?php } ?>
    </div>
</div>
<script>
    (function () {
        new Clipboard('#copy-button');
    })();
    $('#copy-button').click(function(){
        $('.copied-confirmation-msg').addClass('show-confirmation-msg');
        setTimeout(function(){
            $('.copied-confirmation-msg').removeClass('show-confirmation-msg');
        },3000)
    })
</script>