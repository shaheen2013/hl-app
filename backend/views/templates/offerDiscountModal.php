<?php include LANG . $_SESSION['userLang'] . '/offerDiscountModal.php' ?>
<div class="modal fade bs-modal-lg" id="offerDiscountModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $offerDiscountModalLang['Modifica la cantidad de descuento'] ?></h4>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="col-lg-4 ">
            <label for="discount"><?php echo $offerDiscountModalLang['Descuento'] ?></label>
            <div class="input-group">
              <input type="number" class="form-control" id="discount" name="discount" value="<?php echo (!empty($_SESSION['descuento']) ? $_SESSION['descuento'] : '')?>" min="1" max="100">
              <span class="input-group-addon"><?php echo $offerDiscountModalLang['%'] ?></span>
            </div>
          </div>
        </form>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $offerDiscountModalLang['Aceptar button'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->