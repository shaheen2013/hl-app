<!-- Modal -->
<div class="modal fade" id="facebookShareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $referralShareLang['share your experience on facebook'] ?></h4>
      </div>
      <div class="modal-body">
       <div class="media">
         <a class="pull-left" href="#">
           <img class="media-object img-circle fbImg" src="https://s3.amazonaws.com/uifaces/faces/twitter/kolage/128.jpg" alt="Image" width="40">
         </a>
         <div class="media-body">
           <small class="media-heading"><strong class="fbName"></strong> <?php echo $referralShareLang['share experiences at'] ?> <strong><?php echo $datosHotel['hotelName'] ?></strong></small>
         </div>
       </div>
       <img class="mt" src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL?><?php echo $hotel ?>/fotoBg/<?php echo $datosHotel['fotoBg'] ?>" alt="hotel image" style="border-radius:5px;width:100%">
       <h4><strong><?php echo $referralShareLang['great Experience at'] ?> <?php echo $datosHotel['hotelName'] ?>!</strong></h4>
       <p><?php echo (empty($socialMediaShareText)? $referralShareLang['i´ve stayed and totally recommend it! get a'] .' <strong>'. $ofertaReferralHotel['nombre'] .'</strong> '.$referralShareLang['thanks to me, just by clicking on the image above!'] : $socialMediaShareText); ?></p>
       <button class="btn btn-facebook btn-icon mt hide" onclick="fb_share();" data-dismiss="modal"><i class="fa fa-facebook pr"></i> <?php echo $referralShareLang['share facebook'] ?></button>
     </div>
   </div>
 </div>
</div>
<script>
    $('#facebookShareModal').on('show.bs.modal', function (e) {
      $('.fbImg').attr('src', localStorage.getItem('fbimage'));
      $('.fbName').text(localStorage.getItem('fbname'))
    });
</script>