<?php include LANG . $_SESSION['userLang'] . '/datamatch-list-top-menu.php' ?>

<?php if (!empty($_SESSION['h_logueado']) || (!empty($_SESSION['staff_id_hotel']) && $_SESSION['staff_role'] == 3)) { ?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li><a href="<?php echo $urlTree['datamatch-list'] ?>/" class="<?php echo $currentSubPage == 'datamatch-list' ? 'top-bar-active' : ''?>"><?php echo $datamatchTopMenu['Datamatch List'] ?></a></li>
	<?php if (array_get($url, 'dir1')=='datamatch-users') { ?>
		<li><a href="<?php echo $urlTree['datamatch-users']?>/" class="<?php echo $currentSubPage == 'datamatch-users' ? 'top-bar-active' : ''?>"><?php echo $datamatchTopMenu['Datamatch Users'] ?></a></li>
	<?php } ?>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
	<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
	<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
		<li><a href="<?php echo $urlTree['datamatch-list'] ?>/"><?php echo $datamatchTopMenu['Datamatch List'] ?></a></li>
		<?php if (array_get($url, 'dir1')=='datamatch-users') { ?>
				<li><a href="<?php echo $urlTree['datamatch-users'] ?>/"><?php echo $datamatchTopMenu['Datamatch Users'] ?></a></li>		
		<?php } ?>
	</ul>
</div>

<?php } ?>
<?php include TEMPLATES . 'hotel-suggest.php'; ?>