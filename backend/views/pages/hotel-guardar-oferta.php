<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG .$_SESSION['userLang']. '/hotel-guardar-oferta.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-floppy-o"></i> <?php echo $HotelGuardarOfertaLang['Reward campaign saved'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 text-center">
			<i class="fa fa-floppy-o fa-6x azul"></i>
				<h2><?php echo $HotelGuardarOfertaLang['Reward campaign saved text'] ?></h2>
				<h3><?php echo $HotelGuardarOfertaLang['Edit campaign text'] ?><?php echo $HotelGuardarOfertaLang['campaign dashboard'] ?></a> <?php echo $HotelGuardarOfertaLang['and publish it'] ?></h3>
				<a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" title="gestión de ofertas" class="btn btn-lg btn-primary mt"><?php echo $HotelGuardarOfertaLang['create new campaign button'] ?></a>
			</div>
		</div>
	</div>
</div>