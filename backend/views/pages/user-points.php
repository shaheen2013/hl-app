<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-points.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="container">
	<div class="user-utility-bar mb15">
		<h1 class="pull-left"><strong><i class="rubies rubix3">rubies</i> <?php echo $UserPointslang['Your reward points'] ?></strong></h1>
		<?php include TEMPLATES . 'user-reward-menu.php' ?>
	</div>
	<div class="clearfix"></div>
	<div class="row">
		<?php if(!empty($arrayPuntosUsuario) || !empty($puntos_hl)){ ?>
		<div class="item col-lg-3 col-md-4 col-sm-6 col-xs-12  mb2">
			<div class="white-module noPadding fichaHotel">
				<h2 class="offer-rubies"><i class="rubies rubix2 rubiesHL">rubies</i> <?php echo $puntos_hl ?></h2>
				<div class="overlayer absolute">
					<ul class="fichaOfertaUl">
						<li><a href="<?php echo $urlTree['tienda'] ?>/?adre=adq" class="btn btn-hollow btn-lg hotelSeeOffers" data-toggle="tooltip" title="<?php echo $UserPointslang['See all reward offers'] ?>"><i class="fa fa-shopping-cart"></i></a></li>
					</ul>
				</div>
				<img class="hotel-img" src="<?php echo DIR_IMG ?>big-logo.png" alt="<?php echo $oferta['nombre']; ?>"/>
				<h4><strong class="azul">Hotelinking</strong></h4>
			</div>
		</div>
		<?php foreach ($arrayPuntosUsuario as $oferta) { ?>
		<div class="item col-lg-3 col-md-4 col-sm-6 col-xs-12  mb2">
			<div class="white-module noPadding fichaHotel">
				<?php if($oferta['tipo'] == 'cad'){ ?>
				<img class="chain-indicator" src="<?php echo DIR_IMG ?>hotel_chain.png" alt="It´s a chain" width="100" height="100">
				<?php } ?>
				<h2 class="offer-rubies"><i class="rubies rubix2">rubies</i> <?php echo $oferta['puntos']; ?></h2>
				<div class="overlayer absolute">
					<ul class="fichaOfertaUl">
						<li><a href="<?php echo $urlTree['tienda'] ?>/?<?php echo $oferta['tipo']; ?>=<?php echo $oferta['id'];?>" class="btn btn-hollow btn-lg hotelSeeOffers" data-toggle="tooltip" title="<?php echo $UserPointslang['See all reward offers'] ?>"><i class="fa fa-shopping-cart"></i></a></li>
						<li>
							<?php if($oferta['tipo'] == 'hot'){ ?>
							<a href="<?php echo $oferta['urlGuid'] ?>" class="btn btn-hollow btn-lg hotelDetails" data-toggle="tooltip" title="<?php echo $UserPointslang['See details of this hotel'] ?>"><i class="fa fa-eye"></i></a>
							<?php }else{ ?>
							<a href="<?php echo $oferta['urlGuid']?>" class="btn btn-hollow btn-lg hotelDetails" data-toggle="tooltip" title="<?php echo $UserPointslang['See details of this hotel'] ?>"><i class="fa fa-eye"></i></a>
							<?php } ?>
						</li>
						<li><a href="<?php echo $url['dir1'] ?>/?<?php echo ($oferta['follow'] == 0 ? 'fol' : 'unfol' ) ?>=<?php echo $oferta['id']; ?>&tipo=<?php echo $oferta['tipo']; ?>" class="btn btn-hollow btn-lg hotelLikes" data-toggle="tooltip" title="<?php echo $UserPointslang['Like this hotel'] ?>"><i class="fa <?php echo ($oferta['follow'] == 0 ? 'fa-thumbs-o-up' : 'fa-thumbs-o-down' ) ?>"></i></a></li>
					</ul>
				</div>
				<?php if ($oferta['logo'] == '0'){ ?>
				<img class="hotel-img" src="<?php echo DIR_IMG ?>logo.jpg" alt="<?php echo $oferta['nombre']; ?>"/>
				<?php }else{ ?>
				<?php if($oferta['tipo'] == 'hot'){ ?>
				<img class="hotel-img" src="<?php echo $oferta['logo']; ?>" alt="<?php echo $oferta['nombre']; ?>"/>
				<?php }else {?>
				<img class="hotel-img" src="<?php echo $oferta['logo']; ?>" alt="<?php echo $oferta['nombre']; ?>"/>
				<?php } ?>
				<?php } ?>
				<div class="hotel-img-loyalty-holder">
					<img class="loyalty-hotel-img" src="<?php echo DIR_IMG ?>loyalty-card-<?php echo $oferta['nivelFideliz'] ?>.png" alt="fidelizacion">
				</div>
				<ul class="hotel-stars">
					<?php for ($i=0; $i < $oferta['estrellas']; $i++) { ?>
					<li><i class="fa fa-star"></i></li>
					<?php } ?>
				</ul>
				<h4><?php echo $oferta['nombre']; ?></h4>
				<div class="hotel-rating">
					<span class="azul"><h3><i class="fa fa-heart"></i> <?php echo $oferta['rating']; ?></h3></span>
				</div>
				<div class="hotel-media col-lg-12 mt">
					<?php if($oferta['tipo'] == 'hot'){ ?>
					<span class="pull-left hotel-location media-info"><i class="fa fa-map-marker"></i> <?php echo $oferta['city']; ?></span>
					<?php } ?>
					<span class="pull-right hotel-offer-numbers media-info"><i class="fa fa-gift"></i> <?php echo $oferta['nofertas']; ?></span>
				</div>
				<div class="clearfix"></div>
				<div class="progress mt">
					<?php if($oferta['ng'] == 0) {?>
					<span class="pl"><small>No free night set</small></span>
					<?php }else{ ?>
					<?php if ($oferta['puntos'] >= $oferta['ng']) { ?>
					<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="<?php echo $oferta['ng'] ?>" style="width: 100%;">
						<?php echo $UserPointslang['Free Night!'] ?>
					</div>
					<?php }else { ?>
					<?php
					$new_width = ($oferta['puntos'] / $oferta['ng']) * 100;
					$new_width = number_format($new_width, 0);
					?>
					<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $oferta['puntos'] ?>" aria-valuemin="0" aaria-valuemax="<?php echo $oferta['ng'] ?>" style="width: <?php echo $new_width;?>%;">
						<?php echo $new_width;?> %
					</div>
					<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php }else{ ?>
		<div class="row">
			<div class="col-lg-12 no-reward-points mt2">
				<div class="text-center mt2">
					<img src="<?php echo DIR_IMG . 'big-diamond.png' ?>" alt="diamond" >
					<h2><?php echo $UserPointslang['You rewards balance is still empty... But no worries, we will show you how to earn rewards fast'] ?></h2>
					<h4><a href="<?php echo $urlTree['faq'] ?>" title="discover how to earn reward points"><?php echo $UserPointslang['discover how to earn reward points'] ?></a></h4>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<script src="<?php echo DIR_JS . 'isotope.pkgd.min.js'?>"></script>
<script src="<?php echo DIR_JS . 'imagesloaded.pkgd.min.js'?>"></script>
<?php include TEMPLATES . 'give-rewards-friend-modal.php' ?>
<script>
	$(document).ready(function(){

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

		$('.fichaOferta, .fichaHotel').hover(function(){
			$(this).children('.overlayer').fadeToggle('fast');
		});

		$('.give-rewards').click(function(e){
			e.preventDefault();
			$('#giveRewardsFriendModal').modal('show');
		});
	});

	$('#searchUser').click(function(){
		var email = $("#userEmail").val();
		$.ajax({ url: "/lib/webservices/user-points-ws.php",
			data: 'email='+ email +'',
			type: 'POST',
			success: function(output) {
				$('#result').empty();
				$('#result').append(output);
			}
		});
	})
</script>