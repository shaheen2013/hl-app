<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/login.php' ?>
<div class="container mt2">
	<div class="col-lg-12">
		<div class="col-lg-6 col-lg-offset-3">
			<img src="<?php echo DIR_IMG; ?>login-logo.png" alt="logo" width="208" height="39" class="loginLogo">
			<div class="col-lg-12 mb white-module login">
				<?php if (!empty($_GET['error'])){ ?>
				<div class="alert alert-danger fade in">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<ul>
						<li><?php echo $_GET['error']; ?></li>
					</ul>
				</div>
				<?php } ?>
				<h1 class="text-center"><?php echo $LoginLang['Acceso de usuario'] ?></h1>
				<p class="text-center mt2"><strong><?php echo $LoginLang['Bienvenido a hotelinking,'] ?></strong><br/>
					<?php echo $LoginLang['Si has sido invitado, puedes acceder con tu cuenta de Twitter'] ?>
				</p>
				<div class="text-center mt2">
					<div class="col-lg-8 col-lg-offset-2">
						<form method="POST" class="login-form">
							<ul>
								<li><button type="submit" class="btn btn-lg btn-primary submitTwitterLogin btn-twitter" name="twitterForm" value="twitterForm" ><i class="fa fa-twitter"></i> <?php echo $LoginLang['Accede con Twitter'] ?></button></li>
								<li><button type="submit" class="btn btn-lg btn-primary submitFacebookLogin btn-facebook" name="facebookForm" value="facebookForm"><i class="fa fa-facebook"></i> <?php echo $LoginLang['Accede con Facebook'] ?></button></li>
								<?php if($token != ''){ ?>
								<li><a class="btn btn-lg btn-primary submitFacebookLogin btn-mail" href="<?php echo $urlTree['create-account'].'/?token='.$token ?>"><i class="fa fa-envelope-o"></i> Create account</a></li>
								<?php } ?>
							</ul>
						</form>
					</div>
					<div class="col-lg-8 col-lg-offset-2 mt2 text-left">
						<?php if($token==''){?>
						<form method="POST">
							<div class="form-group">
								<label>Email address</label>
								<input name="userEmail" class="form-control input-lg" placeholder="Email address..." value="<?php if(!empty($email))echo $email?>"<?php if(!empty($token)){?> disabled <?php }else{?> required <?php }?>/>
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="password"  class="form-control input-lg" placeholder="Password..." name="userPass" required/>
							</div>
							<input class="btn btn-lg btn-primary" type="submit" value="Login"/>
						</form>
						<?php }?>					
					</div>
					<div class="col-lg-10 col-lg-offset-1 mt2">
						<p class="mt2 text-center border-top pt hotel-login-msg"> <?php echo $LoginLang['Si eres hotelero,'] ?> <a href="<?php echo $urlTree['hotel-login'] ?>" title="acceso hoteleros"><?php echo $LoginLang['accede a tu cuenta desde aquí'] ?></a></p>
						<p class="text-center"><?php echo $LoginLang['remember password'] ?> <a href="#" class="recoverPasswordButton" data-toggle="modal" data-target="#password-recovery" title="password recovery"><?php echo $LoginLang['get it back'] ?></a></p>
					</div>                    
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-lg-offset-3">
			<div class="alert alert-info mt">
				<div class="pull-left">
					<i class="fa fa-lightbulb-o fa-4x pr"></i>
				</div>
				<p><?php echo $LoginLang['solcita una invitación'] ?> <a href="<?php echo $urlTree['user-landing'] ?>" title="solicita una invitación"><?php echo $LoginLang['pulsando aquí'] ?></a></p>
			</div>
		</div>
	</div>
</div>
<?php include VIEWS . '/templates/password-recovery-modal.php'; ?>