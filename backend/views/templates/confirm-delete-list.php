<?php include LANG . $_SESSION['userLang'] . '/confirm-delete-list.php' ?>
<div class="modal fade bs-modal-lg" id="confirm-delete-list">
  <div class="modal-dialog modal-lg">
    <div class="modal-content text-center">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $ConfirmDeleteListLang['Are you sure you want to delete this list?'] ?></h4>
      </div>
      <div class="modal-body">
      <a href="#" title="delete" class="btn btn-warning btn-lg deleteListBtn"><i class="fa fa-trash-o"></i> <?php echo $ConfirmDeleteListLang['Delete this list'] ?></a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $ConfirmDeleteListLang['Cancel'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->