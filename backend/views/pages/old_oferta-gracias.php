<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/oferta-gracias-canjeo-oferta.php' ?>
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
		echo '</div></div></div>';
	}?>
	<div id="page-content-wrapper">
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 text-center">
			<i class="fa fa-check-circle fa-6x verde"></i>
				<h2><strong><?php echo $OfertaGraciasCanjeoOfertaLang['Muchas gracias!'] ?></strong><br><br><?php echo $OfertaGraciasCanjeoOfertaLang['acabas de canjear'] ?><br/>
				<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $arrayDatosOferta['nombre_san'] ?>/<?php echo $arrayDatosOferta['id_oferta'] ?>" title="<?php echo $arrayDatosOferta['nombre'] ?>"><?php echo $arrayDatosOferta['nombre'] ?></a><br/>
				<?php echo $OfertaGraciasCanjeoOfertaLang['del hotel'] ?> <a href="<?php echo $urlHotel ?>" title="<?php echo $arrayDatosOferta['hotelName'] ?>"><?php echo $arrayDatosOferta['hotelName'] ?></a>
				<?php echo $OfertaGraciasCanjeoOfertaLang['por'] ?> <strong><?php echo $arrayDatosOferta['puntos'] ?></strong> <?php echo($arrayDatosOferta['adq_ret'] == 'adq' ? '<i class="rubies rubix3 rubiesHL">rubies</i>' : '<i class="rubies rubix3">rubies</i>') ?></h2>
				<h3><?php echo $OfertaGraciasCanjeoOfertaLang['¿Qué quieres hacer a continuación?'] ?></h3>
				<a href="<?php echo $urlTree['tienda'] ?>" title="tienda" class="btn btn-lg btn-primary mt"><i class="fa fa-shopping-cart"></i> <?php echo $OfertaGraciasCanjeoOfertaLang['Volver a la tienda'] ?></a>
				<a href="<?php echo $urlTree['oferta-gracias'] ?>/?id=<?php echo $arrayDatosOferta['id_oferta'] ?>&id-cupon=<?php echo $arrayDatosOferta['id_cupon'] ?>&nombre=<?php echo $arrayDatosOferta['nombre_san'] ?>" title="panel de control" class="btn btn-lg btn-primary mt ml"> <i class="fa fa-twitter"></i> <?php echo $OfertaGraciasCanjeoOfertaLang['Comparte tu alegría!'] ?></a>
			</div>
		</div>
	</div>
</div>