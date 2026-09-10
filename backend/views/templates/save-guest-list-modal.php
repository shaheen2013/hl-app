<?php include LANG . $_SESSION['userLang'] . '/save-guest-list-modal.php' ?>
<div class="modal fade bs-modal-lg" id="saveGuestListModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo $SaveGuestListModalLang['Save this list with a name'] ?></h4>
			</div>
			<div class="modal-body">
				<form action="<?php echo $urlTree['invitar-usuarios-2'] ?>" method="post" id="saveListModalForm">

          <label for="listName"><?php echo $SaveGuestListModalLang['New list name'] ?></label>
          <input type="text" class="form-control" id="listName" name="listName" placeholder="<?php echo $SaveGuestListModalLang['Write the list name here'] ?>" >
          
          <div class="row">
            <div class="col-sm-12 separator mb mt2">
              <div class="col-xs-5"><hr></div>
              <div class="col-xs-2 text-center"><?php echo $SaveGuestListModalLang['Or'] ?></div>
              <div class="col-xs-5"><hr></div>
            </div>
          </div>
          
          <label for="listName"><?php echo $SaveGuestListModalLang['List name'] ?></label>
          <select name="addToList" id="addToListSelect" class="form-control">
            <option class="guestListOption" value="0">...</option>
            <?php foreach($listas as $lista){?>
            <option class="guestListOption" value="<?php echo $lista['id'] ?>"><?php echo $lista['nombre'] ?></option>
            <?php }?> 
          </select>
          
          <div class="col-lg-5 mb pl0">
            <input type="submit" value="<?php echo $SaveGuestListModalLang['Save'] ?>" class="btn btn-success btn-lg mt2 btn-block" name="saveUsers">
          </div>

          <input type="hidden" name="field-1" value="0">
          <input type="hidden" name="field-2" value="1">
          <input type="hidden" name="field-3" value="2">
          <input type="hidden" name="field-4" value="3">
          <input type="hidden" name="field-5" value="4">
          <input type="hidden" name="field-6" value="5">
          <input type="hidden" name="ruta" value="<?php echo $ruta_archivo; ?>">
          <input type="hidden" name="archivo" value="<?php echo $nombre_archivo; ?>">

        </form>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $SaveGuestListModalLang['Cancel'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->