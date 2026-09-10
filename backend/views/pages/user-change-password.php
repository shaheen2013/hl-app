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
			<div class="col-lg-8 col-lg-offset-2">
				<form method="post" action="<?php echo $urlTree['user-change-password']?>">
					<?php if($tienePassAnterior){?>
					<div class="form-group">
						<label for="change-password"><?php echo $UserProfileLang['Type your actual password'] ?></label>
						<input type="password" class="form-control" name="oldPass" id="change-password" placeholder="Your old password...">
					</div>
					<?php }?>
					<div class="form-group">
						<label for="change-new-password"><?php echo $UserProfileLang['Type your new password'] ?></label>
						<input type="password" class="form-control" name="newPass" id="change-new-password" placeholder="Your new password...">
					</div>
					<div class="form-group">
						<label for="r-change-new-password"><?php echo $UserProfileLang['Retype your new password'] ?></label>
						<input type="password" class="form-control" name="rNewPass" id="r-change-new-password" placeholder="Retype new password...">
					</div>
					<input type="submit" class="btn btn-lg btn-success" value="<?php echo $UserProfileLang['Change your password'] ?>" name="changePass" />
				</form>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>