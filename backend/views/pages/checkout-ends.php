<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG .$_SESSION['userLang']. '/checkout-ends.php' ?>
<div id="wrapper">
	<?php if(!empty($_SESSION['h_logueado'])){
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(!empty($_SESSION['staff_logueado'])){
		include TEMPLATES . 'check-sidebar.php';
	} ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php //include top menu ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
			<h1 class="pull-left"><i class="fa fa-check-circle-o"></i> <?php echo $checkoutEndsLang['Top bar message'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 text-center">
				<i class="fa fa-check-circle-o fa-6x verde"></i>
				<h1><strong><?php echo $checkoutEndsLang['Well done message'] ?></strong>
					<div class="row">
						<div class="col-lg-12 mt2 text-center">
							<h2><?php echo $checkoutEndsLang['Check out ended, you can go back to check out dashboard from here:'] ?></h2>
							<a href="<?php echo $urlTree['checkout'] ?>" title="go back to checkout" class="btn btn-lg btn-primary mt2"><i class="fa fa-sign-out"></i> <?php echo $checkoutEndsLang['go back to guest checkout button'] ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>