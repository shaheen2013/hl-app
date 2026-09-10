<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-profile.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="container">
	<div class="user-utility-bar mb15">
		<?php include TEMPLATES . 'user-profile-menu.php'; ?>
	</div>
</div>
<div class="container" id="fullContainer">
	<div class="panel panel-default">
		<div class="panel-body">
	<div class="col-lg-10 col-lg-offset-1">
		<?php if (isset($errorMsg)){ ?>
		<div class="alert alert-danger fade in text-center">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			<p><?php echo $errorMsg; ?></p>
		</div>
		<?php } ?>
		<form role="form"  action="<?php echo $url['dir1']?>" method="post">
			<div class="col-lg-6 col-md-12 mt">
				<label for="userName"><?php echo $UserProfileLang['Tu nombre'] ?></label>
				<input type="text" class="form-control" id="userName" value="<?php echo $arrayDatosUsuario['nombre']; ?>" name="userName" placeholder="<?php echo $UserProfileLang['Escribe tu nombre...'] ?>" required >
			</div>
			<div class="col-lg-6 col-md-12 mt">
				<label for="userEmail"><?php echo $UserProfileLang['Tu correo electrónico'] ?></label>
				<div class="form-inline">
					<input type="text" class="form-control" id="userEmail" value="<?php echo $arrayDatosUsuario['email']; ?>" name="userEmail"  placeholder="<?php echo $UserProfileLang['tu correo electrónico...'] ?>" disabled="disabled" >
				</div>
			</div>
			<div class="col-lg-6 mt">
				<label for="birthDate"><?php echo $UserProfileLang['Fecha de nacimiento'] ?></label>
				<div class="form-inline">
					<div class="form-group col-lg-4 col-sm-3 pl0">
						<input type="text" class="form-control" id="birthDay" value="<?php echo $arrayFechaNacimiento['2']; ?>" name="birthDay" placeholder="<?php echo $UserProfileLang['dd'] ?>" required >
					</div>
					<div class="form-group col-lg-4 col-sm-3 pl0">
						<input type="text" class="form-control" id="birthMonth" value="<?php echo $arrayFechaNacimiento['1']; ?>" name="birthMonth" placeholder="<?php echo $UserProfileLang['mm'] ?>" required >
					</div>
					<div class="form-group col-lg-4 col-sm-3 pl0">
						<input type="text" class="form-control" id="birthYear" value="<?php echo $arrayFechaNacimiento['0']; ?>" name="birthYear" placeholder="<?php echo $UserProfileLang['aaaa'] ?>" required >
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="col-lg-6 mt">
				<label for="userSex"><?php echo $UserProfileLang['Sexo'] ?></label>
				<select class="form-control" name="userSex" id="userSex">
					<option value="n"><?php echo $UserProfileLang['Selecciona...'] ?></option>
					<option value="m" <?php if($arrayDatosUsuario['sexo'] == 'm'){ echo 'selected';};?>><?php echo $UserProfileLang['Mujer'] ?></option>
					<option value="h" <?php if($arrayDatosUsuario['sexo'] == 'h'){ echo 'selected';};?>><?php echo $UserProfileLang['Hombre'] ?></option>
				</select>
			</div>
			<div class="clearfix"></div>
			<div class="col-lg-6 mt">
				<label for="userCity"><?php echo $UserProfileLang['Ciudad'] ?></label>
				<div class="form-inline">
					<input type="text" class="form-control" id="userCity" value="<?php echo $arrayDatosUsuario['location']; ?>" name="userCity"  placeholder="<?php echo $UserProfileLang['Ciudad...'] ?>" required >
				</div>
			</div>

			<div class="col-lg-6 mt">
				<label><?php echo $UserProfileLang['Link social medias'] ?></label>
				<div class="form-inline">
					<?php foreach($redesSociales as $key=>$social ){?>
					<input type="submit" value="<?php echo $key?>" name="btn-<?php echo $key?>"class="btn btn-<?php echo $key?>" <?php if($social==1)echo 'disabled="disabled" ' ?>/>
					<?php }?>
				</div>    
			</div>

			<div class="col-lg-12 mt2">
				<div class="checkbox">
					<label>
						<input type="checkbox" checked="checked"> <?php echo $UserProfileLang['He leido y acepto las'] ?> <a href="<?php echo $urlTree['terms-and-conditions'] ?>" title="condiciones del servicio"><?php echo $UserProfileLang['condiciones del servicio'] ?></a>
					</label>
				</div>
			</div>
			<div class="col-lg-5 mb">
				<input type="submit" value="<?php echo $UserProfileLang['Save changes'] ?>" class="btn btn-success btn-lg mt2 btn-block" name="hotelConfirmButton">
			</div>
		</form>
		<div class="clearfix"></div>
	</div>
		</div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en&key=<?php echo GOOGLE_API_KEY ?>"></script>
<script>
	var input = document.getElementById('userCity');
	var options = {types: ['geocode']}
	new google.maps.places.Autocomplete(input, options);
</script>