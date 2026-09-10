<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/oferta.php' ?>
<?php if(isset($_SESSION['h_logueado'])){
	echo '<div id="wrapper">';
	include TEMPLATES . 'hotel-sidebar.php';
}else if(isset($_SESSION['u_logueado'])){
	include TEMPLATES . 'user-top-bar.php';
}else if(isset($_SESSION['staff_logueado'])){
	echo '<div id="wrapper">';
	include TEMPLATES . 'check-sidebar.php';

}?>
<div id="page-content-wrapper">
	<?php if(isset($_SESSION['h_logueado'])){ ?>
        <div class="top-bar">
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
	<?php } ?>
	<div class="mainContent mt2" id="fullContainer">
		<div class="container">
			<div class="col-lg-12 mb2">
				<?php include TEMPLATES . 'offer-menu.php'; ?>
			</div>
			<div class="col-lg-8 ofertaDetalle col-md-7">
				<?php if ($arrayDatosOferta['descuento'] > 0) { ?>
				<div class="offerBorder">
					<h2 class="blanco"><strong><?php echo $arrayDatosOferta['descuento']; ?></strong> %</h2>
				</div>
				<?php } ?>
				<div class="imgOferta">
					<img class="imgPublicOferta" src="<?php echo DIR_IMG_OFERTAS . $arrayDatosOferta['id']; ?>/<?php echo $arrayDatosOferta['img']; ?>" alt="<?php echo $arrayDatosOferta['hotelName']; ?>">
					<div class="nameOferta">
						<h1><?php echo $arrayDatosOferta['nombre_oferta']; ?></h1>
						<h4> <?php echo $OfertaLang['oferta valida desde el'] ?> <?php echo $arrayDatosOferta['inicio']; ?> <?php if( $arrayDatosOferta['fin'] != '00-00-0000'){?> <?php echo $OfertaLang['hasta el'] ?> <?php echo $arrayDatosOferta['fin']; ?><?php } ?></h4>
					</div>
				</div>
				<div class="white-module p2 mt2">
					<h3 class="mt"><?php echo $OfertaLang['Descripción de la oferta'] ?></h3>
					<section>
						<?php echo $arrayDatosOferta['descripcion']; ?>
					</section>

				</div>
				<div class="white-module p2 mt2 mb2">
					<h3 class="mt"><?php echo $OfertaLang['Condiciones de la oferta'] ?></h3>
					<section>
						<?php echo $arrayDatosOferta['condiciones']; ?>
					</section>

				</div>
			</div>
			<div class="col-lg-4 col-md-5">
				<div class="offer-sidebar">
					<?php if($arrayDatosOferta['adq_ret'] == 'ref') { ?>
					<div class="white-module text-center overflowHidden offerCostModule" <?php echo(!empty($_SESSION['h_logueado']) ? 'title="'.$OfertaLang['Hoteliers cant adquire offers'].'" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.$OfertaLang['Hoteliers are not elegible for adquiring offers. This offers are for individuals only'].'"' : '') ?>>
						<h3><?php echo $OfertaLang['This is a referral offer'] ?></h3>
						<p><?php echo $OfertaLang['not elegible to redeem with reward points'] ?></p>
					</div>
					<?php }else{ ?>
					<div class="white-module text-center overflowHidden offerCostModule" <?php echo(!empty($_SESSION['h_logueado']) ? 'title="'.$OfertaLang['Hoteliers cant adquire offers'].'" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.$OfertaLang['Hoteliers are not elegible for adquiring offers. This offers are for individuals only'].'"' : '') ?>>
						<h3><?php echo $OfertaLang['Coste de la oferta'] ?></h3>
						<h2 class="offerDetailsCost"><strong><?php echo $arrayDatosOferta['puntos']; ?></strong> <?php echo($arrayDatosOferta['adq_ret'] == 'adq' ? '<i class="rubies rubix2 rubiesHL">rubies</i>' : '<i class="rubies rubix2">rubies</i>')?></h2>
						<?php if ($checks[0] === true) { ?>
						<a href="#" class="btn btn-lg btn-success mt mb buyOfferBtn"><?php echo $OfertaLang['Canjear esta oferta'] ?></a>
						<?php }else{ ?>
						<a href="#" class="btn btn-lg btn-warning mt mb disabled"><?php echo $OfertaLang['You can´t adquire this offer'] ?></a>
						<?php } ?>
						<?php if (isset ($arrayDatosOferta['quedan']) || $arrayDatosOferta['cupo'] == 0) { ?>
						<?php if($arrayDatosOferta['cupo'] == 0){?>
						<p><?php echo $OfertaLang['Plazas ilimitadas'] ?></p>
						<?php }else{ ?>
						<p><?php echo $OfertaLang['quedan'] ?> <strong><?php echo $arrayDatosOferta['quedan']; ?></strong> <?php echo $OfertaLang['plazas'] ?></p>
						<?php } ?>
						<?php }else{ ?>
						<p><?php echo $arrayDatosOferta['No quedan plazas para esta oferta']; ?></p>
						<?php } ?>
						<div class="row">
							<small><?php echo $OfertaLang['You have']; ?> <?php echo $puntosUsuarioOferta['puntos'] ?> <i class="rubies rubix1 <?php echo ($puntosUsuarioOferta['tipo'] == 'adq' ? 'rubiesHL' : '') ?>">rubies</i> <?php echo ($puntosUsuarioOferta['tipo'] == 'ret' ? $OfertaLang['in this hotel'] : '') ?></small>
						</div>
					</div>
					<?php } ?>
					<?php if ($arrayDatosOferta['requerimientos'] > 0) {?>
					<div class="alert alert-info offerSidebarAlert dblock"><i class="fa fa-exclamation-circle"></i> <?php echo $OfertaLang['Esta oferta requiere estar en hotel alojado un mínimo de'] ?> <strong><?php echo $arrayDatosOferta['requerimientos']; ?> <?php echo $OfertaLang['noches'] ?></strong>
					</div>
					<?php } ?>
					<div class="white-module mb hotelOfferDetails mt">
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
                                    <h4 class="media-heading"><strong><a href="<?php echo $urlGUID; ?>" title="hotel"><?php echo $arrayDatosOferta['hotelName']; ?></a></strong></h4>
                                    <ul>
                                        <?php for ($i=0; $i < $arrayDatosOferta['estrellas']; $i++) { ?>
                                        <li><i class="fa fa-star"></i></li>
                                        <?php } ?>
                                        <li class="pl"><i class="fa fa-heart"> <?php echo $arrayDatosOferta['rating']; ?></i></li>
                                        <li class="pl"><i class="fa fa-check-square-o"></i> <?php echo $arrayDatosOferta['totalRatings']; ?></li>
                                    </ul>
                                    <strong><?php echo $OfertaLang['Price Range:']; ?></strong><h3><?php echo $arrayDatosOferta['min_rango']; ?> <i class="fa fa-arrows-h"></i> <?php echo $arrayDatosOferta['max_rango']; ?> €</h3>
                                </div>
                                <a href="#" class="mb2 dblock hotelDetailsToggle mt" title="see details"><?php echo $OfertaLang['show all info']; ?> <i class="fa fa-chevron-down"> </i></a>
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
									<?php if (empty($arrayDatosOferta['logo'])){ ?>
                                    <img class="img-circle img-thumbnail" src="<?php echo DIR_IMG; ?>logo.jpg" alt="add your logo here" width="80" height="80">
                                    <?php } else { ?>
                                    <img class="img-circle img-thumbnail" src="<?php $arrayDatosOferta['logo'] ?>" alt="add your logo here" width="80" height="80">
                                    <?php } ?>
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><strong><a href="<?php echo $urlGUID; ?>" title="hotel"><?php echo $arrayDatosOferta['hotelName']; ?></a></strong></h4>
                                </div>
                                <strong>M:</strong> <a href="mailto:<?php echo $arrayDatosOferta['email']; ?>" title="mail"><?php echo $arrayDatosOferta['email']; ?></a>
                            <?php } ?>
                            
						</div>
					</div>
					<div class="btn-group-vertical dblock mt2 offerOptions mb2 ">
						<a href="#" class="btn btn-default btn-lg buyOfferForFriendBtn"><i class="fa fa-gift pr"></i> <?php echo $OfertaLang['Make a gift to a friend']; ?></a>
						<?php if(!empty($arrayFolWlst)) {
							if($arrayFolWlst['follow']){ ?>
							<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $arrayDatosOferta['hotelName_san']?>/<?php echo $arrayDatosOferta['id']?>/?unfol=<?php echo $ofertaDe['id']?>&tipo=<?php echo $ofertaDe['tipo']?>" class="btn btn-default btn-lg"><i class="fa fa-thumbs-o-down pr"></i> <?php echo $OfertaLang['Stop following this hotel']; ?></a>
							<?php }else{ ?>
							<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $arrayDatosOferta['hotelName_san']?>/<?php echo $arrayDatosOferta['id']?>/?fol=<?php echo $ofertaDe['id']?>&tipo=<?php echo $ofertaDe['tipo']?>" class="btn btn-default btn-lg"><i class="fa fa-thumbs-o-up pr"></i> <?php echo $OfertaLang['Follow this hotel']; ?></a>
							<?php } ?>
							<?php }else{ ?>
							<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $arrayDatosOferta['hotelName_san']?>/<?php echo $arrayDatosOferta['id']?>/?fol=<?php echo $arrayDatosOferta['hotel_id']?>" class="btn btn-default btn-lg"><i class="fa fa-thumbs-o-up pr"></i> <?php echo $OfertaLang['Follow this hotel']; ?></a>
							<?php } ?>
							<?php if(!empty($arrayFolWlst)) {
								if($arrayFolWlst['wishlist']){ ?>
								<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $arrayDatosOferta['hotelName_san']?>/<?php echo $arrayDatosOferta['id']?>/?unwlst=<?php echo $arrayDatosOferta['id']?>" class="btn btn-default btn-lg"><i class="fa fa-trash-o pr"></i> <?php echo $OfertaLang['Remove from wishlist']; ?></a>
								<?php }else{ ?>
								<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $arrayDatosOferta['hotelName_san']?>/<?php echo $arrayDatosOferta['id']?>/?wlst=<?php echo $arrayDatosOferta['id']?>" class="btn btn-default btn-lg"><i class="fa fa-list-alt pr"></i> <?php echo $OfertaLang['Add to wishlist']; ?></a>
								<?php } ?>
								<?php }else{ ?>
								<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $arrayDatosOferta['hotelName_san']?>/<?php echo $arrayDatosOferta['id']?>/?wlst=<?php echo $arrayDatosOferta['id']?>" class="btn btn-default btn-lg"><i class="fa fa-list-alt pr"></i> <?php echo $OfertaLang['Add to wishlist']; ?></a>
								<?php } ?>
								<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $arrayDatosOferta['hotelName_san']?>/<?php echo $arrayDatosOferta['id']?>/?shr=<?php echo $arrayDatosOferta['id']?>" class="btn btn-default btn-lg"><i class="fa fa-share-square-o pr"></i> <?php echo $OfertaLang['Share this offer']; ?></a>
								<a href="#" class="btn btn-default btn-lg offerIssueBtn"><i class="fa fa-exclamation-triangle pr"></i> <?php echo $OfertaLang['¿Any issue with this offer?']; ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include TEMPLATES . 'modalCheckOffer.php'; ?>
<?php include TEMPLATES . 'issuesContactModal.php';?>
<?php include TEMPLATES . 'confirm-buy-offer-modal.php';?>
<?php include TEMPLATES . 'confirm-buy-offer-for-friend-modal.php' ?>
<script src="<?php echo DIR_JS . 'oferta.min.js'?>"></script>
<script>
	$(document).ready(function(){
		$('.offerIssueBtn').click(function(e){
			e.preventDefault();
			$('#issuesContactModal').modal('show');
		});
		<?php if(!empty($checks) && $checks[0] === false){?>
			$('#modalCheckOffer').modal('show');
			<?php } ?>
			$('.buyOfferBtn').click(function(e){
				e.preventDefault();
				$('#confirmBuyOfferModal').modal('show');
			})
			$('.buyOfferForFriendBtn').click(function(e){
				e.preventDefault();
				$('#confirmBuyOfferFriendModal').modal('show');
			})
		});
	</script>
