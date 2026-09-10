<?php include LANG . $_SESSION['userLang'] . '/satisfaction-list-top-menu.php' ?>

<?php if (!empty($_SESSION['h_logueado']) || (!empty($_SESSION['staff_id_hotel']) && $_SESSION['staff_role'] == 3)) { ?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li><a href="<?php echo $urlTree['satisfaction-list'] ?>/" class="<?php echo $currentSubPage == 'satisfaction-list' ? 'top-bar-active' : ''?>"><?php echo $satisfactionTopMenu['Satisfaction List'] ?></a></li>
	<li><a href="<?php echo $urlTree['satisfaction-users']?>/" class="<?php echo $currentSubPage == 'satisfaction-users' ? 'top-bar-active' : ''?>"><?php echo $satisfactionTopMenu['Satisfaction Users'] ?></a></li>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
	<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
	<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
		<li><a href="<?php echo $urlTree['satisfaction-list'] ?>/"><?php echo $satisfactionTopMenu['Satisfaction List'] ?></a></li>
		<li><a href="<?php echo $urlTree['satisfaction-users'] ?>/"><?php echo $satisfactionTopMenu['Satisfaction Users'] ?></a></li>
	</ul>
</div>

<?php }

include TEMPLATES . 'hotel-suggest.php'; ?>
