<?php include LANG . $_SESSION['userLang'] . '/referrals-top-menu.php'?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li><a href="<?php echo $urlTree['clients'] ?>/" class="<?php echo $currentSubPage == 'clients-management' ? 'top-bar-active' : '' ?>"><?php echo $referralsTopMenu['Clients'] ?></a></li>
	<?php if (isset($_SESSION['h_logueado'])) {?>
		<li><a href="<?php echo $urlTree['referrals'] ?>/" class="<?php echo $currentSubPage == 'referrers-management' ? 'top-bar-active' : '' ?>"><?php echo $referralsTopMenu['View all referrers'] ?></a></li>
		<li><a href="<?php echo $urlTree['referrers'] ?>/" class="<?php echo $currentSubPage == 'referrals-management' ? 'top-bar-active' : '' ?>"><?php echo $referralsTopMenu['View all referrals'] ?></a></li>
		<li><a href="<?php echo $urlTree['stay-share'] . '/' . $guidHotel . '/' ?>" target="_blank"><?php echo $referralsTopMenu['View user sharing page'] ?></a></li>
		<!-- <li><a href="<?php echo $urlTree['referrals-home'] ?>" class="<?php echo $currentSubPage == 'statistics' ? 'top-bar-active' : '' ?>"><?php echo $referralsTopMenu['View statistics'] ?></a></li> -->
	<?php }?>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
	<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
	<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
		<li><a href="<?php echo $urlTree['clients'] ?>/"><?php echo $referralsTopMenu['Clients'] ?></a></li>
		<?php if (isset($_SESSION['h_logueado'])) {?>
			<li><a href="<?php echo $urlTree['referrals'] ?>/"><?php echo $referralsTopMenu['View all referrers'] ?></a></li>
			<li><a href="<?php echo $urlTree['referrers'] ?>/"><?php echo $referralsTopMenu['View all referrals'] ?></a></li>
			<li><a href="<?php echo $urlTree['stay-share'] . '/' . $guidHotel . '/' ?>" target="_blank"><?php echo $referralsTopMenu['View user sharing page'] ?></a></li>
			<!-- <li><a href="<?php echo $urlTree['referrals-home'] ?>"><?php echo $referralsTopMenu['View statistics'] ?></a></li> -->
		<?php }?>
	</ul>
</div>

<?php 
include TEMPLATES . 'hotel-suggest.php'; ?>