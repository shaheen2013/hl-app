<div class="mt2">
    <div class="col-lg-12 text-center">
        <h2>Multiplicadores de Marketing</h2>
    </div>
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 mt2">
            <?php if($changed): ?>
                <div class="alert alert-success">Cambios realizados</div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="singleClick">Precio de un click en Facebook (USD)</label>
                    <input type="text" class="form-control" id="singleClick" placeholder="xxx.xx" name="singleClick" value="<?php echo $multipliers['single_click_price'] ?>">
                </div>
                <div class="form-group">
                    <label for="thousandImpressions">Precio de mil impresiones en Facebook (USD)</label>
                    <input type="text" class="form-control" id="thousandImpressions" placeholder="xxx.xx" name="thousandImpressions" value="<?php echo $multipliers['thousand_impressions_price'] ?>">
                </div>
                <div class="form-group">
                    <label for="impressionsPercentage">Porcentaje de impresiones en Facebook (%)</label>
                    <input type="text" class="form-control" id="impressionsPercentage" placeholder="xxx" name="impressionsPercentage" value="<?php echo $multipliers['impressions_percents']?>">
                </div>
                <button type="submit" class="btn btn-primary mt2">Cambiar multiplicadores</button>
            </form>
            <a href="<?php echo SECURE_BASE_PATH . 'private/' . $urlTree['private-invitar-hotel']?>" class="btn btn-default mt2"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</a>
        </div>
    </div>
</div>