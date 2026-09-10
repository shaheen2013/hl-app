<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-preferences.php' ?>
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
			<h1 class="pull-left"><i class="fa fa-thumbs-o-up"></i> <?php echo $userPreferencesLang['User preferences'] ?> <strong><?php echo $datosUsuario['nombre'] ?></strong></h1>
			<?php }else{ ?>
			<h1 class="pull-left"><i class="fa fa-user"></i> <strong><?php echo $datosUsuario['nombre'] ?></strong></h1>
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
			<?php if(!empty($_SESSION['h_logueado']) || !empty ($_SESSION['staff_logueado'])){?>
			<div class="white-module">
				<div class="media">
					<div class="pull-left">
						<?php if(!empty($datosUsuario['img'])){?>
						<img class="user-img img-circle" src="<?php echo $datosUsuario['img'] ?>" alt="<?php echo $datosUsuario['nombre'] ?>" height="110" />
						<?php } else { ?>
						<img class="user-img img-circle" src="<?php echo DIR_IMG . 'avatar.jpg' ?>" alt="User Avatar" height="110" />
						<?php } ?>
					</div>
					<div class="media-body">
						<div class="col-sm-3 col-xs-6 text-center">
							<h4><i class="fa fa-star"></i><br><?php echo $userPreferencesLang['Minimum category'] ?></h4>
							<p><strong><?php echo (!empty($preferenciasHotelUsuario['minEstrellas']) ? $preferenciasHotelUsuario['minEstrellas'] : $userPreferencesLang['not set']) ?></strong></p>
						</div>
						<div class="col-sm-3 col-xs-6 text-center">
							<h4><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><br><?php echo $userPreferencesLang['Maximum category'] ?></h4>
							<p><strong><?php echo (!empty($preferenciasHotelUsuario['maxEstrellas']) ? $preferenciasHotelUsuario['maxEstrellas'] : $userPreferencesLang['not set']) ?></strong></p>
						</div>
						<div class="col-sm-3 col-xs-6 text-center">
							<h4><i class="fa fa-money"></i><br><?php echo $userPreferencesLang['Money range min'] ?></h4>
							<p><strong><?php echo (!empty($preferenciasHotelUsuario['rango_inf']) ? $preferenciasHotelUsuario['rango_inf']. ' $' : $userPreferencesLang['not set']) ?></strong></p>
						</div>
						<div class="col-sm-3 col-xs-6 text-center">
							<h4><i class="fa fa-money"></i><i class="fa fa-money"></i><i class="fa fa-money"></i><br><?php echo $userPreferencesLang['Money range max'] ?></h4>
							<p><strong><?php echo (!empty($preferenciasHotelUsuario['rango_sup']) ? $preferenciasHotelUsuario['rango_sup']. ' $' : $userPreferencesLang['not set']) ?></strong></p>
						</div>
					</div>
				</div>
			</div>
			<?php  }  ?>

			<?php if(!empty($_SESSION['h_logueado']) || !empty ($_SESSION['staff_logueado'])){?>
			<div class="white-module mt customerData">
				<div class="col-lg-12">
					<div class="row">
					<h3 class="pull-left"><?php echo $userPreferencesLang['Wich hotels this user likes most'] ?></h3>
					</div>
				</div>
				<div class="center-block ">
					<?php
					foreach ($tiposHotel as $value) {
						if(in_array($value, $tiposHotelUsuario)){
							echo '<span class="badge badge-lg '.($value == $hotelTipo ? 'badge-match' : 'badge-success').'">'.$value.'</span>';
						}else{
							echo '<span class="badge badge-lg '.($value == $hotelTipo ? 'badge-notmatch' : 'badge-disabled').'">'.$value.'</span>';
						}
					}
					?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="white-module mt customerData">
				<div class="col-lg-12">
					<div class="row">
					<h3 class="pull-left"><?php echo $userPreferencesLang['Wich kind of decoration this user likes most'] ?></h3>
					</div>
				</div>
				<div class="center-block ">
					<?php
					foreach ($tiposDecoracion as $value) {
						if(in_array($value, $tiposDecoracionUsuario)){
							echo '<span class="badge badge-lg '.($value == $hotelDecoracion ? 'badge-match' : 'badge-success').'">'.$value.'</span>';
						}else{
							echo '<span class="badge badge-lg '.($value == $hotelDecoracion ? 'badge-notmatch' : 'badge-disabled').'">'.$value.'</span>';
						}
					}
					?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="white-module mt customerData">
				<div class="col-lg-12">
					<div class="row">
						<h3 class="pull-left"><?php echo $userPreferencesLang['Wich kind of rooms this user likes most'] ?></h3>
						</div>
				</div>
				<div class="center-block ">
					<?php
					foreach ($tiposHabitacion as $value) {
						if(in_array($value, $tiposHabUsuario)){
							echo '<span class="badge badge-lg '.(in_array($value, $hotelTiposHab) ? 'badge-match' : 'badge-success').'">'.$value.'</span>';
						}else{
							echo '<span class="badge badge-lg '.(in_array($value, $hotelTiposHab) ? 'badge-notmatch' : 'badge-disabled').'">'.$value.'</span>';
						}
					}
					?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="white-module mt customerData">
				<div class="col-lg-12">
					<div class="row">
					<h3 class="pull-left"><?php echo $userPreferencesLang['Wich kind of extras this user values most'] ?></h3>
					</div>
				</div>
				<div class="center-block ">
					<?php
					foreach ($tiposExtras as $value) {
						if(in_array($value, $extrasHabUsuario)){
							echo '<span class="badge badge-lg '.(in_array($value, $hotelExtras) ? 'badge-match' : 'badge-success').'">'.$value.'</span>';
						}else{
							echo '<span class="badge badge-lg '.(in_array($value, $hotelExtras) ? 'badge-notmatch' : 'badge-disabled').'">'.$value.'</span>';
						}
					}
					?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="white-module mt customerData">
				<div class="col-lg-12">
					<div class="row">
					<h3 class="pull-left"><?php echo $userPreferencesLang['Wich kind of services this user values most'] ?></h3>
					</div>
				</div>
				<div class="center-block ">
					<?php
					foreach ($serviciosHotel as $value) {
						if(in_array($value, $serviciosHotelUsuario)){
							echo '<span class="badge badge-lg '.(in_array($value, $hotelServicios) ? 'badge-match' : 'badge-success').'">'.$value.'</span>';
						}else{
							echo '<span class="badge badge-lg '.(in_array($value, $hotelServicios) ? 'badge-notmatch' : 'badge-disabled').'">'.$value.'</span>';
						}
					}
					?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="white-module mt customerData">
				<div class="col-lg-12">
					<div class="row">
					<h3 class="pull-left"><?php echo $userPreferencesLang['This user redeemed those kind of rewards'] ?></h3>
					</div>
				</div>
				<div class="center-block ">
					<div id="categories-chart"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="white-module mt customerData">
				<div class="col-lg-12">
					<div class="row">
					<h3 class="pull-left"><?php echo $userPreferencesLang['This user wishlisted those kind of rewards'] ?></h3>
					</div>
				</div>
				<div class="center-block ">
					<div id="wishlists-chart"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<?php include LIB . 'user-preferences-charts.php' ?>