<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<?php include LANG . $_SESSION['userLang'] . '/edit-staff.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-folder-open-o"></i> Edit Staff</h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-6 col-lg-push-3 pad-bottom">
				<form id="edit-staff">
					<label class="mt2" for="staffLang"><?php echo $editStaffLang['staff lang'] ?></label>
					<select name="staffLang" id="staffLang" class="form-control">
						<option value="es" <?php echo $userLang === "es" ? "selected" : "" ?>><?php echo $editStaffLang['es'] ?></option>
						<option value="en" <?php echo $userLang === "en" ? "selected" : "" ?>><?php echo $editStaffLang['en'] ?></option>
					</select>
					<label class="mt2" for="mfaRequired"><?php echo $editStaffLang['mfa'] ?></label>
					<select name="mfaRequired" id="mfaRequired" class="form-control">
						<option value="false" <?php echo $userMfaRequired === "false" || !$userMfaRequired ? "selected" : "" ?>><?php echo $editStaffLang['no'] ?></option>
						<option value="true" <?php echo $userMfaRequired === "true" ? "selected" : "" ?>><?php echo $editStaffLang['yes'] ?></option>
					</select>
					<label class="mt2" for="staffRole"><?php echo $editStaffLang['Select Role'] ?></label>
					<select name="staffRole" id="staffRole" class="form-control">
					<?php foreach ($roles as $rol) { ?>
						<!-- Hide the account admin option if it is independent brand -->
						<?php if (!(empty($arrayHotelesCadena) && $rol['id'] == 1)) { ?>
							<option value="<?php echo $rol['id'] ?>" <?php if ($rol['id'] == $actual_staff_role['id_role']) { echo 'selected'; } ?>><?php echo $rol['role'] ?></option>
						<?php } ?>
					<?php } ?>
					</select>
					<?php if (!empty($arrayHotelesCadena) ) {?>
						<div id="hotelSelector">
							<label class="mt2"><?php echo $editStaffLang['Select Hotels'] ?></label>
							<select name="staff_hotel[]" id="staff_hotel" class="form-control selectpicker" multiple required title="Choose one or more hotels" data-size="5">
							<?php foreach ($arrayHotelesCadena as $hotel) { ?>
								<option value="<?php echo $hotel['id'] ?>" <?php if (in_array_r($hotel['id'], $staff_hotels)) { echo 'selected'; } ?>><?php echo $hotel['nombre'] ?></option>
							<?php } ?>
							</select>
						</div>
					<?php } ?>
					<input type="hidden" id="id" name="id" value="<?php echo $staff_id ?>">
					<input type="hidden" id="previousRole" name="previousRole" value="<?php echo $actual_staff_role['id_role'] ?>">
					<input type="hidden" id="staffEmail" name="staffEmail" value="<?php echo $actual_staff_role['email'] ?>">
					<input type="submit" class="btn btn-primary mt2" value="Save changes">
				</form>
			</div>
		</div>
	</div>
</div>

<?php
	function in_array_r($item , $array){
		return preg_match('/"'.preg_quote($item, '/').'"/i' , json_encode($array));
	}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>

<script>
	function toggleHotelSelection() {
		$('#staffRole').val() === "1" ? $('#hotelSelector').hide() : $('#hotelSelector').show();
		$('#staffRole').val() === "1" ? $('#staff_hotel').removeAttr('required') : $('#staff_hotel').prop('required', true);
    }

	<?php if (array_get($_SESSION, 'c_logueado')) {?>
		toggleHotelSelection();
		$("#staffRole").on("change", function() { 
			toggleHotelSelection();
		});
	<?php } ?>
</script>