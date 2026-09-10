<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-change-password.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-asterisk"></i> <?php echo $HotelChangePassLang['Password change'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
				<div class="col-lg-4 col-lg-offset-4">
						<h2 class="text-center mb"><?php echo $HotelChangePassLang['Change your password text'] ?></h2>
						<form role="form" class="mt2" method="post" enctype="multipart/form-data" >
							<div class="col-lg-12 col-md-8">
								<label for="password"><?php echo $HotelChangePassLang['old password'] ?></label>
								<input type="password" class="form-control" id="password" name="passwordOld" required >
							</div>
							<div class="col-lg-12 col-md-8 mt2">
								<label for="password"><?php echo $HotelChangePassLang['New password'] ?></label>
								<input type="password" class="form-control" id="password" name="password" required >
								<p class="help-block"><small><?php echo $HotelChangePassLang['Change pass (6-18 characters)'] ?></small></p>
							</div>
							<div class="col-lg-12 col-md-8">
								<label for="passwordR"><?php echo $HotelChangePassLang['Confirm your new password'] ?></label>
								<input type="password" class="form-control" id="passwordR" name="passwordR" required >
							</div>
							<div class="clearfix"></div>
							<div class="col-lg-12 col-md-8 mt2 ">
								<input type="submit" value="<?php echo $HotelChangePassLang['Save changes'] ?>" class="btn btn-success btn-lg btn-block" name="hotelConfirmButton">
							</div>
						</form>
					<div class="clearfix"></div>
				</div>
		</div>
	</div>
</div>