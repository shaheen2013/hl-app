<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-survey-2.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="user-utility-bar">
	<h1 class="pull-left"><i class="fa fa-check-square-o"></i> <?php echo $UserSurvey2Lang['Give your opinion about'] ?> <strong><?php echo $arrayDatosHotel['nombre_hotel'] ?></strong></h1>
</div>
<div class="col-lg-12" id="fullContainer">
	<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 text-center mb4">
		<h1><?php echo $UserSurvey2Lang['¿Que puntuación y comentarios le darías a'] ?> <strong><?php echo $arrayDatosHotel['nombre_hotel'] ?></strong> </h1>
	</div>
	<form action="<?php echo $urlTree['user-survey-2'] ?>" method="POST" class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 ">
		<div class="row mb2">
			<div class="mb2 mt4">
				<div class="slider sliderRating mt4"></div>
			</div>
		</div>
		<div class="row">
			<div class="mb2">
				<label for="positiveComment"><i class="fa fa-thumbs-o-up"></i>  <?php echo $UserSurvey2Lang['Comentarios positivos'] ?></label>
				<textarea name="positiveComment" class="form-control" rows="3" placeholder="<?php echo $UserSurvey2Lang['Escribe aquí tus comentarios positivos...'] ?>"></textarea>
			</div>
			<div class="mb2">
				<label for="negativeComment"><i class="fa fa-thumbs-o-down"></i>  <?php echo $UserSurvey2Lang['Comentarios negativos'] ?></label>
				<textarea name="negativeComment" class="form-control" rows="3" placeholder="<?php echo $UserSurvey2Lang['Escribe aquí tus comentarios negativos...'] ?>"></textarea>
			</div>
		</div>
		<input type="hidden" name="rating" id="hotelRating" value="5">
		<div class="row text-center">
			<input type="hidden" name="id" value="<?php echo $id_encuesta ?>"
			<div class="mb2">
				<input name="save-survey" type="submit" class="btn btn-lg btn-success" value="<?php echo $UserSurvey2Lang['Enviar opinión'] ?>">
			</div>
		</div>
	</form>
</div>
<script>
	jQuery(document).ready(function($) {
		var initialValue = 5;
		var sliderTooltip = function(event, ui) {
			var curValue = ui.value || initialValue;
			var tooltip = '<div class="rangeValue">' + curValue + '</div>';
			$('.ui-slider-handle').html(tooltip);
			$('#hotelRating').val(curValue);
		}
		$('.sliderRating').slider({
			range: "min",
			min:0.1,
			max:10,
			value:5,
			step:0.1,
			create:sliderTooltip,
			slide:sliderTooltip
		});
	});
</script>