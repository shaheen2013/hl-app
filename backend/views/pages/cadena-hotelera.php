<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/cadena-hotelera.php' ?>
<?php if(isset($_SESSION['h_logueado'])){
	echo '<div id="wrapper">';
	include TEMPLATES . 'hotel-sidebar.php';
}else if(isset($_SESSION['u_logueado'])){
	include TEMPLATES . 'user-top-bar.php';
	echo '<div class="row"><div class="user-utility-bar"><div class="pull-right">';
	echo '</div></div></div>';
}else if(isset($_SESSION['staff_logueado'])){
	echo '<div id="wrapper">';
	include TEMPLATES . 'check-sidebar.php';
}else{
	include TEMPLATES . 'user-top-bar.php';
	echo '<div class="row"><div class="user-utility-bar"><div class="pull-right">';
	include TEMPLATES . 'offer-menu.php';
	echo '</div></div></div>';
}?>
<div id="page-content-wrapper">
	<?php if(isset($_SESSION['h_logueado'])){ ?>
        <div class="top-bar">
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
	<?php } ?>
	<div class="mainContent">
		<div class="container mt2">
			<div class="media">
				<?php if(!empty ($arrayDatosHotel['logo'])){?>
				<a title="perfil" class="pull-left"><img width="80" height="80" alt="<?php echo $arrayDatosHotel['hotelName'] ?>" src="<?php echo DIR_IMG_FICHA_HOTEL . $arrayDatosHotel['id']?>/logo/<?php echo $arrayDatosHotel['logo'] ?>" class="img-circle img-thumbnail"></a>
				<?php }else{ ?>
				<a title="perfil" href="<?php echo $urlTree['hotel-profile'] ?>" class="pull-left"><img width="80" height="80" alt="add your logo here" src="<?php echo DIR_IMG.'cadenas-ficha/' . $datosCadena['id'] .'/logo/'. $datosCadena['logo'] ?>" class="img-circle img-thumbnail"></a>
				<?php } ?>
				<div class="media-body hotelPageDetails">
					<h1 class="media-heading pull-left"><?php echo $datosCadena['nombre'] ?></h1>
					<div class="clearfix"></div>
						<h4><?php echo $CadenaHoteleraLang['Contact info:'] ?><strong> <?php echo $datosCadena['telefono_contacto'] ?> - <a href="mailto:<?php echo $datosCadena['email_contacto'] ?>" title="<?php echo $datosCadena['nombre'] ?>"><?php echo $datosCadena['email_contacto'] ?></a></strong></h4>
						<h4 class="offer-rubies"><i class="rubies rubix2">rubies</i> <?php echo $datosCadena['puntos']; ?></h4>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="white-module hotelDescription mt">
						<h3><?php echo $CadenaHoteleraLang['Chain Description'] ?></h3>
						<p>
							<?php echo $datosCadena['descripcion'] ?>
						</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 mt mb">
					<h3><?php echo $CadenaHoteleraLang['Hotels of'] ?> <?php echo $datosCadena['nombre'] ?></h3>
				</div>
			</div>
			<div class="row" id="fullContainer">
				<?php foreach ($hotelesCadena as $oferta) { ?>
				<div class="item col-lg-3 col-md-4 col-sm-6 col-xs-12  mb2">
					<div class="white-module noPadding fichaHotel">
						<div class="overlayer absolute">
							<ul class="fichaOfertaUl">
								<li><a href="<?php echo $urlTree['tienda'] ?>/?hot=<?php echo $oferta['id'];?>" class="btn btn-hollow btn-lg hotelSeeOffers" data-toggle="tooltip" title="See all offers from this hotel"><i class="fa fa-shopping-cart"></i></a></li>
								<li><a href="<?php echo $oferta['urlGuid'] ?>" class="btn btn-hollow btn-lg hotelDetails" data-toggle="tooltip" title="See details of this hotel"><i class="fa fa-eye"></i></a></li>
								<li><a href="<?php echo $url['dir1'] .'/'. $datosCadena['nombre_san'] .'/'. $datosCadena['id'] ?>/?<?php echo ($oferta['follow'] == 0 ? 'fol' : 'unfol' ) ?>=<?php echo $oferta['id']; ?>" class="btn btn-hollow btn-lg hotelLikes" data-toggle="tooltip" title="Like this hotel"><i class="fa <?php echo ($oferta['follow'] == 0 ? 'fa-thumbs-o-up' : 'fa-thumbs-o-down' ) ?>"></i></a></li>
							</ul>
						</div>
						<?php if ($oferta['logo'] == '0'){ ?>
						<img class="hotel-img" src="<?php echo DIR_IMG ?>logo.jpg" alt="<?php echo $oferta['nombre']; ?>"/>
						<?php }else{ ?>
						<img class="hotel-img" src="<?php echo DIR_IMG_FICHA_HOTEL ?><?php echo $oferta['id']; ?>/logo/<?php echo $oferta['logo']; ?>" alt="<?php echo $oferta['nombre']; ?>"/>
						<?php } ?>
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
							<span class="pull-left hotel-location media-info"><i class="fa fa-map-marker"></i> <?php echo $oferta['city']; ?></span>
							<span class="pull-right hotel-offer-numbers media-info"><i class="fa fa-gift"></i> <?php echo $oferta['nofertas']; ?></span>
						</div>
						<div class="clearfix"></div>
						<div class="progress mt">
							<?php if($oferta['ng'] == 0) {?>
							<span class="pl"><small><?php echo $CadenaHoteleraLang['No free night set'] ?></small></span>
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
			</div>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'isotope.pkgd.min.js'?>"></script>
<script src="<?php echo DIR_JS . 'imagesloaded.pkgd.min.js'?>"></script>
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
	})
</script>
