<?php include LANG . $_SESSION['userLang'] . '/hotel-comments-modal.php' ?>
<div class="modal fade bs-modal-lg" id="hotel-comments-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $HotelCommentsModallang['Hotel comments'] ?></h4>
      </div>
      <div class="modal-body">
        <ul>
          <?php foreach ($arrayComentariosHoteles as $comentario) { ?>
          <li class="mb2">
            <?php if (!empty($comentario['logo'])){ ?>
            <img class="img-circle img-thumbnail mb" src="<?php echo DIR_IMG_FICHA_HOTEL;?><?php echo $comentario['id'] ?>/logo/<?php echo $comentario['logo'] ?>" alt="Hotel logo" width="50" height="50"> <a href="hotel/<?php echo $comentario['hotelName_san'] . '/' . $comentario['id'] ?>"><span class="pl"><?php echo $comentario['hotelName'] ?></span></a>
            <?php } else { ?>
            <img class="img-circle img-thumbnail mb" src="<?php echo DIR_IMG;?>logo.jpg" alt="user avatar" width="50" height="50"> <a href="#" title="Hotel name"><span class="pl"> <a href="hotel/<?php echo $comentario['hotelName_san'] . '/' . $comentario['id'] ?>"><span class="pl"><?php echo $comentario['hotelName'] ?></span></a>
            <?php } ?>
            <div class="pull-right"><i class="fa fa-heart pl"></i> <?php echo $comentario['rate'] ?> <span class="pl"><?php echo $comentario['fecha'] ?></span></div>
            <p class="mb2">
            <?php echo $comentario['coment'] ?>
            </p>
            <div class="clearfix"></div>
          </li>
          <?php } ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $HotelCommentsModallang['Close'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->