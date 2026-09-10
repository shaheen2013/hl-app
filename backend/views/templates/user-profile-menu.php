<?php include LANG . $_SESSION['userLang'] . '/user-profile-menu.php' ?>
<nav class="navbar navbar-default user-navbar" role="navigation">
	<div class="navbar-header">
		<div class="navbar-brand">
			<?php switch ($url['dir1']) {case $urlTree['user-profile']: echo '<i class="fa fa-cog"></i> <strong>'.$UserProfileMenulLang['Basic profile'].'</strong>'; break; case $urlTree['user-profile-2']: echo '<i class="fa fa-cog"></i> <strong>'.$UserProfileMenulLang['Hotel preferences'].'</strong>'; break; case $urlTree['user-profile-4']: echo '<i class="fa fa-cog"></i> <strong>'.$UserProfileMenulLang['Notifications'].'</strong>'; break; case $urlTree['user-change-password']: echo '<i class="fa fa-cog"></i> <strong>'.$UserProfileMenulLang['password'].'</strong>'; break; } ?>
		</div>
	</div>
	<ul class="nav navbar-nav navbar-right pr">
		<li><a href="<?php echo $urlTree['user-profile'] ?>" title="Basic profile"><span><?php echo $UserProfileMenulLang['Basic profile'] ?></span></a></li>
		<li><a href="<?php echo $urlTree['user-profile-2'] ?>" title="Hotel preferences"><span><?php echo $UserProfileMenulLang['Hotel preferences'] ?></span></a></li>
		<li><a href="<?php echo $urlTree['user-profile-4'] ?>" title="Notifications"><span><?php echo $UserProfileMenulLang['Notifications'] ?></span></a></li>
		<li><a href="<?php echo $urlTree['user-change-password'] ?>" title="Notifications"><span><?php echo $UserProfileMenulLang['password'] ?></span></a></li>
	</ul>
</nav>