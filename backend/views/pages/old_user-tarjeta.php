<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-tarjeta.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="row">
	<div class="user-utility-bar">
			<h1 <?php echo ($detect->isMobile() && !$detect->isTablet() ? '' : 'class="pull-left"' )?>><i class="fa fa-credit-card"></i> <?php echo $UserTarjetaLang['Your loyalty card'] ?></h1>
			<div <?php echo ($detect->isMobile() && !$detect->isTablet() ? '' : 'class="pull-right"' )?>>
				<?php include TEMPLATES . 'user-profile-menu.php'; ?>
			</div>
	</div>
</div>

<div class="mainContent" id="fullContainer">
	<div class="col-lg-12">
		<div class="col-lg-6 col-lg-offset-3">
			<?php if (isset($errorMsg)){ ?>
			<div class="alert alert-danger fade in text-center">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<p><?php echo $errorMsg; ?></p>
			</div>
			<?php } ?>
			<div class="col-lg-12 mb text-center">
				<h2 class="text-center mb2"><?php echo $UserTarjetaLang['Your loyalty card here'] ?></h2>
				<div class="center-block loyalty-card">
					<img id="userCard" src="<?php echo $imagen ?>" alt="tarjeta de usuario" width="480">
				</div>
				<div class="row center-block mt2">
					<button class="btn btn-default printImgButton" title="Print card" onClick="printImage();"><i class="fa fa-print"></i> <?php echo $UserTarjetaLang['Print or download loyalty card'] ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function printImage() {
		pwin = window.open(document.getElementById("userCard").src,"_blank");
		pwin.onload = function () {window.print();}
	}
</script>