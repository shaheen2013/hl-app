<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/checkout-user.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-sign-out"></i> <?php echo $checkoutuserLang['Guest Check-Out'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent mt2" id="fullContainer">
			<img class="logoPrint dnone mb2" src="<?php echo DIR_IMG ?>printLogo.jpg" alt="logo for print" width="191" height="37">
			<?php if(!empty($datosUsuario)) { ?>
			<div class="col-lg-12">
				<?php if ($datosUsuario['img']){?>
				<img class="img-circle img-thumbnail" src="<?php echo $datosUsuario['img'];?>" width="50" height="50">
				<?php }else{?>
				<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG;?>avatar.jpg" width="50" height="50">
				<?php } ?>
				<span class="pl"><a href="<?php echo $datosUsuario['urlGuid']?>"><?php echo $datosUsuario['nombre'];?></a></span>
				<button class="pull-right printBtn btn btn-default"><i class="fa fa-print"></i> <?php echo $checkoutuserLang['print this list'] ?></button>
				<div class="table-responsive mt relative">
					<table class="table table-striped">
						<tr class="table-header">
							<td>
								<span class="pull-left"><?php echo $checkoutuserLang['voucher name'] ?></span> <a href="<?php echo $_SERVER["REQUEST_URI"] ?>&ord=nombre_oferta" title="sort" class="sortOption"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $checkoutuserLang['redeem date'] ?></span><a href="<?php echo $_SERVER["REQUEST_URI"] ?>&ord=fecha_canj" title="sort" class="sortOption"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $checkoutuserLang['Voucher ID'] ?></span> <a href="<?php echo $_SERVER["REQUEST_URI"] ?>&ord=voucher" title="sort" class="sortOption"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $checkoutuserLang['rubies'] ?></span></a>
							</td>
							<td class="actionsTd">
								<span class="pull-right"><?php echo $checkoutuserLang['actions'] ?></span></a>
							</td>
						</tr>
						<?php if(!empty($cuponesUsuario)){ ?>
						<?php foreach ($cuponesUsuario as $cupon) { ?>
						<tr class="table-row">
							<td>
								<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $cupon['nombre_oferta_san'] ?>/<?php echo $cupon['id_oferta'] ?>" title="<?php echo $cupon['nombre_oferta'] ?>"><?php echo $cupon['nombre_oferta'] ?></a>
							</td>
							<td>
								<?php echo $cupon['fecha_canj'] ?>
							</td>
							<td>
								<?php echo $cupon['voucher'] ?>
							</td>
							<td>
								<?php echo ($cupon['adq_ret'] == 'adq' ? '<i class="rubies rubix1 rubiesHL">rubies</i>' : '<i class="rubies rubix1">rubies</i>') ?> <?php echo $cupon['puntos'] ?>
							</td>
							<td class="actionsTd">
								<div class="btn-group pull-right">
									<a href="<?php echo $urlTree['hotel-detalle-oferta'] ?>/<?php echo $cupon['id_oferta'] ?>" class="btn btn-default" title="Open in new window"><i class="fa fa-eye"></i></a>
								</div>
							</td>
						</tr>
						<?php } ?>
						<?php } else { ?>
						<tr class="table-row">
							<td>
								-
							</td>
							<td>
								-
							</td>
							<td>
								-
							</td>
							<td>
								-
							</td>
							<td class="actionsTd">
								-
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
				<div class="alert alert-info mt"><span><?php echo $checkoutuserLang['Remember'] ?></span></div>
				<div class="col-lg-6">
					<div class="row">
						<form class="form-inline" action="<?php echo $urlTree['checkout-thanks'] ?>" method="post">
							<div class="form-group">
								<label for="USD"><?php echo $checkoutuserLang['How much money this guest spent'] ?></label>
								<div class="input-group mb2">
									<div class="input-group-btn">
										<button type="button" class="btn btn-lg btn-default dropdown-toggle " data-toggle="dropdown" aria-expanded="false"><span class="divisa-toggle"><?php echo $moneda ?></span> </button>
										<input class="input-divisa" type="hidden" id="divisas" name="divisas" value="<?php echo $moneda ?>">
									</div><!-- /btn-group name currency-->
									<input type="number" class="form-control input-lg" name="amount" id="amount">
								</div>
							</div>
							<div class="form-group">
								<label for="rubies"><?php echo $checkoutuserLang['How many rubies the guest deserves?'] ?></label>
								<div class="input-group mb2">
									<input type="number" class="form-control input-lg " name="rubies" id="rubies" value="" disabled>
									<span class="input-group-addon"><i class="rubies rubix2">rubies</i></span>
								</div>
							</div>
							<input type="hidden" name="userId" value="<?php echo $datosUsuario['id_usuario'];?>">
							<input type="submit" class="btn btn-primary btn-lg" value="<?php echo $checkoutuserLang['check out button'] ?>">
						</form>
					</div>
				</div>
			</div>
			<?php } else { ?>
			<div class="text-center mt2">
				<i class="fa fa-users grisClaro fa-5x"></i>
				<h2><?php echo $checkoutuserLang['There´s no user to check out'] ?></h2>
				<h4><?php echo $checkoutuserLang['please select one in the previous step'] ?></h4>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script>
	function calcularPuntos(){
		var moneda= $("#divisas").val();
		var cantidad= $("#amount").val();
		if(moneda!='' && cantidad!=''){
			$.ajax({ url: "/lib/webservices/checkout-user.php",
				data: 'currency='+ moneda +'&amount='+cantidad,
				type: 'POST',
				success: function(output) {
					$('#rubies').empty();
					$('#rubies').val(output);
				}
			});
		}

	};
	$(document).ready(function(e){
		$('.printBtn').click(function(){
			print();
		})
		$('#amount').on('keyup', function(){ // ----amount
			calcularPuntos();
		});
		$('#divisas').change(function(){ // ----currency
			calcularPuntos();
		});
		$('.a-divisa').click(function(e){
			e.preventDefault();
		})
		$('.a-divisa').click(function(e){
			e.preventDefault();
			var divisa = $(this).data('value');
			$('.input-divisa').val(divisa);
			$('.divisa-toggle').text(divisa);
			calcularPuntos();
		})
	})
</script>
