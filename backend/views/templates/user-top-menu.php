<?php include LANG . $_SESSION['userLang'] . '/user-top-menu.php' ?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li><a href="<?php echo $urlTree['user'] ?>/<?php echo $url['dir2'].'/'.$url['dir3'] ?>"><?php echo $userTopMenulang['user details'] ?></a></li>
	<li><a href="<?php echo $urlTree['user-preferences'] ?>/<?php echo $url['dir2'].'/'.$url['dir3'] ?>"><?php echo $userTopMenulang['user preferences'] ?></a></li>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
	<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
	<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
		<li><a href="<?php echo $urlTree['user'] ?>/<?php echo $url['dir2'].'/'.$url['dir3'] ?>"><?php echo $userTopMenulang['user details'] ?></a></li>
		<li><a href="<?php echo $urlTree['user-preferences'] ?>/<?php echo $url['dir2'].'/'.$url['dir3'] ?>"><?php echo $userTopMenulang['user preferences'] ?></a></li>
	</ul>
</div>