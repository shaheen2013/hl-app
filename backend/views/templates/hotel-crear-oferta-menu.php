<?php include LANG . $_SESSION['userLang'] . '/hotel-crear-oferta-menu.php' ?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li><a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" title="<?php echo $HotelCrearOfertaMenuLang['Basic info text'] ?>"><?php echo $HotelCrearOfertaMenuLang['Basic info'] ?></a></li>
	<li><a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] . (!empty($_SESSION['id_oferta']) ? '/?id=' . $_SESSION['id_oferta'] : '') ?>" title="<?php echo $HotelCrearOfertaMenuLang['campaign details text'] ?>"><?php echo $HotelCrearOfertaMenuLang['campaign details'] ?></a></li>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	<li><a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" title="<?php echo $HotelCrearOfertaMenuLang['Basic info text'] ?>"><?php echo $HotelCrearOfertaMenuLang['Basic info'] ?></a></li>
	<li><a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] . (!empty($_SESSION['id_oferta']) ? '/?id=' . $_SESSION['id_oferta'] : '')?>" title="<?php echo $HotelCrearOfertaMenuLang['campaign details text'] ?>"><?php echo $HotelCrearOfertaMenuLang['campaign details'] ?></a></li>
  </ul>
</div>


