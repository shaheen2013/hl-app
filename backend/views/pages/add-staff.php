
<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/add-staff.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'staff-management-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-folder-open-o"></i> <?php echo $lang['Add staff'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="mainContent" id="fullContainer">
		<div class="col-lg-6 col-lg-push-3 mt2 pad-bottom">
		<form action="<?php echo $url['dir1'] ?>" method="POST">
			<label for="staffEmail"><?php echo $lang['Insert staff member email'] ?></label>
            <input type="email" required="required" id="staffEmail" class="form-control" name="staffEmail"/>
            <label class="mt2" for="staffName"><?php echo $lang['Insert staff member name'] ?></label>
			<input type="text" required="required" id="staffName" class="form-control" name="staffName"/>
			<label class="mt2" for="staffLang"><?php echo $lang['staff lang'] ?></label>
			<select name="staffLang" id="staffLang" class="form-control">
				<option value="es"><?php echo $lang['es'] ?></option>
				<option value="en"><?php echo $lang['en'] ?></option>
			</select>
			<label class="mt2" for="mfaRequired"><?php echo $lang['mfa'] ?></label>
			<select name="mfaRequired" id="mfaRequired" class="form-control">
				<option value="false"><?php echo $lang['no'] ?></option>
				<option value="true"><?php echo $lang['yes'] ?></option>
			</select>
			<label class="mt2" for="staffRole"><?php echo $lang['Select the role of this staff member'] ?></label>
			<select name="staffRole" id="staffRole" class="form-control">
			<?php foreach ($roles as $rol) { ?>
				<!-- Hide the account admin option if it is independent brand -->
				<?php if (!(empty($arrayHotelesCadena) && $rol['id_role'] == 1)) { ?>
					<option value="<?php echo $rol['id_role'] ?>"><?php echo $rol['role'] ?></option>
				<?php } ?>
			<?php } ?>
			</select>
			<?php if (!empty($arrayHotelesCadena) ) {?>
				<div id="hotelSelector">
					<label class="mt2" for="staffHotel"><?php echo $lang['Select hotel'] ?></label>
					<?php if(isset($_SESSION['c_logueado'])){ ?>
					<select name="staffHotel[]" id="staffHotel" class="form-control selectpicker" data-size="5" multiple required>
						<?php foreach ($arrayHotelesCadena as $hotel) { ?>
						<option value="<?php echo $hotel['id'] ?>"><?php echo $hotel['nombre'] ?></option>
						<?php } ?>
					</select>
					<?php }?>
				</div>
			<?php } ?>

			
			<input type="submit" class="btn btn-primary mt2" value="<?php echo $lang['Add this user to staff'] ?>">
		</form>

		</div>
	</div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>

<script>
	function toggleHotelSelection() {
		$('#staffRole').val() === "1" ? $('#hotelSelector').hide() : $('#hotelSelector').show();
		$('#staffRole').val() === "1" ? $('#staffHotel').removeAttr('required') : $('#staffHotel').prop('required', true);

    }

	<?php if (!empty($arrayHotelesCadena) ) {?>
		toggleHotelSelection();
		$("#staffRole").on("change", function() { 
			toggleHotelSelection();
		});
	<?php } ?>
</script>