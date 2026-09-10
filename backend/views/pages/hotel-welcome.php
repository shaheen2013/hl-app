<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/hotel-welcome.php' ?>
<div class="mainContent mt2">
	<div class="col-lg-12">
	<div class="col-lg-6 col-lg-offset-3">
				<?php if (isset($errorMsg)){ ?>
				<div class="alert alert-danger fade in text-center">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<p><?php echo $errorMsg; ?></p>
				</div>
				<?php } ?>
				<div class="col-lg-12 mb text-center">
					<img src="<?php echo DIR_IMG . 'hotel-welcome-big.png'?>" alt="Welcome img" width="207" height="295">
						<h1><?php echo $HotelWelcomeLang['Bienvenido a hotelinking'] ?></h1>
						<p><?php echo $HotelWelcomeLang['Text 1'] ?></p>
						<p><?php echo $HotelWelcomeLang['Text 2'] ?></p>
				</div>
				<form role="form" class="mt2" action="<?php echo $url['dir1'].'/?token='.$token; ?>" method="post">
					<div class="col-lg-6 mt2">
						<label for="hotelPassword"><?php echo $HotelWelcomeLang['Escribe una contraseña (6-18 char.)'] ?></label>
						<input type="password" pattern=".{6,18}" name="hotelPassword" class="form-control" id="hotelPassword" required>
                        <p class="help-block naranja" id="passInfo"></p>
					</div>
					<div class="col-lg-6 mt2">
						<label for="hotelPassword"><?php echo $HotelWelcomeLang['Confirmar tu contraseña (6-18 char.)'] ?></label>
						<input type="password" pattern=".{6,18}" name="RhotelPassword" class="form-control" id="RhotelPassword" required>
                        <p class="help-block naranja" id="passInfo"></p>
					</div>
					<div class="clearfix"></div>
					<div class="col-lg-12 mt2">
						<div class="checkbox">
							<label>
								<input type="checkbox" checked="checked" required> <?php echo $HotelWelcomeLang['He leido y acepto las'] ?> <a href="http://hotelinking.com/privacy-policy/" title="condiciones del servicio" target="_blank"><?php echo $HotelWelcomeLang['condiciones del servicio'] ?></a>
							</label>
						</div>
					</div>
					<div class="col-lg-5 mb">
						<input type="submit" value="<?php echo $HotelWelcomeLang['Confirma estos datos'] ?>" class="btn btn-success btn-lg mt2 btn-block" name="hotelConfirmButton" id="hotelConfirmButton">
					</div>
				</form>
			<div class="clearfix"></div>
	</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo DIR_JS?>hotel-welcome.js"></script>
<script>
	$(document).ready(function(){
		$('#hotelConfirmButton').prop("disabled", true);
	});
</script>