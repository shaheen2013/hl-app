<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-ofertas.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="container">

	<div class="user-utility-bar mb15">
		<h1><i class="fa fa-ticket"></i> <strong><?php echo $UserOfertasLang['Your vouchers'] ?></strong></h1>
	</div>

	<div class="row">
		<div id="fullContainer">
			<?php if(!empty($arrayUserOfertas)) { ?>
			<?php foreach ($arrayUserOfertas as $oferta) { ?>
			<div class="item col-sm-3 col-xs-12 cupon-item mb2" data-cost="<?php echo $oferta['puntos']; ?>" data-start-date="<?php echo $oferta['inicio']; ?>" data-end-date="<?php echo $oferta['fin']; ?>" data-category="<?php echo $oferta['estrellas'] ?>" data-rating="<?php echo $oferta['rating']; ?>">
				<a href="<?php echo $urlTree['cupon'] ?>/<?php echo $oferta['nombre_san'] ?>/<?php echo $oferta['id'] ?>" title="See voucher details">
					<div class="cupon-resumen">
						<div class="cupon-img">
							<?php if ($oferta['img'] && file_exists( DIR_IMG_OFERTAS . $oferta['id'] . '/' .$oferta['img'])) { ?>
							<img class="cupon-offer-img" src="<?php echo DIR_IMG_OFERTAS ?><?php echo $oferta['id']; ?>/<?php echo $oferta['img']; ?>" alt="<?php echo $oferta['nombre']; ?>" >
							<?php }else{ ?>
							<img class="cupon-offer-img" src="<?php echo DIR_IMG . 'img-placeholder.jpg' ?>" alt="img">
							<?php } ?>
						</div>
						<div class="cupon-info text-center">
							<?php if(!empty($oferta['id_cadena']) && file_exists($oferta['logo_cadena'] )){?>
							<img class="img-circle img-thumbnail" src="<?php echo oferta['logo_cadena'];?>" alt="chain logo" width="70" height="70">
							<?php } else if ($oferta['logo']  && file_exists($oferta['logo'] )) {?>
							<img class="img-circle img-thumbnail" src="<?php $oferta['logo'];?>" alt="hotel logo" width="70" height="70">
							<?php }else{ ?>
							<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG . 'logo.jpg' ?>" alt="hotel logo" width="70" height="70">
							<?php } ?>
							<p><span class="cupon-hotel-name">
								<?php if(!empty($oferta['id_cadena'])){?>
								<?php echo $oferta['chainName']; ?></span><br>
								<?php }else{?>
								<?php echo $oferta['hotelName']; ?></span><br><?php for ($i=0; $i < $oferta['estrellas']; $i++) {
									echo '<i class="fa fa-star"></i> ';
								} ?>
								<?php }?>
							</p>
							<h3><?php echo $oferta['nombre']; ?></h3>
							<span class="until-date"><?php echo $UserOfertasLang['valid until'] ?> <strong><?php echo $oferta['fin']; ?></strong></span>
						</div>
						<div class="cupon-bottom">
							<?php if($oferta['nOfertas'] > 1){
								echo '<img src="'.DIR_IMG.'voucher-many-bottom.png" alt="voucher bottom img">';
							}else{
								echo '<img src="'.DIR_IMG.'voucher-bottom.png" alt="voucher bottom img">';
							} ?>
						</div>
					</div>
				</a>
			</div>
			<?php } ?>
			<?php } else {?>
			<div class="text-center mt2">
				<i class="fa fa-ticket grisClaro fa-5x"></i>
				<h2><?php echo $UserOfertasLang['There are no available vouchers right now'] ?></h2>
				<h4><?php echo $UserOfertasLang['Go to the'] ?> <a href="<?php echo $urlTree['tienda'] ?>" title="rewards shop"><?php echo $UserOfertasLang['rewards shop and and buy some awesome offers from hotels!'] ?></h4>
				<h5><?php echo $UserOfertasLang['Meanswhile maybe you are interested to know'] ?> <a href="<?php echo $urlTree['faq'] ?>" title="How to earn reward points"> <?php echo $UserOfertasLang['how to earn reward points'] ?></a></h5>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'isotope.pkgd.min.js'?>"></script>
<script src="<?php echo DIR_JS . 'imagesloaded.pkgd.min.js'?>"></script>
<script>
	$(window).load(function(){
		$('#sorts').on( 'click', 'button', function() {
			var sortValue = $(this).attr('data-sort-by');
			if($(this).hasClass('asc')){
				$(this).removeClass('asc').addClass('desc');
			}else{
				$(this).removeClass('desc').addClass('asc');
			}
			if($(this).hasClass('asc')){
				$container.isotope({ sortBy: sortValue, sortAscending: true});
			}else{
				$container.isotope({ sortBy: sortValue, sortAscending: false });
			}
		});
	});
	$(document).ready(function () {
		var $container = $('#fullContainer');
		$container.imagesLoaded(function () {
			$container.masonry({
				itemSelector: '.item',
				columnWidth: '.item',
				transitionDuration: 0,
				getSortData: {
					cost: '[data-cost] parseInt',
					startDate: '[data-start-date]',
					endDate: '[data-end-date]',
					category: '[data-category] parseInt',
					rating: '[data-rating] parseFloat'
				}
			});
		});
	});
</script>