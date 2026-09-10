<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/redeem-ko.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include(TEMPLATES . 'check-out-steps.php'); ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-check-circle-o"></i> <?php echo $RedeemKOLang['Cupón no validado'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 text-center">
			<i class="fa fa-minus-circle fa-6x naranja"></i>
				<h1><strong><?php echo $RedeemKOLang['Upppsss, ha surgido un error'] ?></h1>
				<div class="alert alert-danger"><?php echo $RedeemKOLang['Este cupón ya ha sido canjeado'] ?></div>
				<a href="<?php echo $urlTree['checkin-paso1'] ?>" title="Volver al check in" class="btn btn-lg btn-primary mt"><?php echo $RedeemKOLang['Volver al Check in'] ?></a>
			</div>
		</div>
	</div>
</div>