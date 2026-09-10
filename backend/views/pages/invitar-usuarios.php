<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/invitar-usuarios.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'invite-users-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-envelope-o"></i> <?php echo $InviteUsersLang['invite new guests'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
				<div class="col-lg-8">
					<form id="guestList" action="<?php echo $urlTree['invitar-usuarios-2'] ?>" method="POST" name="fillInviteListForm" enctype="multipart/form-data" >
						<div class="alert alert-info">
							<div class="pull-left">
								<i class="fa fa-lightbulb-o fa-3x pr"></i>
							</div>
							<p>
								<?php if($_SESSION['permisos']['LY'] == '1'){
									echo $InviteUsersLang['invite new guests text'];
								} else {
									echo $InviteUsersLang['invite new guests text referral'];
								}?>
							</p>
						</div>
						<label for="inviteList"><?php echo $InviteUsersLang['Maximum 1.000 lines per import'] ?></label>
						<textarea name="inviteList" class="form-control" id="inviteList" cols="30" rows="8" placeholder="<?php echo($_SESSION['permisos']['LY'] == '1' ? $InviteUsersLang['in box text example'] : $InviteUsersLang['in box text example referral']) ?>"></textarea>
						<label class="mt2" for="inviteList"><?php echo $InviteUsersLang['Maximum 50.000 records per import'] ?></label>
						<input type="file" class="form-control sendFile" name="inviteUsersFile" id="inviteUsersFile">
						<div class="row mt2">
							<div class="col-md-12">
								<input type="submit" value="<?php echo $InviteUsersLang['generate list button'] ?>" class="btn btn-primary" name="hotelConfirmButton">
								<p class="help-block"><?php echo $InviteUsersLang['no worries we are not sending anything yet'] ?></p>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php include TEMPLATES . 'createInvitesModal.php'; ?>
<script>
	$(document).ready(function(){
		$('.sendInviteBtn').button();

		$('.btnVideotutorial').click(function(e){
			e.preventDefault();
			$('#createInvitesModal').modal({
				show: true
			});
		})
	});
</script>