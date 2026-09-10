<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-security-preferences.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-lock"></i> <?php echo $HotelSecurityPreferencesLang['Notifications preferences'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="mainContent" id="fullContainer">
			<div class="col-lg-8 mt2">
				<form action="<?php echo $url['dir1'] ?>" method="post">
					
					<div class="col-lg-12">
						
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
								<?php echo $HotelSecurityPreferencesLang['Días desde Stay hasta la notificación de Review'] ?>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-3">
										<div class="input-group">
											<input type="text" name="diasEnvioReview" id="inputDias" class="form-control" value="<?php echo $notificaciones['diasEnvioReview']?>">
											<div class="input-group-addon">días</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="checkbox col-lg-12 mt2">
							<label>
								<input type="checkbox" value="1" <?php if (!empty($notificaciones['notif']) && $notificaciones['notif'] == 1){ echo 'checked="checked"';} ?> name="notificaciones"> <?php echo $HotelSecurityPreferencesLang['I would like to receive important notifications and news from Hotelinking'] ?>
							</label>

							<div class="row">
								<input type="submit" value="<?php echo $HotelSecurityPreferencesLang['Save changes'] ?>" class="btn btn-success mt2" name="hotelConfirmButton">
							</div>
						</div>
					</div>

				</form>
			</div>
		</div>

	</div>
</div>
</div>