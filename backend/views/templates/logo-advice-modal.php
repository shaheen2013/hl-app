<?php include LANG . $_SESSION['userLang'] . '/logo-advice-modal.php' ?>
<div class="modal fade bs-modal-lg" id="logo-advice-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $LogoAdivceModalLang['Upload a good logo'] ?></h4>
      </div>
      <div class="modal-body">
          <p><?php echo $LogoAdivceModalLang['logo specifications'] ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $LogoAdivceModalLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
