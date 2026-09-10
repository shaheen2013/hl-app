<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<?php include LANG . $_SESSION['userLang'].'/hotel-login.php' ?>
	<div class="container mt4">
	<div class="col-lg-12">
		<div class="col-lg-6 col-lg-offset-3">
			<?php if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) { ?>
			<img src="<?php echo SECURE_BASE_PATH . DIR_IMG; ?>login-logo.png" alt="logo" width="208" height="39" class="loginLogo">
			<?php } else { ?>
			<img src="<?php echo SECURE_BASE_PATH . DIR_IMG; ?>login-logo.png" alt="logo" width="208" height="39" class="loginLogo">
			<?php } ?>
			<div class="col-lg-12 mb white-module login">
				<h1 class="text-center"><?php echo $HotelLoginLang['hotelier Login'] ?></h1>
				<p class="text-center mt2"><strong><?php echo $HotelLoginLang['hotelier intro text'] ?></strong><br/>
					<?php echo $HotelLoginLang['hotelier intro text 2'] ?>
				</p>
				<form action="<?php echo $url['dir1'] ?>" method="POST">
					<div class="col-lg-10 col-lg-offset-1">
						<label for="email_login"><?php echo $HotelLoginLang['email'] ?></label>
						<input type="email" class="form-control input-lg" id="email_login" name="email_login" placeholder="<?php echo $HotelLoginLang['email text'] ?>" required >
					</div>
					<div class="col-lg-10 col-lg-offset-1 mt2">
						<label for="pass_login"><?php echo $HotelLoginLang['password'] ?></label>
						<input type="password" class="form-control input-lg" id="pass_login" name="pass_login" placeholder="<?php echo $HotelLoginLang['Your password..'] ?>" required >
					</div>
					<div class="col-lg-10 col-lg-offset-1 mt2">
						<input type="checkbox" name="rememberMyPassword"> <small> <?php echo $HotelLoginLang['remember my password'] ?>	</small>			
					</div>
					<div class="col-lg-10 col-lg-offset-1">
					<input class="mt2 btn btn-primary btn-lg center-block" type="submit" name="loginForm" class="btn btn-lg btn-primary" value="<?php echo $HotelLoginLang['Login button'] ?>">
					</div>
				</form>
				<div class="col-lg-10 col-lg-offset-1 mt2">
					<p class="small text-center"><?php echo $HotelLoginLang['remember password'] ?> <a href="#" class="recoverPasswordButton" data-toggle="modal" data-target="#password-recovery" title="password recovery"><?php echo $HotelLoginLang['get it back'] ?></a></p>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-lg-offset-3">
			<div class="alert alert-info">
				<div class="pull-left">
					<i class="fa fa-lightbulb-o fa-4x pr"></i>
				</div>
				<p><?php echo $HotelLoginLang['Warning text'] ?></a></p>
			</div>
		</div>
	</div>
	</div>
<?php include VIEWS . '/templates/password-recovery-modal.php'; ?>