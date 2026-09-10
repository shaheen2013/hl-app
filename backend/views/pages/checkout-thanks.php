<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG .$_SESSION['userLang']. '/checkout-thanks.php' ?>
<div id="wrapper">
	<?php if(!empty($_SESSION['h_logueado'])){
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(!empty($_SESSION['staff_logueado'])){
		include TEMPLATES . 'check-sidebar.php';
	} ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include(TEMPLATES . 'check-out-steps.php'); ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-sign-out"></i> <?php echo $checkoutThanksLang['User rating'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent mt2" id="fullContainer">
			<div class="row text-center">
				<?php if ($datosUsuario['img']){?>
				<img class="img-circle img-thumbnail" src="<?php echo $datosUsuario['img'];?>" width="50" height="50">
				<?php }else{?>
				<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG;?>avatar.jpg" width="50" height="50">
				<?php } ?>
				<strong><?php echo $datosUsuario['nombre'];?></strong> <span class="pl"><?php echo $checkoutThanksLang['Total rubies in your hotel'] ?> <i class="rubies rubix1 pl">rubies</i> <strong><?php echo $datosUsuario['puntos'];?></strong></span>
			</div>
			<div class="row mt4">
				<div class="col-lg-12">
					<form action="<?php echo $urlTree['checkout-ends'] ?>" method="POST" class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 ">
						<div class="row mb2">
							<div class="mb2 mt4">
								<div class="slider sliderRating mt4"></div>
							</div>
						</div>
						<input type="hidden" name="rating" id="userRating" value="5">
						<div class="row mt">
							<label for="hotelUserComment"><?php echo $checkoutThanksLang['write your opinion about this guest'] ?></label>
							<textarea name="hotelUserComment" id="hotelUserComment" class="form-control" placeholder="<?php echo $checkoutThanksLang['write here...'] ?>"></textarea>
						</div>
						<input type="hidden" name="userId" value="<?php echo $id_usuario ?>">
						<div class="row text-center">
							<div class="mb2 mt2">
								<input name="save-survey" type="submit" class="btn btn-lg btn-success" value="<?php echo $checkoutThanksLang['Send rating and comment'] ?>">
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 mt2 text-center">
					<h2><?php echo $checkoutThanksLang['Check out ended, you can go back to check out dashboard from here:'] ?></h2>
					<a href="checkout" title="go back to checkout" class="btn btn-lg btn-primary mt2"><i class="fa fa-sign-out"></i> <?php echo $checkoutThanksLang['skip and go back to dashboard'] ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {
		var initialValue = 5;
		var sliderTooltip = function(event, ui) {
			var curValue = ui.value || initialValue;
			var tooltip = '<div class="rangeValue">' + curValue + '</div>';
			$('.ui-slider-handle').html(tooltip);
			$('#userRating').val(curValue);
		}
		$('.sliderRating').slider({
			range: "min",
			min:0.1,
			max:10,
			value:5,
			step:0.1,
			create:sliderTooltip,
			slide:sliderTooltip
		});
	});
</script>