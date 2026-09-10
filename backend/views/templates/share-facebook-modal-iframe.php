<!-- Modal -->
<div class="modal fade" id="facebookShareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $hotelBookingShareLang['Share with your friends your upcoming visit'] ?></h4>
      </div>
      <div class="modal-body" style="text-shadow:none;font-size:.8em;">
        <div class="media">
          <a class="pull-left" href="#">
            <img class="media-object img-circle fbImg" src="https://s3.amazonaws.com/uifaces/faces/twitter/kolage/128.jpg" alt="Image" width="40">
          </a>
          <div class="media-body">
            <small class="media-heading"><strong class="fbName"></strong> <?php echo $hotelBookingShareLang['share experiences at'] ?> <strong><?php echo $datosHotel['hotelName'] ?></strong></small>
          </div>
        </div>
        <div class="media">
          <div class="pull-left" style="overflow:hidden;width:30%;">
            <img src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel ?>/fotoBg/<?php echo $datosHotel['fotoBg'] ?>" alt="hotel img" style="width:250px">
          </div>
          <div class="media-body">
            <h4 class="media-heading"><strong><?php echo $hotelBookingShareLang['I just booked at'] ?> <?php echo $datosHotel['hotelName'] ?>!</strong></h4>
            <p><?php echo $hotelBookingShareLang['You can book too, i can give to you a'] ?> <strong><?php echo $ofertaReferralHotel['nombre'] ?></strong> <?php echo $hotelBookingShareLang['at booking by clicking on the image above'] ?></p>
            <button class="btn btn-facebook btn-icon mt" onclick="fb_share();" data-dismiss="modal"><i class="fa fa-facebook pr"></i> <?php echo $hotelBookingShareLang['Share with facebook'] ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('#facebookShareModal').on('show.bs.modal', function(e) {
    $('.fbImg').attr('src', localStorage.getItem('fbimage'));
    $('.fbName').text(localStorage.getItem('fbname'))
  });
</script>