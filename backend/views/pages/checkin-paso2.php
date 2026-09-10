<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/checkin-paso2.php' ?>
<div id="wrapper">
	<?php if(!empty($_SESSION['h_logueado'])){
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(!empty($_SESSION['staff_logueado'])){
		include TEMPLATES . 'check-sidebar.php';
	} ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include(TEMPLATES . 'check-in-top-menu.php'); ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-check-circle-o"></i> <?php echo $lang['Reward vouchers validation'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
				<form action="<?php echo $url['dir1']. '/'.$id_usuario.'/' ?>">
					<div class="input-group mb2">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
						<input type="text" class="form-control input-lg" name="search" id="cuponMainSearch" placeholder="<?php echo $lang['Search by voucher ID number'] ?>">
					</div>
				</form>
				<div class="clearfix"></div>
				<?php if(!empty($id_usuario) || !empty($_GET['search'])) { ?>
					<form class="form-horizontal" action="<?php $url['dir1'] ?>" name="vouchersForm" method="POST">
						<div class="tablaDetallesOferta">
							<div class="table-responsive mt relative">
								<table class="table table-striped">
									<tr class="table-header">
										<td>
											<i class="fa fa-check-square"></i> <?php echo $lang['Select'] ?> 	<a href="#" class="pull-right small toggleSelect">Toggle select</a>
										</td>
										<td>
											<span class="pull-left"><?php echo $lang['Guest name'] ?></span> <a href="<?php echo $_SERVER["REQUEST_URI"] ?>&ord=nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
										</td>
										<td>
											<span class="pull-left"><?php echo $lang['Reward Campaign'] ?></span> <a href="<?php echo $_SERVER["REQUEST_URI"] ?>&ord=nombre_oferta" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
										</td>
										<td>
											<span class="pull-left"><?php echo $lang['Voucher ID'] ?></span> <a href="<?php echo $_SERVER["REQUEST_URI"] ?>&ord=voucher" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
										</td>
										<td>
											<span class="pull-left"><?php echo $lang['Redeem date'] ?></span><a href="<?php echo $_SERVER["REQUEST_URI"] ?>&ord=fecha" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
										</td>
										<td>
											<span class="pull-left"><?php echo $lang['Voucher due date'] ?></span><a href="<?php echo $_SERVER['REQUEST_URI'] ?>&ord=fin" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
										</td>
										<td>
											<span class="pull-right"><?php echo $lang['Actions'] ?></span></a>
										</td>
									</tr>
									<?php foreach ($arrayCupones as $cupon) { ?>
									<tr class="table-row">
										<td>
											<input type="checkbox" name="redeems[]" value="<?php echo $cupon['voucher'] ?>">
										</td>
										<td>
											<a href="<?php echo $cupon['urlGuid'] ?>" title="<?php echo $cupon['nombre'] ?>"><?php echo $cupon['nombre'] ?></a>
										</td>
										<td>
											<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $cupon['nombre_oferta_san'] ?>/<?php echo $cupon['id_oferta'] ?>" title="<?php echo $cupon['nombre_oferta'] ?>"><?php echo $cupon['nombre_oferta'] ?></a>
										</td>
										<td>
											<?php echo $cupon['voucher'] ?>
										</td>
										<td>
											<?php echo $cupon['fecha'] ?>
										</td>
										<td>
											<?php echo $cupon['fin'] ?>
										</td>
										<td class="text-center">
											<div class="btn-group">
												<a href="/<?php echo $urlTree['checkin-paso2'] ?>/<?php echo $cupon['id_usuario'] ?>/?redeem=<?php echo $cupon['voucher'] ?>" class="btn btn-success" title="redeem"><i class="fa fa-check-circle"></i> Redeem</a>
												<a href="<?php echo $urlTree['hotel-detalle-oferta'] ?>/<?php echo $cupon['nombre_oferta_san'] ?>/?id=<?php echo $cupon['id_oferta'] ?>" class="btn btn-default" title="details"><i class="fa fa-eye"></i></a>
											</div>
										</td>
									</tr>
									<?php } ?>
								</table>
								<div class="pull-left">
									<a href="<?php echo $urlTree['checkin-paso1'] ?>" class="btn btn-default"><i class="fa fa-times"></i> <?php echo $lang['Skip for later'] ?></a>
								</div>
								<div class="pull-right">
									<span class="pull-left"><?php echo $lang['Select on action for the selected items'] ?></span><select class="form-control pull-right" name="selectedVouchers">
									<option value="redeem"><?php echo $lang['Validate selected vouchers'] ?></option>
								</select>
								<input type="submit" class="btn btn-default mt" name="redeemsForm" value="<?php echo $lang['Confirm action'] ?>">
							</div>
						</div>
					</form>
					<?php } else { ?>
						<div class="text-center mt2">
						<i class="fa fa-ticket grisClaro fa-5x"></i>
							<h2><?php echo $lang['Nothing to show here right now'] ?></h2>
							<h4><?php echo $lang['Please search by voucher code in the form above or'] ?> <a href="<?php echo $urlTree['checkin-paso1'] ?>" title="<?php echo $lang['Check in a new guest'] ?>"><?php echo $lang['Check in a new guest'] ?></a></h4>
							<h5><?php echo $lang['You can take a look at your voucher lists too'] ?></h5>
						</div>
					<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.toggleSelect').click(function(e){
			e.preventDefault();
			var checkBoxes = $("input[name=redeems\\[\\]]");
			checkBoxes.prop("checked", !checkBoxes.prop("checked"));
		})
	})
</script>