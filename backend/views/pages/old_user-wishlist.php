<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-wishlist.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>

<div class="container">

	<div class="user-utility-bar mb15">
		<h1><strong><i class="fa fa-heart"></i> <?php echo $UserWishlistLang['Your wishlist'] ?></strong></h1>
	</div>

	<div class="row">
		<div id="fullContainer">
		<?php if(!empty($arrayWishlist)) { ?>
			<?php foreach ($arrayWishlist as $oferta) { ?>
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12  mb2 shop-item" data-cost="<?php echo $oferta['puntos']; ?>" data-start-date="<?php echo $oferta['inicio_ord']; ?>" data-end-date="<?php echo $oferta['fin']; ?>" data-category="<?php echo $oferta['estrellas'] ?>" data-rating="<?php echo $oferta['rating']; ?>">
					<div class="offer-banner">
						<?php if($oferta['id_tipo_oferta'] == 'chk'){
							echo '<div class="offer-checkin pull-left"><i class="fa fa-lock"></i></div>';
						} ?>
						<div class="banner-sprite end-banner pull-left"></div>
						<div class="banner-price pull-left"><?php echo ($oferta['adq_ret'] == 'adq' ? '<i class="rubies rubix1 rubiesHL">rubies</i>' :'<i class="rubies rubix1">rubies</i>') ?> <?php echo number_format($oferta['puntos'],0,",","."); ?></div>
						<div class="banner-sprite start-banner pull-left"></div>
					</div>
					<a href="<?php echo $urlTree['user-wishlist'] ?>/?unwlst=<?php echo $oferta['id']; ?>" class="shop-offer-like"><i class="fa fa-heart verde fa-2x"></i></a>
					<div class="shop-item-container">
					<div class="can-buy-offer <?php echo( $oferta['puedeAdquirir'] == '0' ? 'no-se-puede-comprar' : 'se-puede-comprar') ?>"></div>
						<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $oferta['url']; ?>/<?php echo $oferta['id']; ?>" class="shop-offer-link" title="<?php echo $oferta['nombre']; ?>"></a>
						<div class="offer-text">
							<h3><a href="<?php echo $urlTree['oferta'] ?>/<?php echo $oferta['url']; ?>/<?php echo $oferta['id']; ?>" title="<?php echo $oferta['nombre']; ?>"><?php echo $oferta['nombre']; ?></a></h3>
							<div class="row">
								<div class="pull-left shop-hotel-name">
									<?php if ($oferta['logo'] == '0' || !file_exists( DIR_IMG_FICHA_HOTEL . $oferta['id_hotel'] . '/logo/' . $oferta['logo'])){?>
									<img class="img-circle" src="<?php echo DIR_IMG;?>logo.jpg" alt="<?php echo $oferta['hotelName']; ?>" width="30" height="30">
									<?php }else {?>
									<img class="img-circle" src="<?php echo DIR_IMG_FICHA_HOTEL ?><?php echo $oferta['id_hotel']; ?>/logo/<?php echo $oferta['logo'];?>" alt="<?php echo $oferta['hotelName']; ?>" width="30" height="30">
									<?php } ?> <?php echo $oferta['hotelName']; ?>
										<?php for ($i=0; $i < $oferta['estrellas']; $i++) {
											echo '<i class="fa fa-star"></i>';
										} ?>
								</div>
							</div>
						</div>
						<div class="shop-offer">
							<?php if ($oferta['img'] && file_exists( DIR_IMG_FICHA_HOTEL . $oferta['id_hotel'] . '/' . $oferta['id'] . '/' .$oferta['img'])) { ?>
							<img class="offer-img" src="<?php echo DIR_IMG_FICHA_HOTEL ?><?php echo $oferta['id_hotel']; ?>/<?php echo $oferta['id']; ?>/<?php echo $oferta['img']; ?>" alt="<?php echo $oferta['nombre']; ?>" width="275"/>
							<?php }else{ ?>
							<img class="offer-img" src="<?php echo DIR_IMG;?>img-placeholder.jpg" alt="<?php echo $oferta['nombre']; ?>" width="275"/>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php }else { ?>
			<div class="text-center mt2">
				<i class="fa fa-heart grisClaro fa-5x"></i>
				<h2><?php echo $UserWishlistLang['There are no offers in your wishlist'] ?></h2>
				<h4><?php echo $UserWishlistLang['Go to the rewards shop and andd some by clicking on the'] ?> <i class="fa fa-heart"></i> <?php echo $UserWishlistLang['icon of the offer'] ?></h4>
				<h5><?php echo $UserWishlistLang['Meanswhile maybe you are interested to know'] ?> <a href="<?php echo $urlTree['faq'] ?>" title="How to earn reward points"> <?php echo $UserWishlistLang['how to earn reward points'] ?></a></h5>
			</div>
		<?php } ?>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'isotope.pkgd.min.js'?>"></script>
<script src="<?php echo DIR_JS . 'imagesloaded.pkgd.min.js'?>"></script>
<?php include TEMPLATES . 'give-rewards-friend-modal.php' ?>
<script>
	$(document).ready(function () {
		var $container = $('#fullContainer');
		$container.imagesLoaded(function () {
			$container.masonry({
				itemSelector: '.shop-item',
				columnWidth: '.shop-item',
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
		$('.fichaOferta, .fichaHotel').hover(function(){
			$(this).children('.overlayer').fadeToggle('fast');
		});
		$('.give-rewards').click(function(e){
			e.preventDefault();
			$('#giveRewardsFriendModal').modal('show');
		})
	});
</script>