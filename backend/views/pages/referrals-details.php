<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/referrals-details.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-truck"></i> <?php echo $referralsDetailsLang['Referral details'] ?>
				<?php if(!empty($datosUsuario['nombre'])) {?>
					<!-- <a href="<?php echo $datosUsuario['urlGuid'] ?>" class="hasTooltip" data-toggle="tooltip" data-placement="top" title="Take a look at the guest profile"><?php echo $datosUsuario['nombre'] ?></a> -->
					<a href="<?php echo $urlTree['clients-profile']?>/<?php echo $datosUsuario['id'] ?>" class="hasTooltip" data-toggle="tooltip" data-placement="top" title="Take a look at the guest profile"><?php echo $datosUsuario['nombre'] ?></a>
				<?php } else { 
						echo $datosUsuario['email'];
					 } ?>
				</h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
			<?php if(!empty($arrayUsuarios)){ ?>
				<div class="clearfix"></div>
				<div class="table-responsive mt relative">
					<table class="table table-striped">
						<tr class="table-header">
							<td>
								<span><?php echo $referralsDetailsLang['Reward Id'] ?></span> <a href="<?php echo $urlActual ?>?ord=id" title="sort"><i class="fa fa-sort pull-right pl"></i></a>
							</td>
							<td>
								<span><?php echo $referralsDetailsLang['Reward name'] ?></span> <a href="<?php echo $urlActual ?>?ord=offer_name" title="sort"><i class="fa fa-sort pull-right pl"></i></a>
							</td>
							<td>
								<span><?php echo $referralsDetailsLang['Voucher Id'] ?></span> <a href="<?php echo $urlActual ?>?ord=offer_id" title="sort"><i class="fa fa-sort pull-right pl"></i></a>
							</td>
							<td>
								<span><?php echo $referralsDetailsLang['Promo code'] ?></span> <a href="<?php echo $urlActual ?>?ord=promo_code" title="sort"><i class="fa fa-sort pull-right pl"></i></a>
							</td>
							<td>
								<span><?php echo $referralsDetailsLang['Total spent'] ?></span> <a href="<?php echo $urlActual ?>?ord=total_spent" title="sort"><i class="fa fa-sort pull-right pl"></i></a>
							</td>
						</tr>
						<?php foreach ($arrayUsuarios as $usuario) {?>
						<tr class="table-row">
							<td>
								<?php echo $usuario['offer_id'] ?>
							</td>
							<td>
								<a href="<?php echo $urlTree['hotel-detalle-oferta'] . '/' . $usuario['offer_id'] ?>" title="Reward details"><?php echo $usuario['offer_name'] ?></a>
							</td>
							<td>
								<?php echo $usuario['id'] ?>
							</td>
							<td>
								<?php echo $usuario['promo_code'] ?>
							</td>
							<td>
								<?php echo $usuario['total_spent'] ?>
							</td>
						</tr>
						<?php } ?>
					</table>
					<?php include TEMPLATES . 'paginacion-template.php'; ?>
				</div>
			<?php }else{ ?>
					<div class="text-center mt2 container no-data-msg">
						<i class="fa fa-truck grisClaro fa-5x"></i>
						<h2><?php echo $referralsDetailsLang['There´re no referral activity at this moment'] ?></h2>
						<h4><?php echo $referralsDetailsLang['By the way, ¿Are your customers sharing his opinion about your hotel?'] ?></h4>
						<a href="<?php echo $urlTree['invitar-usuarios'] ?>/?alert=1" class="btn btn-lg btn-success mt2"><?php echo $referralsDetailsLang['Start inviting guests to your hotel'] ?></a>
					</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>