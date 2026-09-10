<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/hotel-publicar-oferta.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php //include top menu ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-check-circle"></i><?php echo $HotelPublicarOfertaLang['Well done'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 text-center">
				<i class="fa fa-check-circle fa-6x verde"></i>
				<h3><?php echo $HotelPublicarOfertaLang['published campaign text'] ?><a href="<?php echo $urlOferta; ?>" title="oferta" target="_blank"><?php echo $urlOferta; ?></a></h3>
				<a href="<?php echo $urlTree['hotel-crear-oferta'] ?>/?tipo=<?php echo $offermethod ?>" title="gestion de ofertas" class="btn btn-lg btn-primary mt"><?php echo $HotelPublicarOfertaLang['Create new campaign'] ?></a>
			</div>
		</div>
	</div>
</div>