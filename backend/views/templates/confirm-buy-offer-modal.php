<?php include LANG . $_SESSION['userLang'] . '/confirm-buy-offer-modal.php' ?>
<div class="modal fade bs-modal-lg" id="confirmBuyOfferModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-center"><?php echo $ConfirmBuyofferModalLang['Confirm that you want to adquire this offer'] ?></h4>
      </div>
      <div class="modal-body text-center">
      <form method="post"  action="<?php echo  $url_o . '/?acq=' .$id ?>">
        <input type="submit" class="btn btn-success mt2 btn-lg" value="<?php echo $ConfirmBuyofferModalLang['Adquire this offer'] ?>">
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $ConfirmBuyofferModalLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->