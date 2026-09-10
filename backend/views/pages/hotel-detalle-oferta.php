<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-detalle-oferta.php' ?>
<div id="wrapper">
	<?php if(!empty($_SESSION['h_logueado'])){
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(!empty($_SESSION['staff_logueado'])){
		include TEMPLATES . 'check-sidebar.php';
	} ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-gestion-ofertas-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-eye"></i> <?php echo $HotelDetalleOfertaLang['Detalle de la oferta'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent mt2" id="fullContainer">
			<div class="col-lg-12">
				<div class="white-module ofertaHorizontal">
<!-- 					<p class="offer-tipo"><?php if($arrayDatosOferta['tipo_oferta'] == 'chk'){?><span class="checkInIcon"><i class="fa fa-key"></i></span><?php } ?><span><?php echo $arrayDatosOferta['categoria']; ?></span></p>
 --><!-- 					<?php if($arrayDatosOferta['adq_ret'] != 'ref'){ ?>
					<h2 class="offer-rubies"><?php echo($arrayDatosOferta['adq_ret'] == 'adq' ? '<i class="rubies rubix2 rubiesHL">rubies</i>' : '<i class="rubies rubix2">rubies</i>')?> <?php echo $arrayDatosOferta['puntos'] ?></h2>
					<?php } ?> -->
					<div class="media">
						<?php if ($arrayDatosOferta['img']) { ?>
						<img class="offer-img pull-left" src="<?php echo $arrayDatosOferta['img']; ?>" alt="<?php echo $arrayDatosOferta['nombre']; ?>" width="275"/>
						<?php }else{ ?>
						<img class="offer-img pull-left" src="<?php echo DIR_IMG;?>img-placeholder.jpg" alt="<?php echo $oferta['nombre'] ?? ""; ?>" width="275"/>
						<?php } ?>
						<div class="media-body">
							<h2 class="mt mb2"><?php echo $arrayDatosOferta['nombre'] ?></h2>
							<p><?php echo $HotelDetalleOfertaLang['Esta oferta comienza el'] ?> <strong><?php echo $arrayDatosOferta['inicio'] ?></strong> <?php echo $HotelDetalleOfertaLang['y finaliza el'] ?> <strong><?php echo($arrayDatosOferta['adq_ret'] == 'ref' ? $HotelDetalleOfertaLang['unlimited'] : $arrayDatosOferta['fin']) ?></strong></p>
							<ul>
								<li><strong><?php echo $HotelDetalleOfertaLang['Adquisición o Retención:'] ?></strong> <?php echo $arrayDatosOferta['adq_ret'] ?></li>
								<li><strong class="pl10"><?php echo $HotelDetalleOfertaLang['Estado:'] ?> </strong>
									<?php if($arrayDatosOferta['estado'] == 1){?>
									<span class="verde"><?php echo $HotelDetalleOfertaLang['Publicada'] ?></span>
									<?php } else if($arrayDatosOferta['estado'] == 2){ ?>
									<span class="naranja"><?php echo $HotelDetalleOfertaLang['Pausada'] ?></span>
									<?php } else if($arrayDatosOferta['estado'] == 0){ ?>
									<span><?php echo $HotelDetalleOfertaLang['En borrador'] ?></span>
									<?php } ?></li>
									<li><strong><?php echo $HotelDetalleOfertaLang['Categoría:'] ?></strong> <?php echo $arrayDatosOferta['tipo_oferta'] ?></li>
<!--									<li><strong>--><?php //echo $HotelDetalleOfertaLang['Sub categoría:'] ?><!--</strong> --><?php //echo $arrayDatosOferta['categoria'] ?><!--</li>-->
									<li><strong><?php echo $HotelDetalleOfertaLang['Cupo:'] ?></strong> <?php echo($arrayDatosOferta['adq_ret'] == 'ref' ? $HotelDetalleOfertaLang['unlimited'] : $arrayDatosOferta['cupo']) ?></li>
								</ul>
								<ul>
									<li><strong><?php echo $HotelDetalleOfertaLang['Ofertas adquiridas:'] ?></strong> <?php echo $arrayDatosOferta['adquiridas'] ?></li>
									<li><strong><?php echo $HotelDetalleOfertaLang['Ofertas canjeadas:'] ?></strong> <?php echo $arrayDatosOferta['canjeadas'] ?></li>
									<li><strong><?php echo $HotelDetalleOfertaLang['Ofertas restantes:'] ?></strong> <?php echo($arrayDatosOferta['adq_ret'] == 'ref' ? $HotelDetalleOfertaLang['unlimited'] : $arrayDatosOferta['quedan']) ?></li>
									<?php if($arrayDatosOferta['adq_ret'] == 'ref'){ ?>
									<li><strong><?php echo $HotelDetalleOfertaLang['Asigned to landingpage:'] ?></strong> <?php echo ($arrayDatosOferta['oferta_landing'] == 1 ? $HotelDetalleOfertaLang['YES'] : $HotelDetalleOfertaLang['NO']) ?></li>
									<?php } ?>
								</ul>
								<h3><?php echo $HotelDetalleOfertaLang['Descripción de la oferta'] ?></h3>
								<p><?php echo $arrayDatosOferta['descripcion'] ?></p>
							</div>
						</div>
					</div>
					<form name="searchUser" action="<?php echo $url['dir1'] ."/".$arrayDatosOferta['id'] ?>/" class="mt2">
						<div class="input-group mb2">
							<span id="search-icon" class="input-group-addon"><i class="fa fa-search"></i></span>
							<input type="text" class="form-control input-lg" name="search" id="cuponMainSearch" placeholder="<?php echo $HotelDetalleOfertaLang['Search by voucher ID or guest name box'] ?>">
						</div>
					</form>
					<?php if(!empty($arrayCupones)) {?>
					<div class="tablaDetallesOferta">
						<div class="table-responsive mt relative">
							<table class="table table-striped">
								<tr class="table-header">
									<td>
										<span class="pull-left"><?php echo $HotelDetalleOfertaLang['Nombre'] ?></span> <a href="<?php echo $urlNoParams;?>?ord=nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
									</td>
									<td>
										<span class="pull-left"><?php if($arrayDatosOferta['adq_ret']=='ref'){echo $HotelDetalleOfertaLang['Promo code'];}else{echo $HotelDetalleOfertaLang['ID cupón'];} ?></span> <a href="<?php echo $urlNoParams;?>?ord=voucher" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
									</td>
									<td>
										<span class="pull-left"><?php if($arrayDatosOferta['adq_ret']=='ref'){echo $HotelDetalleOfertaLang['ref Date'];}else{ echo $HotelDetalleOfertaLang['Fecha de compra'];}?></span><a href="<?php echo $urlNoParams;?>?ord=fecha" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
									</td>
									<td>
										<span class="pull-left"><?php if($arrayDatosOferta['adq_ret']=='ref'){echo $HotelDetalleOfertaLang['ref Booking'];}else{ echo $HotelDetalleOfertaLang['Fecha de validación'];}?></span><a href="<?php echo $urlNoParams;?>?ord=<?php if($arrayDatosOferta['adq_ret']=='ref'){echo 'booking_value';}else{echo 'fecha_canj';} ?>" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelDetalleOfertaLang['Estado'] ?></span> <a href="<?php echo $urlNoParams;?>?ord=canjeado" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
									</td>
<!--                                    <td>-->
<!--										<span class="pull-left">--><?php //echo $HotelDetalleOfertaLang['Canjeado por'] ?><!--</span> <a href="--><?php //echo $urlNoParams;?><!--?ord=redeemed_by" title="sort"><i class="fa fa-sort pull-left pl"></i></a>-->
<!--									</td>-->
<!--									<td>-->
<!--										<span class="pull-right">--><?php //echo $HotelDetalleOfertaLang['Acciones'] ?><!--</span></a>-->
<!--									</td>-->
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
										<?php echo $cupon['voucher']; ?>
									</td>
									<td>
										<?php echo $cupon['fecha']; ?>
									</td>
									<td>
										<?php if($arrayDatosOferta['adq_ret']=='ref'){?>
										<?php echo $cupon['booking_value'].' '.$cupon['current_coin']; ?>
										<?php }else{?>
										<?php if($cupon['canjeado'] == '1'){ ?>
										<?php echo $cupon['fecha_canj']; ?>
										<?php }else{ ?>
										<?php echo $HotelDetalleOfertaLang['Not Redeemed'] ?>
										<?php } ?>
										<?php }?>
									</td>
									<td>
										<?php if ($cupon['canjeado'] == 1){ ?>
										<?php if($arrayDatosOferta['adq_ret']=='ref'){?>
										<span class="verde"><?php echo $HotelDetalleOfertaLang['Used'] ?></span>
										<?php }else{?>
										<span class="verde"><?php echo $HotelDetalleOfertaLang['Redeemed'] ?></span>
										<?php }?>
										<?php }else{ ?>
										<?php if($arrayDatosOferta['adq_ret']=='ref'){?>
										<span><?php echo $HotelDetalleOfertaLang['Not used'] ?></span>
										<?php }else{?>
										<span><?php echo $HotelDetalleOfertaLang['Adquired'] ?></span>
										<?php }?>
										<?php } ?>
									</td>
<!--                                    <td>-->
<!--										--><?php //echo $cupon['redeemed_by']; ?>
<!--									</td>-->
<!--									<td class="text-right">-->
<!--										<div class="btn-group">-->
<!--											--><?php //if ($cupon['canjeado'] == 0){ ?>
<!--											<a href="--><?php //echo $urlNoParams ?><!--?redeem=--><?php //echo $cupon['voucher'] ?><!--" class="btn btn-success" title="redeem"><i class="fa fa-check-circle"></i> Redeem</a>-->
<!--											--><?php //}?>
<!--											<a href="--><?php ////echo $urlTree['oferta'].'/'.$cupon['nombre_oferta_san'].'/'.$id_oferta; ?><!--" class="btn btn-default"><i class="fa fa-eye"></i></a>-->
<!--										</div>-->
<!--									</td>-->
								</tr>	
								<?php } ?>
							</table>
							<?php include TEMPLATES . 'paginacion-template-pagdir.php'; ?>
						</div>
					</div>
					<?php } else { ?>
					<div class="text-center mt2"><i class="fa fa-ticket fa-5x grisClaro"></i></div>
					<h2 class="text-center">There´s no vouchers redeemed at this moment</h2>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<script src="<?php echo DIR_JS . 'tienda.js'?>"></script>
	<script>
		$(document).ready(function(){
			$('#search-icon').on('click', function(){
				$('form[name="searchUser"').submit();
			})
		});
	</script>
