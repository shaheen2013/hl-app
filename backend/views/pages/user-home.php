<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<div id="wrapper">
	<?php include TEMPLATES . 'user-sidebar.php'; ?>
	<div id="page-content-wrapper">
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-home"></i> Home</h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12 userHome mainContent" id="fullContainer">
		<div class="buscador">
			<h1 class="text-center blanco userHomeTagLine">Canjea tus <i class="rubies rubix3">rubies</i> en hoteles que te gusten</h1>
			<div class="col-lg-6 col-lg-offset-3">
				<form action="" method="POST">
					<div class="row mt2">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-map-marker fa-2x"></i></span>
							<input type="text" class="form-control input-lg searchInput" placeholder="Ciudad,destino, u hotel...">
							<span class="input-group-btn">
								<button class="btn btn-primary btn-lg" type="button">Encuentra hoteles</button>
							</span>
						</div><!-- /input-group -->
					</div><!-- /.row -->
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		setTimeout(
			function()
			{
				$('.searchInput').focus();
			}, 1000);
	})
</script>