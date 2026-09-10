<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/checkout.php' ?>
<div id="wrapper">
	<?php if(!empty($_SESSION['h_logueado'])){
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(!empty($_SESSION['staff_logueado'])){
		include TEMPLATES . 'check-sidebar.php';
	} ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include(TEMPLATES . 'check-out-steps.php'); ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-sign-out"></i> <?php echo $checkoutLang['Checkout user'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
		<div class="col-lg-12 mt">
			<form action="<?php echo $url['dir1'].'/'; ?>">
				<div class="input-group mb2">
					<span class="input-group-addon"><i class="fa fa-users"></i></span>
					<input type="text" class="form-control input-lg" name="search" id="cuponMainSearch" placeholder="<?php echo $checkoutLang['Search by guest name'] ?>">
				</div>
			</form>
			<?php if (!empty($listadoUsuarios)) { ?>
							<div class="table-responsive mt relative">
				<table class="table table-striped">
					<tr class="table-header">
						<td>
							<span class="pull-left"><?php echo $checkoutLang['Guest name'] ?></span> <a href="<?php echo $urlTree['checkout'] ?>/?ord=nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $checkoutLang['Checkin date'] ?></span><a href="<?php echo $urlTree['checkout'] ?>/?ord=chkin_date" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $checkoutLang['Reward offers redeemed'] ?></span> <a href="<?php echo $urlTree['checkout'] ?>/?ord=redeemedOffers" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-right"><?php echo $checkoutLang['Actions'] ?></span></a>
						</td>
					</tr>
					<?php foreach ($listadoUsuarios as $usuario) { ?>
					<tr class="table-row">
						<td>
						<?php if ($usuario['img']){?>
							<img class="img-circle img-thumbnail" src="<?php echo $usuario['img'];?>" width="50" height="50">
							<?php }else{?>
							<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG;?>avatar.jpg" width="50" height="50">
							<?php } ?>
							<span class="pl"><a href="<?php echo $usuario['urlGuid']?>"><?php echo $usuario['nombre'];?></a></span>
						</td>
						<td>
							<?php echo $usuario['chkin_date'];?>
						</td>
						<td>
							<?php echo $usuario['redeemedOffers'];?>
						</td>
						<td>
							<div class="btn-group pull-right">
								<a href="<?php echo $urlTree['checkout-user'] ?>/?id=<?php echo $usuario['id_usuario'];?>" class="btn btn-lg btn-primary" title="<?php echo $checkoutLang['Checkout this user tooltip'] ?>"><i class="fa fa-sign-out"></i></a>
							</div>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php } else{ ?>
					<div class="text-center mt2">
						<i class="fa fa-users grisClaro fa-5x"></i>
						<h2><?php echo $checkoutLang['There´re no guests to check-out at this moment'] ?></h2>
						<h4><?php echo $checkoutLang['First guests needs to be checked in the hotel before check out them'] ?></h4>
						<a href="<?php echo $urlTree['invitar-usuarios'] ?>/?alert=1" class="btn btn-lg btn-success mt2"><?php echo $checkoutLang['Check the next guest in!'] ?></a>
					</div>
			<?php } ?>
		</div>
		</div>
	</div>
</div>