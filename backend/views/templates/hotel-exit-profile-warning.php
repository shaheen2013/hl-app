<?php include LANG . $_SESSION['userLang'] . '/hotel-exit-profile-warning.php' ?>
<div class="modal fade bs-modal-lg" id="hotel-exit-profile-warning">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $HotelExiteWarningModalLang['Sure about exiting?'] ?></h4>
      </div>
      <div class="modal-body">
      <p><?php echo $HotelExiteWarningModalLang['Exit text'] ?></p>
      </div>
      <div class="modal-footer">
        <a href="<?php echo $urlTree['hotel-profile-datos-de-reserva'] ?>" title="<?php echo $HotelExiteWarningModalLang['tooltip del boton'] ?>" class="btn btn-warning"><i class="fa fa-ban"></i> <?php echo $HotelExiteWarningModalLang['Yes I will do it later'] ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->