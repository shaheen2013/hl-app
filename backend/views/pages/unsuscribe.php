<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php 
  include LANG . $_SESSION['userLang'].'/unsuscribe.php';
  include TEMPLATES . 'user-top-bar.php'; 
  ?>
<div class="mt2 col-md-6 col-md-offset-3">
  <?php if(!$preunsuscribe) {?>
    <div class="panel panel-default">
      <div class="panel-body">
         <h4><?php echo $unsuscribeLang['Are you sure you want to unsuscribe?'] ?></h4>
         <p><?php echo $unsuscribeLang['By click on the button <strong>confirm</strong>'] ?></p>
         <ul>
           <?php echo $unsuscribeLang['lis'] ?>
         </ul>
         <a href="http://hotelinking.com" title="hotelinking" class="btn btn-success mt2"><?php echo $unsuscribeLang['Exit this screen'] ?></a>
         <a href="<?php echo $actual_link ?>&p=t" class="btn btn-danger mt2"><?php echo $unsuscribeLang['Confirm unsuscribe'] ?></a>
      </div>
    </div>
  <?php }else{ ?>
    <?php if(!empty($unsuscribe)){ ?>
      <div class="alert alert-success text-center" role="alert">
        <h4><i class="fa fa-check-circle-o"></i> <?php echo $unsuscribeLang['You are successfully unsuscribed from notifications.'] ?></h4>
      </div>
      <p class="mt2 text-center"><?php echo $unsuscribeLang['If you want to suscribe again, go to your hotelinking profile and activate notifications again.'] ?></p>
    <?php }else{ ?>
      <div class="alert alert-danger text-center" role="alert">
        <h4><?php echo $unsuscribeLang['There was an error with the unsuscribe process, please use alternative method. Mail us at'] ?> <a href="mailto:support@hotelinking.com" title="Hotelinking support">support@hotelinking.com</a> <?php echo $unsuscribeLang['or try again later.'] ?></h4>
      </div>
    <?php } ?>
  <?php } ?>
</div>