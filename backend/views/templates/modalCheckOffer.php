<?php include LANG . $_SESSION['userLang'] . '/modalCheckOffer.php' ?>
<div class="modal fade bs-modal-lg" id="modalCheckOffer">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $ModalCheckOfferLang['Ups'] ?></h4>
      </div>
      <div class="modal-body">
          <ul>
            <?php foreach ($checks as $message) {
              if (is_bool($message) === false) {
                $msgCode = ${'msg' . $message};
                echo '<li>' . $msgCode . '</li>';
              }
            } ?>
          </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $ModalCheckOfferLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->