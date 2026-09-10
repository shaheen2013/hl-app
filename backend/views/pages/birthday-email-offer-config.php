<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} 
include_once LANG .$_SESSION['userLang']. '/birthday-email-offer-config.php'; 
?>

<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
		<div class="top-bar">
			<?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
		</div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-birthday-cake" aria-hidden="true"></i> <?php echo $birthdayConfigLang['Birthday email offer selection'] ?></h1>
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
                    <?php if($_SESSION['permisos']['birthday_emails'] == 1){ ?>
					<div class="col-lg-12">
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
								<?php echo $birthdayConfigLang['Select the offer to be given on Birthdays'] ?>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="input-group">
											<select name="birthdayoffer" class="form-control">
												<option value="0"><?php echo $birthdayConfigLang['Not selected...'] ?></option>
												<?php foreach($hotelOfferList as $offer): ?>
													<option value="<?php echo $offer['id'] ?>" <?php echo($offer['id'] == $birthdayData['oferta_id'] ? 'selected' : '') ?>><?php echo $offer['nombre'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
                                        <br>
                                        <div class="input-group">
                                            <input type="checkbox" name="SendBirthdayWarningOfNonUsers" aria-label="Checkbox for following text input" <?php echo (isset($birthdayData['sendWarningFromNonUsers']) && $birthdayData['sendWarningFromNonUsers'] == 1) ? "checked" : "" ?>>
                                            <?php echo $birthdayConfigLang['Send birthday offers to no clients'] ?>
                                        </div>
									</div>
								</div>
							</div>
						</div>
					</div>
                    <?php } ?>
					<div class="col-lg-12">
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
								<?php echo $birthdayConfigLang['Notifications config'] ?>
							</div>

                            <div class="panel-body">

                                <div class="input-group">
                                    <label for="birthdayAlertRange"><?php echo $birthdayConfigLang['Birthday notification range'] ?></label>
                                    <input id="birthdayAlertRange" type="number" name="birthdayAlertRange" class="form-control" placeholder="" value="<?php echo $birthdayAlertRange ?>">
                                </div>
                            </div>
							<div class="panel-body">

                                    <div class="input-group">
                                        <input type="text" name="birthdayAlertEmails" class="form-control" placeholder="" value="<?php echo (isset($birthdayAlertEmails)) ? $birthdayAlertEmails : "" ?>">
                                        <span class="input-group-addon"><label>
                                        <input type="checkbox" name="birthdayAlert" aria-label="Checkbox for following text input" <?php echo (isset($birthdayAlarm['birthday_alarm']) && $birthdayAlarm['birthday_alarm'] == 1) ? "checked" : "" ?>>  <?php echo $birthdayConfigLang['Birthday alert'] ?></label>
                                    </span>
                                    </div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<button type="submit" class="btn btn-success" name="hotelConfirmButton"><?php echo $birthdayConfigLang['Save selected'] ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>