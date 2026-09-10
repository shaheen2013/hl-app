<?php include LANG . $_SESSION['userLang'] . '/cantPublishOfferModal.php' ?>
<div class="modal fade bs-modal-lg" id="cantPublishOfferModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $CantPublishOfferModalLang['Uuups you cant publish your offer'] ?></h4>
      </div>
      <div class="modal-body">
      <p><?php echo $CantPublishOfferModalLang['Uuups you cant publish your offer text'] ?></p>
        <ol>
          <li><?php echo $CantPublishOfferModalLang['Campaign image'] ?></li>
          <li><?php echo $CantPublishOfferModalLang['Campaign title'] ?></li>
          <li><?php echo $CantPublishOfferModalLang['Valid date from'] ?></li>
          <li><?php echo $CantPublishOfferModalLang['Valid until'] ?></li>
          <li><?php echo $CantPublishOfferModalLang['Offer description'] ?></li>
          <li><?php echo $CantPublishOfferModalLang['Offer conditions'] ?></li>
          <li><?php echo $CantPublishOfferModalLang['rewards and allotment'] ?></li>
          <li><?php echo $CantPublishOfferModalLang['hotel closed'] ?></li>
        </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $CantPublishOfferModalLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->