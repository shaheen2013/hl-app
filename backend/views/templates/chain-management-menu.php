<?php include LANG . $_SESSION['userLang'] . '/chain-management-menu.php' ?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li><a href="chain-management" title="Chain management" class="<?php echo $currentSubPage == 'add-hotel' ? 'top-bar-active' : ''?>"><?php echo $ChainManagementMenuLang['add new hotels'] ?></a></li>
	<?php if (!empty($_SESSION['c_logueado'])) {//Si está logueado como cadena?>
	<li><a href="chain-details" title="Chain details" class="<?php echo $currentSubPage == 'add-chain-info' ? 'top-bar-active' : ''?>"><?php echo $ChainManagementMenuLang['edit hotel chain info'] ?></a></li>
	<li><a href="<?php echo $urlTree['chain-email'] ?>" title="<?php echo $ChainManagementMenuLang['Edit email and password'] ?>" class="<?php echo $currentSubPage == 'chain-email-management' ? 'top-bar-active' : ''?>"><?php echo $ChainManagementMenuLang['Edit email and password'] ?></a></li>
	<?php if (array_get($_SESSION,'c_eprivacy_responsable')){ ?><li><a href="<?php echo $urlTree['chain-privacy'] ?>" title="Privacy policy management" class="<?php echo $currentSubPage == 'chain-privacy' ? 'top-bar-active' : ''?>"><?php echo $ChainManagementMenuLang['Private policy management'] ?></a></li><?php }?>
	<li><a href="<?php echo $urlTree['archived-hotels'] ?>" title="archived hotels" class="<?php echo $currentSubPage == 'archived-hotels' ? 'top-bar-active' : ''?>"> <?php echo $ChainManagementMenuLang['archived hotels'] ?></a></li>
        <li>  <a href="<?php echo $urlTree['clients-chain-reports-management'] ?>/" class="<?php echo $currentPage == 'clients-chain-reports-management' ? 'top-bar-active' : ''?>"><?php echo $ChainManagementMenuLang['chainReports'] ?></a></li>
	<?php
} ?>
	<?php if (!empty($_SESSION['c_logueado']) && array_get($_SESSION, 'permisos.LY')=='1') {//Si está logueado como cadena?>
    <li><a href="<?php echo $urlTree['loyalty-management'] ?>" title="<?php echo $ChainManagementMenuLang['Loyalty management'] ?>" class="<?php echo $currentSubPage == 'add-chain-info' ? 'top-bar-active' : ''?>"><?php echo $ChainManagementMenuLang['Loyalty management'] ?></a></li>
	<?php
}
    if (!empty($_SESSION['c_logueado']) && !empty($_SESSION['chain']['brand_id'])) {
?>
    <li>
        <a href="<?php echo $urlTree['chain-protocol-management']; ?>" title="<?php echo $ChainManagementMenuLang['protocol management']; ?>" class="<?php echo $currentSubPage == 'chain-protocol-management' ? 'top-bar-active' : ''; ?>">
            <?php echo $ChainManagementMenuLang['protocol management']; ?>
        </a>
    </li>
    <?php
    }
    ?>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	<li><a href="chain-management" title="Chain management"><?php echo $ChainManagementMenuLang['add new hotels'] ?></a></li>
	<li><a href="chain-details" title="Chain details"><?php echo $ChainManagementMenuLang['edit hotel chain info'] ?></a></li>
	 <li><a href="<?php echo $urlTree['chain-email'] ?>" title="<?php echo $ChainManagementMenuLang['Edit email and password'] ?>"><?php echo $ChainManagementMenuLang['Edit email and password'] ?></a></li>
	<?php if (!empty($_SESSION['c_logueado']) && array_get($_SESSION, 'permisos.LY')=='1') {//Si está logueado como cadena?>
    <li><a href="<?php echo $urlTree['loyalty-management'] ?>" title="<?php echo $ChainManagementMenuLang['Loyalty management'] ?>"><?php echo $ChainManagementMenuLang['Loyalty management'] ?></a></li>
  	<?php
} ?>
  </ul>
</div>
<?php
    include TEMPLATES . 'hotel-suggest.php';
?>
