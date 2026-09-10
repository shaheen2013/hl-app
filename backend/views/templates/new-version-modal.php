  <?php include LANG . $_SESSION['userLang'] . '/new-version-modal.php' ?>
<div class="modal fade bs-modal-lg" id="new-version-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $NewVersionModalLang['A new update has been installed! (version: 0.2)'] ?></h4>
      </div>
      <div class="modal-body">
          <h2><?php echo $NewVersionModalLang['Hello everyone'] ?></h2>
          <p><?php echo $NewVersionModalLang['Our development team has been bussy the last few weeks, and they released a bunch of awesome features that will make your lives easier and your guest experiences more delightful.'] ?></p>
          <ul class="mt2">
            <li>
              <h4><?php echo $NewVersionModalLang['Chain Management'] ?></h4>
              <p><?php echo $NewVersionModalLang['Chain Management text'] ?></p>
            </li>
            <li>
              <h4><?php echo $NewVersionModalLang['Improved Analytics'] ?></h4>
              <p><?php echo $NewVersionModalLang['Improved Analytics text'] ?></p>
            </li>
            <li>
              <h4><?php echo $NewVersionModalLang['Guest Redesign'] ?></h4>
              <p><?php echo $NewVersionModalLang['Guest Redesign text'] ?></p>
            </li>
            <li>
              <h4><?php echo $NewVersionModalLang['Gift rewards'] ?></h4>
              <p><?php echo $NewVersionModalLang['Gift rewards text'] ?></p>
            </li>
            <li>
              <h4><?php echo $NewVersionModalLang['Gift offers'] ?></h4>
              <p><?php echo $NewVersionModalLang['Gift offers text'] ?></p>
            </li>
            <li>
              <h4><?php echo $NewVersionModalLang['Guest invite process'] ?></h4>
              <p><?php echo $NewVersionModalLang['Guest invite process text'] ?></p>
            </li>
            <li>
              <h4><?php echo $NewVersionModalLang['Guest rewards log'] ?></h4>
              <p><?php echo $NewVersionModalLang['Guest rewards log text'] ?></p>
            </li>
            <li>
              <h4><?php echo $NewVersionModalLang['Bugs fixed'] ?></h4>
              <p><?php echo $NewVersionModalLang['Bugs fixed text'] ?></p>
            </li>
          </ul>
          <p class="mt2"><?php echo $NewVersionModalLang['thanks and email'] ?></p>
          <p class="mt2">
            <?php echo $NewVersionModalLang['best regards'] ?>
          </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $NewVersionModalLang['close'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->