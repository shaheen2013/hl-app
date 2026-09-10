<div class="bookingShare booking-share-5">
  <div class="tranquility-msg">
    <i class="fa fa-gift pr fa-2x"></i><br><?php echo $hotelBookingShareLang['Before we show you your booking confirmation we have this gift to you'] ?>
  </div>
  <div class="section text-center">
    <?php
    //Si el hotel existe
    if($shareScreen === true){ ?>
    <div class="offer-img-bg" style="background-image:linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo DIR_IMG_OFERTAS . $ofertaShare['id'] . '/big_' . $ofertaShare['img'] ?>); background-position:center; background-size: cover; padding: 35px 15px; color:#FFF;">
      <img class="img-circle img-thumbnail" src="<?php echo DIR_IMG_FICHA_HOTEL; ?><?php echo $hotel; ?>/logo/<?php echo $datosHotel['logo'] ?>" alt="add your logo here" width="80" height="80">
      <?php
      //Si este GUID tiene una oferta por share activa
      if($ofertaShare == true){ ?>
        <h2><?php echo $hotelBookingShareLang['Get an additional'] ?></h2>
        <h1><?php echo $ofertaShare['nombre_oferta'] ?><?php echo $hotelBookingShareLang['for FREE'] ?></h1>
        <p><?php echo $hotelBookingShareLang['By sharing on'] ?> <strong>Facebook</strong> <?php echo $hotelBookingShareLang['your upcoming trip'] ?></p>
        <p id="small-msg"><i class="fa fa-envelope-o pr"></i> <?php echo $hotelBookingShareLang['We will send you an email with your coupon code right after the share'] ?></p>
      <?php }else{ ?>
        <h2><?php echo $hotelBookingShareLang['Thank you!'] ?></h2>
        <h1><?php echo $hotelBookingShareLang['Show your happiness'] ?></h1>
        <p><?php echo $hotelBookingShareLang['By sharing on'] ?> <strong>Facebook</strong> <?php echo $hotelBookingShareLang['your upcoming trip'] ?></p>
      <?php 
      //Si este GUID tiene una oferta por share activa
      } ?>
      </div>
      <?php 
      //Hotel tiene oferta de referral para sus amigos
      //if(!empty($ofertaReferralHotel['nombre'])){ 

      //}else{

      //}
      //Hotel tiene oferta de referral para sus amigos
      ?>

      <div class="shareButtons mt2">
        <button class="btn btn-blanco btn-lg instructionBtn mb"><?php echo ($ofertaShare == true ? $hotelBookingShareLang['Get your voucher for free now'] : $hotelBookingShareLang['Share on Facebook now']) ?></button><br/>
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

<div class="modal fade" tabindex="-1" role="dialog" id="facebookInstructions">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $hotelBookingShareLang['Como ganar tu'] ?></h4>
      </div>
      <div class="modal-body">
        <?php echo $hotelBookingShareLang['Cuando le des al botón de'] ?> <strong><?php echo $hotelBookingShareLang['continua con Facebook'] ?></strong>, <?php echo $hotelBookingShareLang['Facebook va a solicitarte'] ?>
        <h4><?php echo $hotelBookingShareLang{'¿Por qué?'} ?></h4>
        <?php echo $hotelBookingShareLang['Para poder mandarte'] ?>
        <div class="bordered-share">
          <h4><i class="fa fa-facebook-official pr"></i><?php echo $hotelBookingShareLang['I just booked at'] ?> <?php echo $datosHotel['hotelName'] ?></h4>
          <?php echo (empty($socialMediaShareText)? $hotelBookingShareLang['You can book too, i can give to you a'].' '.$ofertaReferralHotel['nombre'].' '.$hotelBookingShareLang['at booking by clicking on the image above'] : $socialMediaShareText); ?>
        </div>
      </div>
        <div class="modal-footer">
          <button class="btn btn-facebook" onclick="fb_login();"><i class="fa fa-facebook-official pr"></i> <?php echo $hotelBookingShareLang['continua con Facebook boton'] ?></button>
        </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
  $(function(){

    $('.instructionBtn').click(function(){
      $('#facebookInstructions').modal('show');
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
    });

    $('.btn-facebook').click(function(){
      $('#facebookInstructions').modal('hide');
      if(!readCookie('hlsc')){
        $.ajax({
          url: "/lib/webservices/hotel-booking-share-ws.php",
          data: 'event=second_click&hId=<?php echo $hotel ?>&shareData=<?php echo $shareData['shareType'] ?>',
          type: 'POST',
          success : function(){
            createCookie('hlsc', true, 1);
          }
        });
      }
    });

  })
</script>
