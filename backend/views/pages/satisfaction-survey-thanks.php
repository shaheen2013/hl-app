<?php include_once LANG . $_SESSION['userLang'] . '/satisfaction-survey-thanks.php'; ?>
<div class="stay-overlayer"></div>

<!-- <img class="ss-bg-img"src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel ?>/fotoBg/big_<?php echo $datosHotel['fotoBg'] ?>" alt="Background image of hotel" class="rs-bg"> -->
<img class="ss-bg-img" src="<?php echo $datosHotel['fotoBg'] ?>" alt="Background image of hotel" class="rs-bg">

<div class="satisfaction-survey-content text-center vertical-align">

    <img src="<?php echo $datosHotel['logo'] ?>"alt="Hotel logo" class="img-thumbnail img-circle animated zoomIn anim1" width="100" height="100">

	<div class="satisfaction-text">
		<h3 class="animated zoomIn anim2"><?php echo $user_name . $sSTLang['Muchas gracias por realizar la encuesta!'] ?></h3>
		<p class="animated zoomIn anim3">
			<?php echo ($user_gender=='male' ? $sSTLang['Esperamos verlo pronto en nuestro hotel'] : $sSTLang['Esperamos verla pronto en nuestro hotel'] ). ' ' . $datosHotel['hotelName'] . ' ' . $sSTLang['Pronto'] . "!" ?>
		</p>
	</div>

</div>