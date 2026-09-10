<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/logout.php' ?>
<div class="container vertical-align">
	<div class="col-lg-12">
		<div class="col-lg-6 col-lg-offset-3 text-center">
		<img src="<?php echo DIR_IMG; ?>login-logo.png" alt="logo" width="208" height="39" class="loginLogo">
		<h2><?php echo $LogoutLang['you have been successfully loged out'] ?></h2>
		<a href="/" title="volver al inicio" class="btn btn-primary mt"><?php echo $LogoutLang['Go back to Hotelinkings home page'] ?></a>
		</div>
	</div>
</div>