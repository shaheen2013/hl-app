<?php include LANG . $_SESSION['userLang'] . '/check-sidebar.php' ?>
<div id="sidebar-wrapper">
	<div id="user-wrapper">
		<div class="user-image-sb pull-left">
			<img class="img-circle" width="50" height="50" src="<?php echo (!empty($_SESSION['logoHotel']) ? $_SESSION['logoHotel'] : DIR_IMG .  'logo.jpg') ?>" alt="user image">
		</div>
		<p class="pull-left"><?php echo $_SESSION['hotelName']; ?></p>
		<a href="<?php echo $urlTree['staff-profile'] ?>" class="hasTooltip pull-right" data-toggle="tooltip" data-placement="right" title="<?php echo $CheckSidebarLang['Access to your profile'] ?>"><i class="fa fa-cog"></i></a>
	</div>
	<div class="list-group mt2">
		<a href="<?php echo $urlTree['checkin-paso1'] ?>" class="list-group-item"><i class="fa fa-sign-in"></i><span class="pl"><?php echo $CheckSidebarLang['Check in user'] ?></span></a>
		<a href="<?php echo $urlTree['checkout'] ?>" class="list-group-item"><i class="fa fa-sign-out"></i><span class="pl"><?php echo $CheckSidebarLang['Check out user'] ?></span><?php echo($totalCheckins > 0 ? '<span class="badge">'.$totalCheckins.'</span>' : '')?></a>
		<a href="<?php echo $urlTree['logout'] ?>" class="list-group-item"><i class="fa fa-power-off"></i><span class="pl"><?php echo $CheckSidebarLang['Log out'] ?></span></a>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.hasTooltip').tooltip({
			container: "body"
		});
	})
</script>