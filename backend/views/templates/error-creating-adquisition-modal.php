<?php include LANG . $_SESSION['userLang'] . '/error-creating-adquisition-modal.php' ?>
<div class="modal fade bs-modal-lg" id="error-creating-adquisition-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $ErrorCreatingAdquisitionModallang['Aún no estás listo para poder crear ofertas de adquisición'] ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $ErrorCreatingAdquisitionModallang['Para poder crear ofertas de adquisición se han de tener rellenados lo siguientes datos en tu'] ?> <a href="<?php echo $urlTree['hotel-profile-2'] ?>" title="ficha de hotel"><?php echo $ErrorCreatingAdquisitionModallang['profile'] ?></a></p>
        <ul>
          <li><?php echo $ErrorCreatingAdquisitionModallang['Categoría'] ?></li>
          <li><?php echo $ErrorCreatingAdquisitionModallang['Rango de precios'] ?></li>
          <li><?php echo $ErrorCreatingAdquisitionModallang['Selector de temporadas ( no puedes tener temporadas en N/S)'] ?></li>
        </ul>

          <div class="text-center">
            <a href="<?php echo $urlTree['hotel-profile-2'] ?>" class="btn btn-primary btn-lg mt2 start-tour-btn" title="perfil"><?php echo $ErrorCreatingAdquisitionModallang['Ir a mi ficha de hotel'] ?></a>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $ErrorCreatingAdquisitionModallang['Cerrar'] ?></button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->