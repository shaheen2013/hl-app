<?php include LANG . $_SESSION['userLang'] . '/categorias-explanation-modal.php' ?>
<!-- Modal -->
<div class="modal fade" id="cat-hel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $CategoriasExplanationModalLang['Categorias y sub-categorias'] ?></h4>
      </div>
      <div class="modal-body">
          <p><?php echo $CategoriasExplanationModalLang['Epxlicación Categorias y sub-categorias'] ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>