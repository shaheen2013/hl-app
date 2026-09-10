<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/chain-email.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'chain-management-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-cog"></i> <?php echo $chainEmailLang['Change email and password'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-sm-6 col-sm-offset-3 mt2">
			<h3><?php echo $chainEmailLang['Change chain emails'] ?></h3>
				<form method="post">
					<div class="form-group">
						<label for="account-email"><?php echo $chainEmailLang['Chain manager email'] ?></label>
						<input type="email" class="form-control" id="account-email" name="account-email" value="<?php echo $datosHotelCE['email'] ?>" placeholder="<?php echo $chainEmailLang['Email for your login'] ?>" required >
						<p class="help-block"><small><?php echo $chainEmailLang['This email is for login purposes'] ?></small></p>
					</div>
					<div class="form-group">
						<label for="sending-email"><?php echo $chainEmailLang['Chain email for communications'] ?></label>
						<input type="email" class="form-control" id="sending-email" name="sending-email" value="<?php echo $datosHotelCE['email_envio'] ?>" placeholder="<?php echo $chainEmailLang['Email for sending communications'] ?>" required >
						<p class="help-block"><small><?php echo $chainEmailLang['User will receive communications from this email'] ?></small></p>
					</div>
					<input name="chain-email" type="submit"  name="" value="<?php echo $chainEmailLang['Save'] ?>" class="btn btn-success" />
				</form>
				<hr/>
				<h3><?php echo $chainEmailLang['Change your chain password'] ?></h3>
				<form method="post">
					<div class="form-group">
						<label for="old-pass"><?php echo $chainEmailLang['Your current password'] ?></label>
						<input type="password" class="form-control" id="old-pass" name="old-pass" required >
					</div>
					<div class="form-group">
						<label for="new-pass"><?php echo $chainEmailLang['Your new password'] ?></label>
						<input type="password" class="form-control" id="new-pass" name="new-pass" required >
						<p class="help-block"><small><?php echo $chainEmailLang['Change pass (6-18 characters)'] ?></small></p>
					</div>
					<div class="form-group">
						<label for="re-new-pass"><?php echo $chainEmailLang['Re-type your new password'] ?></label>
						<input type="password" class="form-control" id="re-new-pass" name="re-new-pass" required >
						<p class="help-block"><small><?php echo $chainEmailLang['Change pass (6-18 characters)'] ?></small></p>
					</div>
					<input name="chain-pass" type="submit" value="<?php echo $chainEmailLang['Save'] ?>" class="btn btn-success" />
				</form>
				<br>
			</div>
		</div>
	</div>

