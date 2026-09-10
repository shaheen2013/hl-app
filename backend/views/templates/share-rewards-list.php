<!-- Modal -->
<div class="modal fade" id="rewardsListModal" tabindex="-1" role="dialog" aria-labelledby="rewardsListModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $referralShareLang['Check out the rewards you can win modal'] ?></h4>
      </div>
      <div class="modal-body">
          <?php foreach ($goals as $goal) { ?>
          <div class="panel panel-default">
            <div class="panel-body">
             <div class="media">
               <a class="pull-left" href="#">
                 <img class="media-object" src="<?php echo DIR_IMG_OFERTAS . $goal['id_oferta'] . '/small_' . $goal['img'] ?>" alt="Image">
               </a>
               <div class="media-body">
                  <small><strong><?php echo $referralShareLang['Bringing'] ?> <?php echo $goal['n_referrals'] ?> <?php echo $referralShareLang['referrals to our hotel'] ?></strong></small>
                 <h4 class="media-heading mt"><?php echo $goal['nombre'] ?></h4>
                 <p><?php echo $goal['descripcion'] ?></p>
               </div>
             </div>
           </div>
          </div>
         <?php } ?>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $referralShareLang['Close'] ?></button>
    </div>
  </div>
</div>
</div>