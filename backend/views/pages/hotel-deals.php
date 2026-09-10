<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-deals.php' ?>
<?php include TEMPLATES . 'user-top-bar.php'; ?>
<div id="page-content-wrapper">
	<div class="container">
		<div class="user-utility-bar mb15">
			<h1><i class="fa fa-ticket"></i> <strong><?php echo $hotelDealsLang['Hotel deals list'] ?></strong></h1>
		</div>
		<div class="topMenu">
			<div class="media">
				<?php if(!empty ($arrayDatosHotel['logo'])){?>
				<a title="perfil" class="pull-left"><img width="80" height="80" alt="<?php echo $arrayDatosHotel['hotelName'] ?>" src="<?php $arrayDatosHotel['logo'] ?>" class="img-circle img-thumbnail"></a>
				<?php }else{ ?>
				<a title="perfil" href="<?php echo $urlTree['hotel-profile'] ?>" class="pull-left"><img width="80" height="80" alt="add your logo here" src="public/img/logo.jpg" class="img-circle img-thumbnail"></a>
				<?php } ?>
				<div class="media-body hotelPageDetails">
					<h1 class="media-heading pull-left"><?php echo $arrayDatosHotel['hotelName'] ?></h1>
					<ul class="pull-left pl mt">
						<?php for($i = 0; $i < $arrayDatosHotel['estrellas']; $i++){
							echo '<li><i class="fa fa-star"></i></li>';
						}?>
					</ul>
					<div class="clearfix"></div>
					<h3 class="cityTitle">List of referral deals</h3>
					<!-- <p>This hotel is part of: <strong><a href="#" title="Melia hotels and resort">Melia hotels and resort</a></strong></p> -->
				</div>
			</div>
		</div>
		<div id="fullContainer">
			<div class="col-lg-12">
				<?php if(!empty($arrayDealsHotel)){ ?>
				<?php foreach ($arrayDealsHotel as $deal) { ?>
				<div class="col-md-3 col-sm-4 col-xs-12 item">
					<div class="panel panel-default">
						<div class="panel-thumbnail"><img src="<?php echo BASE_PATH . DIR_IMG_OFERTAS . $deal['id_oferta'] . '/' . 'med_' . $deal['img']?>" class="img-responsive"></div>
						<div class="panel-body">
							<p class="lead"><a href="<?php echo BASE_PATH . $urlTree['oferta'] . '/' . string_sanitize($deal['nombre']) . '/' . $deal['id_oferta'] ?>" title=""><?php echo $deal['nombre'] ?></a></p>
							<p>Deal elegible if you bring <strong><?php echo $deal['n_referrals'] ?></strong> referrals</p>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php }else{ ?>
				<div class="col-lg-8 col-lg-offset-2 text-center">
					<i class="fa fa-meh-o fa-5x"></i>
					<h2>This Hotel has no referral offers at this moment</h2>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'isotope.pkgd.min.js'?>"></script>
<script src="<?php echo DIR_JS . 'imagesloaded.pkgd.min.js'?>"></script>
<script>
	$(document).ready(function () {
		var $container = $('#fullContainer');
		$container.imagesLoaded(function () {
			$container.masonry({
				itemSelector: '.item',
				columnWidth: '.item',
				transitionDuration: 0,
			});
		});
	});
</script>