<div class="bookingShare booking-share-1">
  <div class="tranquility-msg">
    <i class="fa fa-gift pr fa-2x"></i><br><?php echo $hotelBookingShareLang['Before we show you your booking confirmation we have this gift to you'] ?>
  </div>
  <div class="section text-center">
    <?php
    //Si el hotel existe
    if($shareScreen === true){ ?>
      <img class="img-circle img-thumbnail" src="<?php echo DIR_IMG_FICHA_HOTEL; ?><?php echo $hotel; ?>/logo/<?php echo $datosHotel['logo'] ?>" alt="add your logo here" width="80" height="80">
      <?php
      //Si este GUID tiene una oferta por share activa
      if($ofertaShare == true){ ?>
        <h2><?php echo $hotelBookingShareLang['Get an additional'] ?></h2>
        <h1><?php echo $ofertaShare['nombre_oferta'] ?><?php echo $hotelBookingShareLang['for FREE'] ?></h1>
        <p><?php echo $hotelBookingShareLang['By sharing on'] ?> <strong class="facebook">Facebook</strong> <?php echo $hotelBookingShareLang['your upcoming trip'] ?></p>
        <p id="small-msg"><i class="fa fa-envelope-o pr"></i> <?php echo $hotelBookingShareLang['We will send you an email with your coupon code right after the share'] ?></p>
      <?php }else{ ?>
        <h2><?php echo $hotelBookingShareLang['Thank you!'] ?></h2>
        <h1><?php echo $hotelBookingShareLang['Show your happiness'] ?></h1>
        <p><?php echo $hotelBookingShareLang['By sharing on'] ?> <strong class="facebook">Facebook</strong> <?php echo $hotelBookingShareLang['your upcoming trip'] ?></p>
      <?php 
      //Si este GUID tiene una oferta por share activa
      } ?>

      <?php 
      //Hotel tiene oferta de referral para sus amigos
      //if(!empty($ofertaReferralHotel['nombre'])){ 

      //}else{

      //}
      //Hotel tiene oferta de referral para sus amigos
      ?>

      <div class="shareButtons mt2">
        <button class="btn btn-blanco btn-lg instructionBtn"><?php echo ($ofertaShare == true ? $hotelBookingShareLang['Get your voucher for free now'] : $hotelBookingShareLang['Share on Facebook now']) ?></button>
      </div>

    <?php }else{
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
  $(function(){
    $('.instructionBtn').click(function(){
      if(!readCookie('hlfc')){
        $.ajax({
          url: "/lib/webservices/hotel-booking-share-ws.php",
          data: 'event=first_click&hId=<?php echo $hotel ?>&shareData=<?php echo $shareData['shareType'] ?>',
          type: 'POST',
          success : function (){
            createCookie('hlfc', true, 1);
          }
        });
      }
      fb_login();
    });
  })
</script>