<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-profile-2.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="container">
	<div class="user-utility-bar mb15">
		<?php include TEMPLATES . 'user-profile-menu.php'; ?>
	</div>
</div>
	<div class="container" id="fullContainer">
		<div class="panel panel-default">
		<div class="panel-body">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="col-lg-12 mb text-center">
				<h3><?php echo $UserProfile2Lang['Hotel preferences text'] ?></h3>
			</div>
			<form role="form" class="mt2" method="post">
				<div class="row mb2">
					<div class="col-lg-12 mb2">
						<label for="sliderStars"><?php echo $UserProfile2Lang['Categoría (estrellas):'] ?> </label><input class="noBorder mb col-xs" type="text" id="sliderStars" disabled value="entre <?php echo $arrayUserProfile2['minEstrellas']; ?> y <?php echo $arrayUserProfile2['maxEstrellas']; ?> estrellas" name="hotelCategory">
						<div class="slider sliderStars"></div>
					</div>
				</div>
				<div class="row mb2">
					<div class="col-lg-12 mb2">
						<label for="sliderPriceRange"><?php echo $UserProfile2Lang['Rango de precios:'] ?> </label><input class="noBorder mb col-xs" type="text" id="sliderPriceRange" disabled value="€ <?php echo $arrayUserProfile2['rango_inf']; ?> - € <?php echo $arrayUserProfile2['rango_sup']; ?>" name="hotelPriceRange">
						<div class="slider sliderPriceRange"></div>
					</div>
				</div>
				<div class="row mb2">
					<div class="col-lg-6">
						<label for="hotelType"><?php echo $UserProfile2Lang['Tipo de hotel preferido'] ?> <small><?php echo $UserProfile2Lang['(puede seleccionar varios)'] ?></small></label>
						<select name="hotelType[]" id="hotelType" class="form-control" multiple>
							<?php foreach ($arrayTiposHotel as $hotel) { ?>
							<option value="<?php echo $hotel['id_tipo_hotel'];?>"
								<?php
								if ($arrayUserTiposHotel10[''.$hotel['id_tipo_hotel'].''] == 1){
									echo 'selected="selected"';
								}
								?>
								>
								<?php echo $hotel['tipo'];?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="col-lg-6">
						<label for="hotelDecoration"><?php echo $UserProfile2Lang['Decoración preferida'] ?> <small><?php echo $UserProfile2Lang['(puede seleccionar varios)'] ?></small></label>
						<select name="hotelDecoration[]" id="hotelDecoration" class="form-control" multiple>
							<?php foreach ($arrayTiposDecoracion as $hotel) { ?>
							<option value="<?php echo $hotel['id_decoracion'];?>"
								<?php
								if ($arrayUserDecoraciones10[''.$hotel['id_decoracion'].''] == 1){
									echo 'selected="selected"';
								}
								?>
								>
								<?php echo $hotel['decoracion'];?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="col-lg-12 mt2">
						<div class="alert alert-info">
							<div class="pull-left">
								<i class="fa fa-lightbulb-o pr"></i>
							</div>
							<p><?php echo $UserProfile2Lang['Seleccionar varios nota aviso'] ?></a></p>
						</div>
					</div>
				</div>
				<div class="row mb2">
					<div class="col-lg-12">
						<p><strong><?php echo $UserProfile2Lang['Tipos de habitaciones'] ?></strong></p>
						<?php foreach ($arrayTiposHabHotel as $hab) { ?>
						<div class="checkbox col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<label>
								<input type="checkbox" value="<?php echo $hab['id_tipo_hab']; ?>" name="roomType[]"
								<?php
								if ($arrayUserTiposHab10[''.$hab['id_tipo_hab'].''] == 1){
									echo 'checked="checked"';
								}
								?>
								> <?php echo $hab['tipo_hab']; ?>
							</label>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="row mb2">
					<div class="col-lg-12">
						<p><strong><?php echo $UserProfile2Lang['Extras en las habitaciones'] ?></strong></p>
						<?php foreach ($arrayExtrasHotel as $extra) { ?>
						<div class="checkbox col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<label>
								<input type="checkbox" value="<?php echo $extra['id_extra']; ?>" name="roomExtra[]"
								<?php
								if ($arrayUserExtras10[''.$extra['id_extra'].''] == 1){
									echo 'checked="checked"';
								}
								?>
								> <?php echo $extra['extra']; ?>
							</label>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="row mb2">
					<div class="col-lg-12">
						<p><strong><?php echo $UserProfile2Lang['Instalaciones y servicios del hotel'] ?></strong></p>
						<?php foreach ($arrayServiciosHotel as $servicio) { ?>
						<div class="checkbox col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<label>
								<input type="checkbox" value="<?php echo $servicio['id_servicio']; ?>" name="hotelServices[]"
								<?php
								if ($arrayUserServicios10[''.$servicio['id_servicio'].''] == 1){
									echo 'checked="checked"';
								}
								?>
								> <?php echo $servicio['servicio']; ?>
							</label>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-lg-12 mb">
						<input name="save" type="submit" class="btn btn-success btn-lg mt2 pull-left"value="<?php echo $UserProfile2Lang['Confirmar datos'] ?>">
					</div>
				</div>
				<input type="hidden" name="minEstrellas" id="minEstrellas" value="<?php echo $arrayUserProfile2['minEstrellas']; ?>">
				<input type="hidden" name="maxEstrellas" id="maxEstrellas" value="<?php echo $arrayUserProfile2['maxEstrellas']; ?>">
				<input type="hidden" name="minRango" id="minRango" value="<?php echo $arrayUserProfile2['rango_inf']; ?>">
				<input type="hidden" name="maxRango" id="maxRango" value="<?php echo $arrayUserProfile2['rango_sup']; ?>">
			</form>
			<div class="clearfix"></div>
		</div>
		</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {

		$('.sliderStars').slider({
			range: true,
			min: 1,
			max:5,
			values:[<?php echo $arrayUserProfile2['minEstrellas']; ?>,<?php echo $arrayUserProfile2['maxEstrellas']; ?>],
			slide: function (event, ui) {
				$("#sliderStars").val( "entre" + ' ' + ui.values[ 0 ] + " y" + ' ' + ui.values[ 1 ] + ' estrellas' );
				$('#minEstrellas').val(ui.values[ 0 ]);
				$('#maxEstrellas').val(ui.values[ 1 ]);
			}
		});

		$('.sliderPriceRange').slider({
			range: true,
			min: 0,
			max: 5000,
			values:[<?php echo $arrayUserProfile2['rango_inf']; ?>,<?php echo $arrayUserProfile2['rango_sup']; ?>],
			step:10,
			slide: function (event, ui) {
				$("#sliderPriceRange").val( "€" + ' ' + ui.values[ 0 ] + " - €" + ' ' + ui.values[ 1 ] );
				$('#minRango').val(ui.values[ 0 ]);
				$('#maxRango').val(ui.values[ 1 ]);
			}
		});
	});
</script>