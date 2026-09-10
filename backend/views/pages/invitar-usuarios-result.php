<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/invitar-usuarios-result.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-check-circle"></i> <?php echo $InvitarUsuariosResultlLang['Well done'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-6 col-lg-offset-3 text-center">
				<i class="fa fa-check-circle fa-6x verde"></i>
				<h2><?php echo $InvitarUsuariosResultlLang['All ready to send out invites to your guests!'] ?></h2>
				<a href="<?php echo $urlTree['gestion-usuarios'] ?>" title="panel de control" class="btn btn-lg btn-primary mt"><?php echo $InvitarUsuariosResultlLang['Go back to guests database button'] ?></a>
			</div>
		</div>
	</div>
</div>