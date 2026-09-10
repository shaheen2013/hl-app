<?php include LANG . $_SESSION['userLang'] . '/issuesContactModal.php' ?>
<div class="modal fade bs-modal-lg" id="issuesContactModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $IssuesContactModalLang['Send us your issues'] ?></h4>
      </div>
      <div class="modal-body">
      <form action="" method="POST">
          <textarea name="issue" class="form-control" cols="30" rows="10"></textarea>
          <input type="submit" class="mt btn btn-primary" value="<?php echo $IssuesContactModalLang['Send us you issue'] ?>">
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $IssuesContactModalLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->