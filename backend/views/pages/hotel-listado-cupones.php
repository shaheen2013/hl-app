<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-listado-cupones.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-gestion-ofertas-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-ticket"></i> <?php echo $HotelListadoCuponesLang['Vouchers List'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
		<div class="col-lg-12 mt">
		<?php if(!empty($arrayCupones)) {?>
			<form action="<?php echo $url['dir1'] ?>/">
				<div class="input-group mb2">
				<span class="input-group-addon"><i class="fa fa-search"></i></span>
				<input type="text" class="form-control input-lg" name="search" id="cuponMainSearch" placeholder="<?php echo $HotelListadoCuponesLang['Search by voucher ID or guest name box'] ?>">
				</div>
			</form>
			<div class="clearfix"></div>
			<div class="table-responsive mt relative">
				<table class="table table-striped">
					<tr class="table-header">
						<td>
							<span class="pull-left"><?php echo $HotelListadoCuponesLang['Guest name'] ?></span> <a href="<?php echo addToUrl('nombre')?>" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $HotelListadoCuponesLang['Campaign name'] ?></span> <a href="<?php echo addToUrl('nombre_oferta')?>" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $HotelListadoCuponesLang['Voucher ID'] ?></span> <a href="<?php echo addToUrl('voucher')?>" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left">BE transaction</span></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $HotelListadoCuponesLang['Validation date'] ?></span><a href="<?php echo addToUrl('fecha')?>" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
                        <td>
							<span class="pull-left"><?php echo $HotelListadoCuponesLang['Share type'] ?></span><a href="<?php echo addToUrl('share_type')?>" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $HotelListadoCuponesLang['Status'] ?></span> <a href="<?php echo addToUrl('canjeado')?>" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-right"><?php echo $HotelListadoCuponesLang['Actions'] ?></span></a>
						</td>
					</tr>
					<?php foreach ($arrayCupones as $cupon){ ?>
					<tr class="table-row">
						<td>
							<?php if (!empty($cupon['img'])){?>
							<img class="img-circle img-thumbnail" src="<?php echo $cupon['img'] ?>" alt="user avatar" width="50" height="50">
							<?php }else {?>
							<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG;?>avatar.jpg" alt="user avatar" width="50" height="50">
							<?php } ?>
							<?php if($cupon['nombre'] == ''){ ?>
							<span class="pl"><?php echo $cupon['email'] ?><small> (temporal)</small></span>
							<?php } else{ ?>
							<span class="pl"><?php echo $cupon['nombre'] ?></span>
							<?php } ?>
						</td>
						<td>
							<a href="<?php echo $urlTree['oferta'] ?>/<?php echo($cupon['nombre_oferta_san'] .'/' . $cupon['id_oferta']);?>"><?php echo $cupon['nombre_oferta'] ?></a>
						</td>
						<td>
							<?php echo $cupon['voucher']; ?>
						</td>
						<td>
							<?php echo $cupon['transaction']; ?>
						</td>
						<td>
							<?php echo $cupon['fecha']; ?>
						</td>
                        <td>
							<?php echo $cupon['share_type']; ?>
						</td>
						<td class="text-center">
							<?php if ($cupon['canjeado'] == 1){ ?>
								<span class="verde"><?php echo $HotelListadoCuponesLang['Status redeemed'].' '.$cupon['fecha_canj']  ?></span>
							<?php }else{ ?>
								<span><?php echo $HotelListadoCuponesLang['Status pending'] ?></span>
								<?php } ?>
						</td>
						<td class="text-center">
                        	<div class="btn-group">
                            	<?php if ($cupon['canjeado'] == 0){ ?>
                                <a href="<?php echo $urlTree['hotel-listado-cupones'] ?>/<?php echo $pagina ?>/?redeem=<?php echo $cupon['voucher'] ?>" class="btn btn-success" title="redeem"><i class="fa fa-check-circle"></i> Redeem</a>
                                <?php }?>
<!--                                <a href="--><?php //echo $urlTree['hotel-detalle-oferta'].'/'.$cupon['id_oferta']; ?><!--" class="btn btn-default"><i class="fa fa-eye"></i></a>-->
                            </div>
						</td>
					</tr>
					<?php } ?>
				</table>
				<?php include TEMPLATES . 'paginacion-template-pagdir.php'; ?>
			</div>
			<?php } else { ?>
				<div class="text-center mt2">
					<i class="fa fa-ticket grisClaro fa-5x"></i>
					<h2><?php echo $HotelListadoCuponesLang['There´s no vouchers activity for now...'] ?></h2>
					<h4><?php echo $HotelListadoCuponesLang['Maybe you haven´t'] ?></h4>
					<h5><?php echo $HotelListadoCuponesLang['if already did it, please, be patient and try to'] ?></h5>
				</div>
			<?php } ?>
		</div>
		</div>
	</div>
</div>