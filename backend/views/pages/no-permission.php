<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/no-permissions.php' ?>
<div class="col-sm-6 col-sm-offset-3 text-center mt4">
	<i class="fa fa-ban fa-6x"></i>
	<h1><?php echo $noPermissionsLang['Upps...'] ?></h1>
	<p><?php echo $noPermissionsLang['Your product permissions doesn´t aloud you to access this page, You will need to contact our customer service.'] ?></p>
	<a href="mailto:customerservice@hotelinking.com" class="btn btn-success mt"><i class="fa fa-envelope-o"></i>
 <?php echo $noPermissionsLang['Contact customer service now'] ?></a>
	<button class="btn btn-primary mt" onclick="goBack()"><i class="fa fa-arrow-circle-o-left"></i> <?php echo $noPermissionsLang['Go Back'] ?></button>
</div>
<script>
function goBack() {
    window.history.back();
}
</script>
