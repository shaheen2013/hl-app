<!-- Modal -->
<div class="modal fade" id="facebookShareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Share your experience on Facebook</h4>
      </div>
      <div class="modal-body">
        <div class="media">
          <a class="pull-left" href="#">
            <img class="media-object img-circle fbImg" src="https://s3.amazonaws.com/uifaces/faces/twitter/kolage/128.jpg" alt="Image" width="40">
          </a>
          <div class="media-body">
            <small class="media-heading"><strong class="fbName"></strong> Shares experiences at <strong><?php echo $datosHotel['hotelName'] ?></strong></small>
          </div>
        </div>
        <img class="mt" src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel ?>/fotoBg/<?php echo $datosHotel['fotoBg'] ?>" alt="hotel image" style="border-radius:5px;width:100%">
        <h4><strong>I`m having a great experience at <?php echo $datosHotel['hotelName'] ?>!</strong></h4>
        <p>I have a offer to share with all my friends that wish to enjoy this hotel too: <?php echo $ofertaReferralHotel['nombre'] ?> thanks to me, by clicking on the image above.</p>
        <button class="btn btn-facebook btn-icon mt" onclick="fb_share();" data-dismiss="modal"><i class="fa fa-facebook pr"></i> Share on Facebook</button>
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