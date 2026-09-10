<?php include LANG . $_SESSION['userLang'] . '/userHasCouponsModal.php' ?>
<div class="modal fade bs-modal-lg" id="userHasCouponsModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $UserHasCopuponsModalLang['Hey! this user have coupons ready for redeem in your hotel'] ?></h4>
      </div>
      <div class="modal-body text-center">
          <p><?php echo $UserHasCopuponsModalLang['¿Do you want to see them now?'] ?></p>
          <a href="<?php echo $urlTree['checkin-paso2'] ?>/<?php echo $id_usuario ?>/" class="btn btn-lg btn-primary"><?php echo $UserHasCopuponsModalLang['Yes, go to coupons list of this user'] ?></a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $UserHasCopuponsModalLang['Close'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->