<?php include LANG . $_SESSION['userLang'] . '/imageUploadErrorModal.php' ?>
<div class="modal fade bs-modal-lg" id="imageUploadErrorModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title naranja"><i class="fa fa-times-circle-o"></i> <?php echo $ImageUploadErrorModalLang['Your uploaded image is too small'] ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $ImageUploadErrorModalLang['Uploaded images needs to be at least 710px width and 500px height'] ?></p>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $ImageUploadErrorModalLang['Cancel'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->