<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-gestion-ofertas.php' ?>

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
				<h1 class="pull-left">
					<i class="rubies rubix3">rubies</i> <?php echo $hotelgestionofertaslang['campaign manager:'] ?> 
				</h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12">
				<div class="clearfix"></div>
				<?php if(!empty($arrayOfertas)){ ?>
				<div class="table-responsive mt relative">
					<table class="table table-striped">
						<tr class="table-header">
							<td>
								<span class="pull-left"><?php echo $hotelgestionofertaslang['campaign id'] ?> </span><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?ord=id" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $hotelgestionofertaslang['campaign created'] ?></span><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?ord=fecha_creacion" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $hotelgestionofertaslang['campaign name'] ?></span> <a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?ord=nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $hotelgestionofertaslang['Acquired'] ?></span><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?ord=adquiridas" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $hotelgestionofertaslang['Redeemed'] ?></span><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?ord=canjeadas" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $hotelgestionofertaslang['valid from'] ?></span><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?ord=inicio" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $hotelgestionofertaslang['Status'] ?></span><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?ord=estado" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-right"><?php echo $hotelgestionofertaslang['Actions'] ?></span></a>
							</td>
						</tr>
						<?php foreach ($arrayOfertas as $oferta) {?>
						<tr class="table-row">
							<td><?php echo $oferta['id'] ?></td>
							<td><?php echo $oferta['fecha_creacion'] ?></td>
							<td><?php echo $oferta['nombre'] ?></td>
							<td><?php echo $oferta['adquiridas'] ?></td>
							<!-- <td><?php //echo $oferta['quedan'] ?></td> -->
							<td><?php echo $oferta['canjeadas'] ?></td>
							<td><?php echo $oferta['inicio'] ?></td>
							<td><?php if ($oferta['estado'] == '1'){?>
								<div class="verde"><?php echo $hotelgestionofertaslang['status published'] ?></div>
								<?php }else if ($oferta['estado'] == '0'){?>
								<?php echo $hotelgestionofertaslang['status draft'] ?>
								<?php }else if ($oferta['estado'] == '2'){?>
								<div class="naranja"><?php echo $hotelgestionofertaslang['status paused not editable'] ?></div>
								<?php }else if ($oferta['estado'] == '3'){?>
								<?php echo $hotelgestionofertaslang['status paused editable'] ?>
                                <?php }else if ($oferta['estado'] == '4'){?>
                                <?php echo $hotelgestionofertaslang['unassigned no editable'] ?>
                                <?php }else if ($oferta['estado'] == '5'){?>
                                <div class="verde"><?php echo $hotelgestionofertaslang['assigned'] ?></div>
                                <?php }else if ($oferta['estado'] == '6'){?>
                                <?php echo $hotelgestionofertaslang['unassigned editable'] ?>
								<?php } ?>
							</td>
							<td>
								<div class="btn-group-vertical pull-right">
									<?php if($oferta['estado'] == '1' ){?>
									 <a href="<?php echo $urlTree['hotel-detalle-oferta'] ?>/?id=<?php echo $oferta['id']  ?>&lang=<?php echo $oferta['lang']?>" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['ver detalle tooltip'] ?>"><i class="fa fa-eye"></i></a>
									<a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro;?>/?est=pau&id=<?php echo $oferta['id'] ?>" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['pause campaign tooltip'] ?>"><i class="fa fa-pause"></i></a>
									<?php } else if($oferta['estado'] == '0'){?>
									<a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>/?id=<?php echo $oferta['id'] ?>&lang=<?php echo $oferta['lang']?>" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['ver detalle tooltip'] ?>"><i class="fa fa-pencil-square-o"></i></a>
									<a href="#" class="btn btn-warning deleteOfferBtn" data-id="<?php echo $oferta['id'] ?>" title="<?php echo $hotelgestionofertaslang['borrar tooltip'] ?>" data-toggle="modal" data-target="#offerDeleteModal"><i class="fa fa-trash-o"></i></a>
									<?php } else if($oferta['estado'] == '3'){?>
									<a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>/?id=<?php echo $oferta['id'] ?>&lang=<?php echo $oferta['lang']?>" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['ver detalle tooltip'] ?>"><i class="fa fa-pencil-square-o"></i></a>
									<a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro;?>/?est=ply&id=<?php echo $oferta['id'] ?>" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['publish campaign again tooltip'] ?>"><i class="fa fa-play-circle-o verde"></i></a>
									<a href="#" class="btn btn-warning deleteOfferBtn" data-id="<?php echo $oferta['id'] ?>" title="<?php echo $hotelgestionofertaslang['borrar tooltip'] ?>" data-toggle="modal" data-target="#offerDeleteModal"><i class="fa fa-trash-o"></i></a>
                                    <?php } else if($oferta['estado'] == '6'){?>
									<a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>/?id=<?php echo $oferta['id'] ?>&lang=<?php echo $oferta['lang']?>" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['ver detalle tooltip'] ?>"><i class="fa fa-pencil-square-o"></i></a>
									<a href="#" class="btn btn-warning deleteOfferBtn" data-id="<?php echo $oferta['id'] ?>" title="<?php echo $hotelgestionofertaslang['borrar tooltip'] ?>" data-toggle="modal" data-target="#offerDeleteModal"><i class="fa fa-trash-o"></i></a>
									<?php } if($oferta['estado'] == '2'){?>
									 <a href="<?php echo $urlTree['hotel-detalle-oferta'] ?>/?id=<?php echo $oferta['id'] ?>" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['ver detalle tooltip'] ?>"><i class="fa fa-eye"></i></a>
									<a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro;?>/?est=ply&id=<?php echo $oferta['id'] ?>" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['publish campaign again tooltip'] ?>"><i class="fa fa-play-circle-o verde"></i></a> -->
									<?php } ?>
									<?php if(($filtro == 'ref' || $filtro == 'ref-chain') && $oferta['estado']!=0) {?>
										<?php if($oferta['landing'] == '0') {?>
                                        	<a href="<?php echo $urlTree['hotel-detalle-oferta'] ?>/<?php echo $oferta['id'] ?>/" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['ver detalle tooltip'] ?>"><i class="fa fa-eye"></i></a>
											<a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?act=<?php echo $oferta['id'] ?>" title="activate for landing page" class="btn btn-default"><i class="fa fa-desktop"></i></a>
										<?php }else if($oferta['landing'] == '1') {?>
                                        	<a href="<?php echo $urlTree['hotel-detalle-oferta'] ?>/<?php echo $oferta['id'] ?>/" class="btn btn-default " title="<?php echo $hotelgestionofertaslang['ver detalle tooltip'] ?>"><i class="fa fa-eye"></i></a>
											<a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?desact=<?php echo $oferta['id'] ?>" title="activate for landing page" class="btn btn-primary"><i class="fa fa-desktop"></i></a>
										<?php } ?>
									<?php } ?>
								</div>
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
				<?php include TEMPLATES . 'paginacion-template-params.php'; ?>
				<?php } else { ?>
						<div class="text-center mt2">
						<?php echo($filtro == 'adq' ? '<img src="'.DIR_IMG .'big-diamond-blue.png" alt="diamond">' : '<img src="'.DIR_IMG . 'big-diamond.png" alt="diamond">') ?>
						<?php echo($filtro == 'adq' ? '<h2>'.$hotelgestionofertaslang['There´s no adquisition offers right now'].'</h2>' : '<h2>'.$hotelgestionofertaslang['There´s no retention offers right now'].'</h2>') ?>
							<h4><?php echo $hotelgestionofertaslang['To adquire new qualified guests to your hotel <strong>you should start create offers right now!</strong>'] ?></h4>
							<a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>" class="btn btn-lg btn-success mt2" title="Create an offer right now"><?php echo $hotelgestionofertaslang['Create an offer right now'] ?></a>
						</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php include TEMPLATES . 'offerDeleteModal.php'; ?>
<script>
	$(document).ready(function(){
		$('.deleteOfferBtn').click(function(e){
			e.preventDefault();
			var id = $(this).data("id");
			$('.offerDeleteModalBtn').attr('href', '<?php echo $urlTree['hotel-gestion-ofertas'] ?>/<?php echo $filtro ?>/?est=del&id='+ id );
		});
	})
</script>