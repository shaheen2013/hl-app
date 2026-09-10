<?php include LANG . $_SESSION['userLang'] . '/password-recovery-modal.php' ?>
<div class="modal fade bs-modal-lg" id="password-recovery">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $PasswordRecoveryModalLang['we need your email'] ?></h4>
      </div>
      <div class="modal-body">
        <form method="POST">
         <div class="col-lg-10 col-lg-offset-1">
           <label for="recovery_emal"><?php echo $PasswordRecoveryModalLang['email'] ?></label>
           <input type="email" class="form-control input-lg" id="recovery_emal" name="recovery_emal" placeholder="<?php echo $PasswordRecoveryModalLang['email text'] ?>" required >
         </div>
         <div class="col-lg-10 col-lg-offset-1">
          <input class="mt2 btn btn-primary" type="submit" name="recoveryForm" class="btn btn-lg btn-primary" value="<?php echo $PasswordRecoveryModalLang['Get new password'] ?>">
        </div>
        <div class="clearfix"></div>
      </form>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->