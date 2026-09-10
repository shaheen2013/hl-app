<?php include LANG . $_SESSION['userLang'] . '/offerCostAndQuotaModal.php' ;
include_once LIB.'convertirDivisas.php';?>
<div class="modal fade bs-modal-lg" id="offerCostAndQuota">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $OfferCostAndQuotaModalLang['Modify cost, quota, and requeriments of this campaign'] ?> <button class="azul btn-helper costAndQuotaBtn" title="ayuda"><i class="fa fa-question-circle"></i></button></h4>
      </div>
      <div class="modal-body">
        <form action="">
          <?php if (!empty($_SESSION['offerMethod']) && $_SESSION['offerMethod'] != 'adq'){ ?>
          <div class="col-lg-4 ">
            <label for="money"><?php echo $OfferCostAndQuotaModalLang['Reward points cost'] ?></label>
            <div class="input-group">
              <input type="number" class="form-control" id="money" name="money" min="0" value="<?php echo $_SESSION['coste'];?>">
              <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown" aria-expanded="false"><span class="divisa-toggle"><?php echo $_SESSION['moneda'] ?></span> <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right drop-down-divisas" role="menu">
                  <?php foreach ($selectDivisas as $key => $divisa) { ?>
                  <li><a href="#" data-value="<?php echo $key?>" class="a-divisa"><?php echo $divisa?></a></li>
                  <?php } ?>
                </ul>
                <input class="input-divisa" type="hidden" id="divisa" name="divisa" value="<?php echo $_SESSION['moneda'] ?>">
              </div><!-- /btn-group name currency-->
            </div><!-- /input-group -->

          </div>
          <?php }?>
          <div class="col-lg-4 ">
            <label for="quota"><?php echo $OfferCostAndQuotaModalLang['Allotment'] ?></label>
            <input type="number" class="form-control" id="quota" name="quota" value="<?php echo (!empty($_SESSION['cupo']) ? $_SESSION['cupo'] : '')?>" min="0" >
          </div>
          <?php if (!empty($_SESSION['offertype']) && $_SESSION['offertype'] != 'chk'){?>
          <div class="col-lg-4 ">
            <label for="requeriments"><?php echo $OfferCostAndQuotaModalLang['Nights required'] ?></label>
            <input type="number" class="form-control" id="requeriments" name="requeriments" value="<?php echo (!empty($_SESSION['requerimientos']) ? $_SESSION['requerimientos'] : '')?>" min="0">
          </div>
          <?php } ?>
        </form>
        <div class="costAndQuotaExplanation mt2">
          <div class="col-lg-12 mt">
            <p><strong><?php echo $OfferCostAndQuotaModalLang['Reward points cost'] ?></strong></p>
            <p><?php echo $OfferCostAndQuotaModalLang['Reward points cost explanation'] ?></p>
          </div>
          <div class="col-lg-12 mt">
            <p><strong><?php echo $OfferCostAndQuotaModalLang['Allotment'] ?></strong></p>
            <p><?php echo $OfferCostAndQuotaModalLang['Allotment explanation'] ?></p>
          </div>
          <div class="col-lg-12 mt">
            <p><strong><?php echo $OfferCostAndQuotaModalLang['Nights required'] ?></strong></p>
            <p><?php echo $OfferCostAndQuotaModalLang['Requirements explanation'] ?></p>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $OfferCostAndQuotaModalLang['Confirm'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->