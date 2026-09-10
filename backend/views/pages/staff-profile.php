<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG .$_SESSION['userLang']. '/staff-profile.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php //include top menu ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-user"></i> <?php echo $staffProfileLang['Bienvenido a tu perfil'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
	<div class="col-lg-12">
		<div class="col-lg-4 col-lg-offset-4">
			<div class="col-lg-12 mb">
				<h2><?php echo $staffProfileLang['Desde aquí puedes modificar'] ?></h2>
			</div>
			<form role="form" class="mt2" action="<?php echo $url['dir1'] ?>" method="post">
				<div class="row">
					<div class="col-lg-12">
						<label for="staffname"><?php echo $staffProfileLang['Tu nombre'] ?></label>
						<input type="text" class="form-control" id="staffname" name="staffname" value="<?php echo $datosStaff['nombre'] ?>" disabled>
					</div>
				</div>
                <div class="row mt2">
					<div class="col-lg-12">
						<label for="password"><?php echo $staffProfileLang['Password actual'] ?></label>
						<input type="password" class="form-control" id="password" name="oldPass"  >
					</div>
				</div>
				<div class="row mt2">
					<div class="col-lg-12">
						<label for="password"><?php echo $staffProfileLang['Nueva contraseña'] ?></label>
						<input type="password" class="form-control" id="password" name="password">
						<p class="help-block"><small><?php echo $staffProfileLang['Change pass (6-18 characters)'] ?></small></p>
					</div>
				</div>
				<div class="row ">
					<div class="col-lg-12">
						<label for="repassword"><?php echo $staffProfileLang['Repite nueva contraseña'] ?></label>
						<input type="password" class="form-control" id="repassword" name="repassword"  >
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-lg-5 mb">
					<input type="submit" value="<?php echo $staffProfileLang['Confirma estos datos'] ?>" class="btn btn-success btn-lg mt2 btn-block" name="hotelConfirmButton">
				</div>
			</form>
			<div class="clearfix"></div>
		</div>
	</div>
		</div>
	</div>
</div>