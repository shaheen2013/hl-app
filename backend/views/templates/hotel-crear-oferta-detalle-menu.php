<?php include LANG . $_SESSION['userLang'] . '/hotel-crear-oferta-detalle-menu.php' ?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li class="hcdo-previous-step"><a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" title="<?php echo $HotelCrearOfertaDetalleMenuLang['Basic info text'] ?>"><?php echo $HotelCrearOfertaDetalleMenuLang['Basic info'] ?></a></li>
	<li class="hcdo-details"><a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] . (!empty($_SESSION['id_oferta']) ? '/?id=' . $_SESSION['id_oferta'] : '')?>" title="<?php echo $HotelCrearOfertaDetalleMenuLang['campaign details text'] ?>"><?php echo $HotelCrearOfertaDetalleMenuLang['campaign details'] ?></a></li>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	<li><a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" title="<?php echo $HotelCrearOfertaDetalleMenuLang['Basic info text'] ?>"><?php echo $HotelCrearOfertaDetalleMenuLang['Basic info'] ?></a></li>
	<li><a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>" title="<?php echo $HotelCrearOfertaDetalleMenuLang['campaign details text'] ?>"><?php echo $HotelCrearOfertaDetalleMenuLang['campaign details'] ?></a></li>
  </ul>
</div>