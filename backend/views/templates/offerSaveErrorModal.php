<?php include LANG . $_SESSION['userLang'] . '/OfferSaveErrorModal.php' ?>
<div class="modal fade bs-modal-lg" id="offerSaveErrorModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title naranja"><i class="fa fa-times-circle-o"></i> <?php echo $OfferSaveErrorModalLang['Remember before saving an offer'] ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $OfferSaveErrorModalLang['Before saving any draft of your campaigns make sure the below is completed:'] ?></p>
        <ul>
          <li><?php echo $OfferSaveErrorModalLang['Campaign title'] ?></li>
          <li><?php echo $OfferSaveErrorModalLang['Pick one target/goal option for your campaign from the previous screen'] ?></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->