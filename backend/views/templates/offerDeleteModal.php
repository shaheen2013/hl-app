<?php include LANG . $_SESSION['userLang'] . '/offerDeleteModal.php' ?>
<div class="modal fade bs-modal-lg" id="offerDeleteModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content text-left">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $offerDeleteModalLang['Delete offer'] ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $offerDeleteModalLang['textDeleteOffer'] ?> </p>
      </div>
      <div class="modal-footer">
        <a href="#" title="delete" class="btn btn-warning offerDeleteModalBtn"><i class="fa fa-trash-o"></i> <?php echo $offerDeleteModalLang['Delete offer'] ?></a>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $offerDeleteModalLang['Cancel'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->