<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<div class="overlayer preStayOverlayer">
	<div id="spinner"></div>
	<p class="connectingFB"><?php echo $hotelBookingShareLang['Connecting with facebook'] ?></p>
</div>
<?php
if(!empty($datosHotel)){
      //Incluye la plantilla de iframe correcta
	include TEMPLATES . 'hotel-booking-share-'.$datosHotel['iframe_style'].'.php';
}else{
	include TEMPLATES . 'hotel-booking-share-1.php';
      //Incluye la plantilla de iframe correcta
}
?>
<div id="fb-root"></div>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script src="<?php echo DIR_JS ?>spiner.min.js"></script>
<?php include LIB . 'facebook-iframe-dialog.php' ?>