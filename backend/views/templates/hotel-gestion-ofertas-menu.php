<?php include LANG . $_SESSION['userLang'] . '/hotel-gestion-ofertas-menu.php' ?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li><a href="<?php echo $urlTree['hotel-listado-cupones'] ?>" title="<?php echo $HotelGestionOfertasMenuLang['ver listado de cupones'] ?>" class="<?php echo $currentSubPage == 'voucher-management' ? 'top-bar-active' : ''?>"><?php echo $HotelGestionOfertasMenuLang['ver listado de cupones'] ?></a></li>
	 <?php if(empty($_SESSION['staff_logueado'])){ ?>
    
    <?php if(isset($_SESSION['c_logueado'])){?>
    <li><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/ref-chain/" title="<?php echo $HotelGestionOfertasMenuLang['referral campaigns chain text'] ?>" class="<?php echo $currentSubPage == 'offer-management' ? 'top-bar-active' : ''?>"><?php echo $HotelGestionOfertasMenuLang['referral campaigns chain'] ?></a></li>
    <?php } else { ?>
    <li><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/ref/" title="<?php echo $HotelGestionOfertasMenuLang['referral campaigns text'] ?>"><?php echo $HotelGestionOfertasMenuLang['referral campaigns'] ?></a></li>
    <?php } ?>
	<li><a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>" title="<?php echo $HotelGestionOfertasMenuLang['create new campaign text'] ?>"><i class="rubies rubix1"> rubies</i> <?php echo $HotelGestionOfertasMenuLang['create new campaign'] ?></a></li>
    <li><a href="<?php echo $urlTree['referral-goals'] ?>" title="<?php echo $HotelGestionOfertasMenuLang['referral goals setup'] ?>" class="<?php echo $currentSubPage == 'goals-management' ? 'top-bar-active' : ''?>"><?php echo $HotelGestionOfertasMenuLang['referral goals setup'] ?></a></li>
    <?php } ?>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	<li><a href="<?php echo $urlTree['hotel-listado-cupones'] ?>" title="<?php echo $HotelGestionOfertasMenuLang['ver listado de cupones'] ?>"><?php echo $HotelGestionOfertasMenuLang['ver listado de cupones'] ?></a></li>
    <?php if(empty($_SESSION['staff_logueado'])){ ?>
    <?php if ($_SESSION['permisos']['LY'] == '1'){ ?>
	<li><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/ret/" title="<?php echo $HotelGestionOfertasMenuLang['retention campaigns text'] ?>"><?php echo $HotelGestionOfertasMenuLang['retention campaigns'] ?></a></li>
    <?php } ?>
    <li><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/ref/" title="<?php echo $HotelGestionOfertasMenuLang['referral campaigns text'] ?>"><?php echo $HotelGestionOfertasMenuLang['referral campaigns'] ?></a></li>
    <?php if(isset($_SESSION['c_logueado'])){?>
    <li><a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/ref-chain/" title="<?php echo $HotelGestionOfertasMenuLang['referral campaigns chain text'] ?>"><?php echo $HotelGestionOfertasMenuLang['referral campaigns chain'] ?></a></li>
    <?php } ?>
	<li><a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" title="<?php echo $HotelGestionOfertasMenuLang['create new campaign text'] ?>"><i class="rubies rubix1"> rubies</i> <?php echo $HotelGestionOfertasMenuLang['create new campaign'] ?></a></li>
    <li><a href="<?php echo $urlTree['referral-goals'] ?>" title="<?php echo $HotelGestionOfertasMenuLang['referral goals setup'] ?>"><?php echo $HotelGestionOfertasMenuLang['referral goals setup'] ?></a></li>
    <?php } ?>
  </ul>
</div>

<?php include TEMPLATES . 'hotel-suggest.php'; ?>