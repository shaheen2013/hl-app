<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/404.php' ?>
<div class="container mt2 text-center">
	<img src="<?php echo DIR_IMG . 'login-logo.png' ?>" class="mt4" alt="hotelinking logo">
	<h1 class="not-found"><strong><?php echo $lang['error 404'] ?></strong></h1>
	<p><?php echo $lang['ups, esta página no existe'] ?></p>
	<button class="btn btn-primary mt" onclick="goBack()"><i class="fa fa-arrow-circle-o-left"></i> <?php echo $lang['Go back'] ?></button>
</div>
<script>
function goBack() {
    window.history.back();
}
</script>