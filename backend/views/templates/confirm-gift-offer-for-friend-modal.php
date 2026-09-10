<?php include LANG . $_SESSION['userLang'] . '/confirm-gift-offer-for-friend-modal.php' ?>
<div class="modal fade bs-modal-lg" id="confirmGiftOfferFriendModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-center"><?php echo $UConfirmGiftOfferForFriendModalLang['Confirm that you want to gift this offer to a friend'] ?></h4>
      </div>
      <div class="modal-body text-center">
      <form method="POST" action="<?php echo  $url_o ?>">
          <label for="friendEmail"><?php echo $UConfirmGiftOfferForFriendModalLang['Your friend´s email'] ?></label>
          <input type="text" class="form-control" id="friendEmail" name="friendEmail" placeholder="<?php echo $UConfirmGiftOfferForFriendModalLang['Write here the email of your friend...'] ?>" required >
          <p class="help-block"><?php echo $UConfirmGiftOfferForFriendModalLang['If your friend doesn´t have an account in hotelinking we will send an invite along with your gift'] ?></small></p>
          <input type="hidden" name="cuponId" id="cupon-id">
          <input type="submit" class="btn btn-success mt2 btn-lg" value="<?php echo $UConfirmGiftOfferForFriendModalLang['Send this offer to my friend'] ?>">
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $UConfirmGiftOfferForFriendModalLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->