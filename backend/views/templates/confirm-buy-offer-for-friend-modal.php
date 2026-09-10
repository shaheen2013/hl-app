<?php include LANG . $_SESSION['userLang'] . '/confirm-buy-offer-for-friend-modal.php' ?>
<div class="modal fade bs-modal-lg" id="confirmBuyOfferFriendModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-center"><?php echo $ConfirmBuyOfferForFriendModalLang['Confirm that you want to adquire this offer for a friend'] ?></h4>
      </div>
      <div class="modal-body text-center">
      <form action="<?php echo  $url_o .'/'?>" method="POST">
          <label for="friendEmail"><?php echo $ConfirmBuyOfferForFriendModalLang['Your friend email'] ?></label>
          <input type="email" class="form-control" id="friendEmail" name="friendEmail" placeholder="<?php echo $ConfirmBuyOfferForFriendModalLang['Write here the email of your friend...'] ?>" required >
          <p class="help-block"><?php echo $ConfirmBuyOfferForFriendModalLang['If your friend doesn´t have an account in hotelinking we will send an invite along with your gift'] ?></small></p>
          <input type="hidden" name="offerId" id="cupon-id" value="<?php echo $id_oferta ?>">
          <input type="submit" class="btn btn-success mt2 btn-lg" value="<?php echo $ConfirmBuyOfferForFriendModalLang['Adquire this offer and send to my friend'] ?>">
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $ConfirmBuyOfferForFriendModalLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->