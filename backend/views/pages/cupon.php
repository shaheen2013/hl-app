<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/cupon.php' ?>
<?php if(isset($_SESSION['h_logueado'])){
	echo '<div id="wrapper">';
	include TEMPLATES . 'hotel-sidebar.php';
}else if(isset($_SESSION['u_logueado'])){
	include TEMPLATES . 'user-top-bar.php';
	echo '<div class="user-utility-bar mb15 mt2">';
	include TEMPLATES. 'voucher-menu.php';
	echo '</div>';
}?>
<div id="page-content-wrapper">
	<div class="container" id="fullContainer">
		<form action="<?php echo $url['dir1'] . "/" . $arrayDatosOferta['nombre_san'] . "/" . $arrayDatosOferta['id']?>/">
			<div class="input-group mb2">
				<span class="input-group-addon"><i class="fa fa-search"></i></span>
				<input type="text" class="form-control input-lg" name="search" id="cuponMainSearch" placeholder="<?php echo $CuponLang['Search by voucher ID or guest name box'] ?>">
			</div>
		</form>
		<?php if(!empty($arrayCuponesOferta)) { ?>
		<div class="table-responsive mt relative">
			<table class="table table-striped">
				<tr class="table-header">
					<td>
						<span class="pull-left"><?php echo $CuponLang['Adquisition date'] ?></span> <a href="#" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
					</td>
					<td>
						<span class="pull-left"><?php echo $CuponLang['Status'] ?></span> <a href="#" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
					</td>
					<td>
						<span class="pull-left"><?php echo $CuponLang['Days left'] ?></span> <a href="#" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
					</td>
					<td>
						<span class="pull-left"><?php echo $CuponLang['Actions'] ?></span>
					</td>
				</tr>
				<?php foreach ($arrayCuponesOferta as $cupon) { ?>
				<tr class="table-row">
					<td>
						<?php echo $cupon['fecha'] ?>
					</td>
					<td>
						<?php if (!empty($cupon['fecha_canj'])){
							echo 'Redeemed on ' . $cupon['fecha_canj'];
						}else{
							echo '<span class="verde">'.$CuponLang['Not redeemed'].'</span>';
						}
						?>
					</td>
					<td>
						<?php
						if ($arrayDatosOferta['days_left'] > 0){
							echo $CuponLang['Quedan'] .' '. $arrayDatosOferta['days_left'] . ' '. $CuponLang['days'];
						}else if($arrayDatosOferta['days_left'] == '-'){
							echo '<span class="verde">'.$CuponLang['Esta oferta no caduca'].'</span>';
						}else{
							echo '<span class="naranja">'.$CuponLang['Esta oferta ha caducado'].'</span>';
						}
						?>
					</td>
					<td>
						<div class="btn-group">
							<?php if (empty($cupon['fecha_canj'])){ ?>
                            	<?php if ($cupon['id_tipo_share'] != '2'){ ?>
							<a href="<?php echo $cupon['redeemOfferUrl'] ?>" class="btn btn-success" title="Redeem at hotel">Redeem code</a>
                            	<?php }else{?>
                                	<strong><?php echo $cupon['voucher'] ;?></strong>
                                <?php }?>
							<?php }else{
								echo 'no actions available';
							};
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<?php } ?>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-8 ofertaDetalle col-md-7">
				<?php if ($arrayDatosOferta['descuento'] > 0) { ?>
				<div class="offerBorder">
					<h2 class="blanco"><strong><?php echo $arrayDatosOferta['descuento']; ?></strong> %</h2>
				</div>
				<?php } ?>
				<div class="imgOferta">
					<img class="imgPublicOferta" src="<?php echo($arrayDatosOferta['img'] != '' ? DIR_IMG_OFERTAS . $arrayDatosOferta['id'] .'/'. $arrayDatosOferta['img'] : DIR_IMG . 'placeholder.png') ?>" alt="<?php echo $arrayDatosOferta['nombre']; ?>">
					<div class="nameOferta">
						<h1><?php echo $arrayDatosOferta['nombre']; ?></h1>
						<h4> <?php echo $CuponLang['oferta válida desde el'] ?> <?php echo $arrayDatosOferta['inicio']; ?> <?php if( $arrayDatosOferta['fin'] != '00-00-0000'){?> <?php echo $CuponLang['hasta el'] ?> <?php echo $arrayDatosOferta['fin']; ?><?php } ?></h4>
					</div>
				</div>
				<div class="white-module p2 mt2">
					<h3 class="mt"><?php echo $CuponLang['Descripción de la oferta'] ?></h3>
					<section>
						<?php echo $arrayDatosOferta['descripcion']; ?>
					</section>

				</div>
				<div class="white-module p2 mt2 mb3">
					<h3 class="mt"><?php echo $CuponLang['Condiciones de la oferta'] ?></h3>
					<section>
						<?php echo $arrayDatosOferta['condiciones']; ?>
					</section>

				</div>
			</div>
			<div class="col-lg-4 col-md-5">
				<div class="offer-sidebar">
					<div class="white-module mb hotelOfferDetails">
						<div class="media">
							<?php if($ofertaDe['tipo']=='hot'){//oferta de hotel?>
							<a class="pull-left" href="#">
								<?php if (empty($arrayDatosOferta['logo'])){ ?>
								<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG; ?>logo.jpg" alt="add your logo here" width="80" height="80">
								<?php } else { ?>
								<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG_FICHA_HOTEL; ?><?php echo $arrayDatosOferta['id_hotel']; ?>/logo/<?php echo $arrayDatosOferta['logo'] ?>" alt="add your logo here" width="80" height="80">
								<?php } ?>
							</a>
							<div class="media-body">                                    
								<h4 class="media-heading"><strong><a href="<?php echo $urlGUID ?>" title="hotel"><?php echo $arrayDatosOferta['hotelName']; ?></a></strong></h4>
								<ul>
									<?php for ($i=0; $i < $arrayDatosOferta['estrellas']; $i++) { ?>
									<li><i class="fa fa-star"></i></li>
									<?php } ?>
									<li class="pl"><i class="fa fa-heart"> <?php echo $arrayDatosOferta['rating']; ?></i></li>
									<li class="pl"><i class="fa fa-check-square-o"></i> <?php echo $arrayDatosOferta['totalRatings']; ?></li>
								</ul>
								<strong><?php echo $CuponLang['Price Range:'] ?></strong><h3><?php echo $arrayDatosOferta['min_rango']; ?> <i class="fa fa-arrows-h"></i> <?php echo $arrayDatosOferta['max_rango']; ?> €</h3>
								<a href="#" class="mb2 dblock hotelDetailsToggle mt" title="see details"><?php echo $CuponLang['show all info'] ?> <i class="fa fa-chevron-down"> </i></a>
								<address class="hotelLoc dnone">
									<?php echo $arrayDatosOferta['street']; ?><br>
									<?php echo $arrayDatosOferta['ciudad']; ?><br>
									<div class="mb"></div>
									<strong>T:</strong> <?php echo $arrayDatosOferta['telefonoReservas']; ?><br>
									<strong>M:</strong> <a href="mailto:<?php echo $arrayDatosOferta['emailReserva']; ?>" title="mail"><?php echo $arrayDatosOferta['emailReserva']; ?></a><br>
									<strong>W:</strong> <a href="<?php echo $websiteReservaUrl ?>" title="web"><?php echo $websiteReservaUrl ?></a><br>
								</address>
								<?php }else{ //oferta de cadena?>
								<a class="pull-left" href="#">
									<?php if (empty($arrayDatosOferta['cadena_logo'])){ ?>
									<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG; ?>logo.jpg" alt="add your logo here" width="80" height="80">
									<?php } else { ?>
									<img class="img-circle img-thumbnail" src="<?php echo $arrayDatosOferta['cadena_logo'] ?>" alt="add your logo here" width="80" height="80">
									<?php } ?>
								</a>
								<div class="media-body">
									<h4 class="media-heading"><strong><a href="<?php echo $urlGUID ?>" title="hotel"><?php echo $arrayDatosOferta['cadena_nombre']; ?></a></strong></h4>
								</div>
								<strong>M:</strong> <a href="mailto:<?php echo $arrayDatosOferta['cadena_email']; ?>" title="mail"><?php echo $arrayDatosOferta['cadena_email']; ?></a>
								<?php } ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<?php include TEMPLATES	.'modalCheckOffer.php'; ?>
<?php include TEMPLATES . 'issuesContactModal.php';?>
<?php include TEMPLATES . 'confirm-gift-offer-for-friend-modal.php' ?>
<?php include TEMPLATES . 'confirm-buy-offer-modal.php';?>
<script src="<?php echo DIR_JS . 'oferta.min.js'?>"></script>
<script>
	$(document).ready(function(){
		$('.offerIssueBtn').click(function(e){
			e.preventDefault();
			$('#issuesContactModal').modal('show');
		});
		$('.buyOfferForFriendBtn').click(function(){
			var cuponId = $(this).data('id');
			$('#cupon-id').val(cuponId);
			$('#confirmGiftOfferFriendModal').modal('show');
		})
		$('.buyOfferBtn').click(function(e){
			e.preventDefault();
			$('#confirmBuyOfferModal').modal('show');
		})
	});
</script>
