<?php include LANG . $_SESSION['userLang'] . '/cantSaveOfferModal.php' ?>
<div class="modal fade bs-modal-lg" id="cantSaveOfferModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $CantSaveOfferModalLang['Upps!, no hemos podido guardar tu oferta'] ?></h4>
      </div>
      <div class="modal-body">
          <p><?php echo $CantSaveOfferModalLang['Before saving any draft of your campaigns make sure the below is completed:'] ?></p>
      <ol>
        <li><?php echo $CantSaveOfferModalLang['Un nombre'] ?></li>
        <li><?php echo $CantSaveOfferModalLang['Si es de retención o de adquisición ( en la página de información básica)'] ?></li>
      </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $CantSaveOfferModalLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
