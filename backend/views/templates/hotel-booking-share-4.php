<div class="bookingShare booking-share-4">
  <div class="tranquility-msg">
    <i class="fa fa-gift pr fa-2x"></i><br><?php echo $hotelBookingShareLang['Before we show you your booking confirmation we have this gift to you'] ?>
  </div>
  <div class="section text-center">
    <?php
    //Si el hotel existe
    if ($shareScreen === true) { ?>
      <img class="img-circle img-thumbnail" src="<?php echo DIR_IMG_FICHA_HOTEL; ?><?php echo $hotel; ?>/logo/<?php echo $datosHotel['logo'] ?>" alt="add your logo here" width="80" height="80">
      <?php
      //Si este GUID tiene una oferta por share activa
      if ($ofertaShare == true) { ?>
        <h2><?php echo $hotelBookingShareLang['Get an additional'] ?></h2>
        <h1><?php echo $ofertaShare['nombre_oferta'] ?><?php echo $hotelBookingShareLang['for FREE'] ?></h1>
        <p><?php echo $hotelBookingShareLang['By sharing on'] ?> <strong class="facebook">Facebook</strong> <?php echo $hotelBookingShareLang['your upcoming trip'] ?></p>
      <?php } else { ?>
        <h2><?php echo $hotelBookingShareLang['Thank you!'] ?></h2>
        <h1><?php echo $hotelBookingShareLang['Show your happiness'] ?></h1>
        <p><?php echo $hotelBookingShareLang['By sharing on'] ?> <strong class="facebook">Facebook</strong> <?php echo $hotelBookingShareLang['your upcoming trip'] ?></p>
      <?php
        //Si este GUID tiene una oferta por share activa
      } ?>
      <div class="col-lg-6 col-lg-offset-3 mt2">
        <div class="panel panel-default">
          <div class="panel-body text-left">
            <div class="media">
              <div class="pull-left media-middle">
                <img src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel ?>/fotoBg/small_<?php echo $datosHotel['fotoBg'] ?>" class="media-object img-thumbnail" width="130">
              </div>
              <div class="media-body">
                <strong class="media-heading"><?php echo $hotelBookingShareLang['I just booked at'] ?> <?php echo $datosHotel['hotelName'] ?>.</strong><br />
                <?php echo (empty($socialMediaShareText) ? $hotelBookingShareLang['You can book too, i can give to you a'] . ' ' . $ofertaReferralHotel['nombre'] . ' ' . $hotelBookingShareLang['at booking by clicking on the image above'] : $socialMediaShareText); ?>
              </div>
            </div>
          </div>
          <div class="panel-footer">
            <small class="mt pull-left"><?php echo $hotelBookingShareLang['Not sharing'] ?></small>
            <button class="btn btn-facebook pull-right facebook-btn-shadow" onClick="fb_login();"><i class="fa fa-facebook-official pr"></i> <?php echo $hotelBookingShareLang['continua con Facebook boton'] ?></button>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    <?php } else {
      //Si no encontramos el GUID nos vamos a mandar una alerta con el botón de cerrar la ventana
    ?>
      <h2>We just wanted to say</h2>
      <h1>Thanks for booking!</h1>
      <p>The staff</p>
    <?php
      //Si no encontramos el GUID nos vamos a mandar una alerta con el botón de cerrar la ventana
    } ?>

  </div>
</div>

<script>
  $(function() {

    $('.btn-facebook').click(function() {
      $('#facebookInstructions').modal('hide');
      if (!readCookie('hlsc')) {
        $.ajax({
          url: "/lib/webservices/hotel-booking-share-ws.php",
          data: 'event=first_click&hId=<?php echo $hotel ?>&shareData=<?php echo $shareData['shareType'] ?>',
          type: 'POST',
          success: function() {
            createCookie('hlsc', true, 1);
          }
        });
      }
    });

  })
</script>