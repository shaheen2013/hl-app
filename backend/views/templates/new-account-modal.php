<?php include LANG . $_SESSION['userLang'] . '/new-account-modal.php' ?>
<div class="modal fade bs-modal-lg" id="newAccountModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $NewAccountModalLang['Escribe tu correo electrónico'] ?></h4>
      </div>
      <div class="modal-body">
        <div class="text-center mt2 mb2">
          <p><?php echo $NewAccountModalLang['Puedes acceder a tu código promocional usando Facebook'] ?></p>
          <button class="btn btn-facebook btn-lg mt" onclick="fb_login();"><i class="fa fa-facebook"></i> <?php echo $NewAccountModalLang['Accede con Facebook'] ?></button>
        </div>
        <div class="row">
          <div class="col-sm-12 separator mb2">
            <div class="col-xs-5">
              <hr>
            </div>
            <div class="col-xs-2 text-center"><?php echo $NewAccountModalLang['O'] ?></div>
            <div class="col-xs-5">
              <hr>
            </div>
          </div>
        </div>
        <p><?php echo $NewAccountModalLang['Text get invite now'] ?></p>
        <form action="" method="post" id="newAccountForm" class="mt2">
          <label for="userEmail"><?php echo $NewAccountModalLang['Tu correo electrónico'] ?></label>
          <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="<?php echo $NewAccountModalLang['tu correo electrónico...'] ?>" required>
          <label class="mt2" for="userName"><?php echo $NewAccountModalLang['Tu nombre'] ?></label>
          <input type="text" class="form-control" id="userName" name="userName" placeholder="<?php echo $NewAccountModalLang['tu nombre...'] ?>" required>
          <input type="hidden" name="FBid" id="FBid">
          <input type="hidden" name="FBNFriends" id="FBNFriends">
          <input type="hidden" name="FBUserImg" id="FBUserImg">
          <input type="hidden" name="FBData" id="FBData" value="0">
          <input type="hidden" name="sec" id="sec" value="0">
          <input type="submit" class="btn btn-primary mt mt2" value="<?php echo $NewAccountModalLang['Regístrate en hotelinking'] ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-defaul" data-dismiss="modal"><?php echo $NewAccountModalLang['Cerrar'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->