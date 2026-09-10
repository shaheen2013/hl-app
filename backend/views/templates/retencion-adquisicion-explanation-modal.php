<?php include LANG . $_SESSION['userLang'] . '/retencion-adquisicion-explanation-modal.php' ?>
<!-- Modal -->
<div class="modal fade" id="ret-adq-mod-hel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $RetencionAdquisicionModalLang['Adquisicion y retencion'] ?></h4>
      </div>
      <div class="modal-body">
          <p><?php echo $RetencionAdquisicionModalLang['Explicación de las ofertas de retención y adquisición'] ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $RetencionAdquisicionModalLang['Close'] ?></button>
      </div>
    </div>
  </div>
</div>