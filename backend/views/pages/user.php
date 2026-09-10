<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user.php' ?>
	<?php if(isset($_SESSION['h_logueado'])){
		echo '<div id="wrapper">';
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(isset($_SESSION['u_logueado'])){
		include TEMPLATES . 'user-top-bar.php';
	}else if(isset($_SESSION['staff_logueado'])){
		echo '<div id="wrapper">';
		include TEMPLATES . 'check-sidebar.php';
	}else{
		include TEMPLATES . 'user-top-bar.php';
	}?>
	<div id="page-content-wrapper">
		<?php if(isset($_SESSION['h_logueado'])){ ?>
            <div class="top-bar">
                <?php include TEMPLATES . 'user-top-menu.php'; ?>
                <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                     alt="top bar logo">
            </div>
		<?php } ?>
		<div class="utility-bar">
			<div class="col-lg-12">
				<?php if (isset($_SESSION['h_logueado'])) { ?>
					<h1 class="pull-left"><i class="fa fa-user"></i> <?php echo $Userlang['Guest profile'] ?> <strong><?php echo $arrayDatosUsuario['nombre'] ?></strong></h1>
					<?php }else{ ?>
						<h1 class="pull-left"><i class="fa fa-user"></i> <strong><?php echo $arrayDatosUsuario['nombre'] ?></strong></h1>
					<?php } ?>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent mt2" id="fullContainer">
	<div class="col-lg-12">
		<div class="white-module">
			<div class="media">
				<div class="pull-left">
					<?php if(!empty($arrayDatosUsuario['img'])){?>
					<img class="user-img img-circle" src="<?php echo $arrayDatosUsuario['img'] ?>" alt="<?php echo $arrayDatosUsuario['nombre'] ?>" height="110" />
					<?php } else { ?>
					<img class="user-img img-circle" src="<?php echo DIR_IMG . 'avatar.jpg' ?>" alt="User Avatar" height="110" />
					<?php } ?>
				</div>
				<div class="media-body">
					<?php if(!empty($_SESSION['h_logueado']) || !empty ($_SESSION['staff_logueado'])){?>
					<div class="col-sm-2 col-xs-6 text-center">
						<h4><a href="#" class="hotel-comments" data-toggle="tooltip" data-placement="top" title="<?php echo $Userlang['See all guest rating and comments for this guest. Hotels can rate guests and posts comments, however guest cannot view them'] ?>"><i class="fa fa-heart mb fa-2x"></i><br><?php echo $Userlang['Guest rating'] ?></a></h4>
						<p><strong><?php echo $arrayDatosUsuario['rating_usuario'] ?></strong></p>
					</div>
					<?php } ?>
					<div class="col-sm-2 col-xs-6 text-center">
						<h4><i class="fa fa-globe mb fa-2x"></i><br><?php echo $Userlang['Nationality'] ?></h4>
						<p><strong><?php echo $arrayDatosUsuario['pais'] ?></strong></p>
					</div>
					<div class="col-sm-2 col-xs-6 text-center">
						<h4><i class="fa fa-twitter-square mb fa-2x"> </i><br><?php echo $Userlang['Twitter followers'] ?></h4>
						<p><strong><?php echo $arrayDatosUsuario['tw_followers'] ?></strong></p>
					</div>
					<div class="col-sm-2 col-xs-6 text-center">
						<h4><i class="fa fa-facebook-square mb fa-2x"> </i><br><?php echo $Userlang['Facebook followers'] ?></h4>
						<p><strong><?php echo $arrayDatosUsuario['fb_friends'] ?></strong></p>
					</div>
					<div class="col-sm-2 col-xs-6 text-center">
						<h4><i class="fa fa-building-o mb fa-2x"></i><br><?php echo $Userlang['Visited Hotels'] ?></h4>
						<p><strong><?php echo $arrayDatosUsuario['hotels_visited'] ?></strong></p>
					</div>
					<div class="col-sm-2 col-xs-6 text-center">
						<h4><i class="fa fa-check-square-o mb fa-2x"></i><br><?php echo $Userlang['Hotel Reviews'] ?></h4>
						<p><strong><?php echo $arrayDatosUsuario['reviews'] ?></strong></p>
					</div>
					<div class="col-sm-2 col-xs-6 text-center">
						<h4><i class="fa fa-moon-o mb fa-2x"></i><br><?php echo $Userlang['Room Nights'] ?></h4>
						<p><strong><?php echo $arrayDatosUsuario['nights'] ?></strong></p>
					</div>
				</div>
			</div>
		</div>
		<?php if(!empty($_SESSION['h_logueado']) || !empty ($_SESSION['staff_logueado'])){?>
		<div class="white-module mt customerData">
		<div class="col-lg-12">
				<h3 class="pull-left"><?php echo $Userlang['Guest data at your hotel'] ?></h3>
		</div>
			<div class="center-block">
				<div class="col-sm-3 col-xs-6 text-center">
					<h4><i class="rubies rubix3 mb">rubies</i><br><?php echo $Userlang['Rubies'] ?></h4>
					<p><strong><?php echo $arrayDatosUsuarioHotel['puntos'] ?></strong></p>
				</div>
				<div class="col-sm-3 col-xs-6 text-center">
					<h4><i class="fa fa-heart mb fa-2x"></i><br><?php echo $Userlang['Guest satisfaction'] ?></h4>
					<p><strong><?php echo $arrayDatosUsuarioHotel['guest_rates_you'] ?></strong></p>
				</div>
				<div class="col-sm-3 col-xs-6 text-center">
					<h4><i class="fa fa-money mb fa-2x"></i><br><?php echo $Userlang['Spend (USD)'] ?></h4>
					<p><strong><?php echo $arrayDatosUsuarioHotel['total_spent'] ?></strong></p>
				</div>
				<div class="col-sm-3 col-xs-6 text-center">
					<h4><i class="fa fa-moon-o mb fa-2x"></i><br><?php echo $Userlang['Total nights'] ?></h4>
					<p><strong><?php echo $arrayDatosUsuarioHotel['nights_hotel'] ?></strong></p>
				</div>
				<div class="col-sm-3 col-xs-6 text-center">
					<h4><i class="fa fa-shopping-cart mb fa-2x"></i><br><?php echo $Userlang['Redeems'] ?></h4>
					<p><strong><?php echo $arrayDatosUsuarioHotel['redeems'] ?></strong></p>
				</div>
				<div class="col-sm-3 col-xs-6 text-center">
					<h4><i class="fa fa-share-square-o mb fa-2x"></i><br><?php echo $Userlang['Social media shares'] ?></h4>
					<p><strong><?php echo $arrayDatosUsuarioHotel['totalShares'] ?></strong></p>
				</div>
				<div class="col-sm-3 col-xs-6 text-center">
					<h4><i class="fa fa-plus-circle mb fa-2x"></i><br><?php echo $Userlang['Referrals'] ?></h4>
					<p><strong><a href="<?php echo $urlTree['referrers'] ?>/?us=<?php echo $id_usuario ?>&filter=trfr" ><?php echo $arrayDatosUsuarioHotel['totalReferrals'] ?></a></strong></p>
				</div>
				<div class="col-sm-3 col-xs-6 text-center">
					<h4><i class="fa fa-money mb fa-2x"></i><br><?php echo $Userlang['Referrals spend'] ?></h4>
					<p><strong><?php echo $arrayDatosUsuarioHotel['referralsSpent'] ?></strong></p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php if (empty($arrayBookingHistory)){?>
		<?php } else { ?>
			<h2 class="text-center mt2"><i class="fa fa-building-o"></i> <?php echo $Userlang['Booking history'] ?></h2>
		<?php foreach ($arrayBookingHistory as $abh) { ?>
		<div class="white-module mt bookingHistoryPanel mb mt2">
			<div class="table-responsive">
				<table class="table table-striped">
					<tr class="table-header">
						<td>
							<span class="pull-left"><?php echo $Userlang['Check-in date'] ?></span>
						</td>
						<td>
							<span class="pull-left"><?php echo $Userlang['Check-out date'] ?></span>
						</td>
						<td>
							<span class="pull-left"><?php echo $Userlang['Room Nights'] ?></span>
						</td>
						<td>
							<span class="pull-left"><?php echo $Userlang['Spend 2'] ?></span>
						</td>
						<td>
							<span class="pull-left"><?php echo $Userlang['Hotel Rating'] ?></span>
						</td>
						<td>
							<span class="pull-left"><?php echo $Userlang['Guest Rating'] ?></span>
						</td>
					</tr>
					<tr class="table-row">
						<td>
							<?php echo $abh['chkin_date'] ?>
						</td>
						<td>
							<?php echo $abh['chkout_date'] ?>
						</td>
						<td>
							<?php echo $abh['noches'] ?>
						</td>
						<td>
							<?php echo $abh['money'] ?>
						</td>
						<td>
							<?php echo ($abh['user_rate'] == 0 ? '-' : $abh['user_rate'] ) ?>
						</td>
						<td>
							<?php echo ($abh['hotel_rate'] == 0 ? '-' : $abh['hotel_rate'] ) ?>
						</td>
					</tr>
				</table>
			</div>
			<h3><?php echo $Userlang['Guest Satisfaction Survey'] ?></h3>
			<div class="row mb">
				<div class="col-lg-6">
					<h4><i class="fa fa-thumbs-o-up verde"></i> <span class="verde"><?php echo $Userlang['Positive Comments'] ?></span></h4>
					<p><?php echo $abh['positiveComment'] ?></p>
				</div>
				<div class="col-lg-6">
					<h4><i class="fa fa-thumbs-o-down naranja"> </i> <span class="naranja"><?php echo $Userlang['Negative Comments'] ?></span></h4>
					<p><?php echo $abh['negativeComment'] ?></p>
				</div>
			</div>
			<?php if (!empty($abh['redeems'])){ ?>
			<h3><?php echo $Userlang['Vouchers redeemed during the stay'] ?></h3>
			<div class="table-responsive mt relative">
				<table class="table table-striped">
					<tr class="table-header">
						<td>
							<span class="pull-left"><?php echo $Userlang['Campaign'] ?></span> <a href="#" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $Userlang['Redeemed on'] ?></span><a href="#" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $Userlang['Voucher ID'] ?></span> <a href="#" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $Userlang['Reward Points'] ?></span></a>
						</td>
						<td>
							<span class="pull-right"><?php echo $Userlang['Actions'] ?></span></a>
						</td>
					</tr>
					<?php foreach ($abh['redeems'] as $oferta) { ?>
					<tr class="table-row">
						<td>
							<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $oferta['nombre_oferta_san'] ?>/<?php echo $oferta['id'] ?>" title="<?php echo $oferta['nombre_oferta'] ?>"><?php echo $oferta['nombre_oferta'] ?></a>
						</td>
						<td>
							<?php echo $oferta['fecha_canj'] ?>
						</td>
						<td>
							<?php echo $oferta['voucher'] ?>
						</td>
						<td>
							<i class="rubies rubix1">rubies</i> <?php echo $oferta['puntos'] ?>
						</td>
						<td>
							<div class="btn-group pull-right">
								<a href="<?php echo $urlTree['hotel-detalle-oferta'] ?>/?id=<?php echo $oferta['id'] ?>" class="btn btn-default" title="<?php echo $Userlang['View campaign details'] ?>"><i class="fa fa-eye"></i></a>
							</div>
						</td>
					</tr>
					<?php } ?>
				</table>

			</div>
			<?php } ?>
		</div>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</div>
		</div>
	</div>
</div>
<?php include TEMPLATES . 'hotel-comments-modal.php' ?>
<script src="<?php echo DIR_JS . 'charts.js'?>"></script>
<script>
	$(document).ready(function(){
		$('.hotel-comments').click(function(e){
			e.preventDefault();
			$('#hotel-comments-modal').modal('show');
		});
		$('.hotel-comments').tooltip({
			container: 'body'
		});
		$('#checkInUser').datepicker({
			dateFormat: "dd-mm-yy"
		});
	});
</script>
