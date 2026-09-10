<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-profile-2.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-building"></i> <?php echo $HotelProfile2Lang['Hotel profile'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="col-lg-12 mb">
						<div class="row">
							<h4 class="mt4"><?php echo $HotelProfile2Lang['Explicación ficha del hotel'] ?> <a href="<?php echo $urlGuidHotel ?>" title="<?php echo $_SESSION['hotelName'] ?>"><?php echo $HotelProfile2Lang['click here'] ?></a><br>
								<?php echo $HotelProfile2Lang['Explicación ficha del hotel 2'] ?></h4>
						</div>
					</div>
						<form role="form" class="mt2 validation-form" method="post" enctype="multipart/form-data" >
							<div class="row mb2">
								<div class="col-lg-12 mb2">
									<label for="sliderStars"><?php echo $HotelProfile2Lang['Hotel stars'] ?> </label><input class="noBorder mb col-xs" type="text" id="sliderStars" disabled
									value="<?php echo $arrayHotelProfile2['estrellas'] ?>" name="hotelCategory">
									<div class="slider sliderStars"></div>
								</div>
							</div>
							<div class="row mb2">
								<div class="col-lg-12 mb2">
									<label for="sliderPriceRange"><?php echo $HotelProfile2Lang['Price Range'] ?> </label><input class="noBorder mb col-xs" type="text" id="sliderPriceRange" disabled value="€ <?php echo $arrayHotelProfile2['min_rango']; ?> - € <?php echo $arrayHotelProfile2['max_rango']; ?>" name="hotelPriceRange">
									<div class="slider sliderPriceRange"></div>
								</div>
							</div>
							<div class="row mb2">
								<div class="col-lg-12 mb2">
									<label for="sliderRoomRange"><?php echo $HotelProfile2Lang['Number of rooms'] ?> </label><input class="noBorder mb col-xs" type="text" id="sliderRoomRange" disabled value="<?php echo $arrayHotelProfile2['n_habitaciones']; ?>" name="hotelRoomRange">
									<div class="slider sliderRoomRange"></div>
								</div>
							</div>
							<div class="row mb2">
								<div class="col-lg-12 mb2">
									<label for="temporadaSlider"><?php echo $HotelProfile2Lang['Season selector'] ?></label>
									<div class="clearfix mt"></div>
									<div class="col-lg-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Jan'] ?></strong></p>
										<div class="slider eneSlider temporadaSlider center-block" data-month="jan"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Feb'] ?></strong></p>
										<div class="slider febSlider temporadaSlider center-block" data-month="feb"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Mar'] ?></strong></p>
										<div class="slider marSlider temporadaSlider center-block" data-month="mar"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Apr'] ?></strong></p>
										<div class="slider aprSlider temporadaSlider center-block" data-month="apr"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['May'] ?></strong></p>
										<div class="slider maySlider temporadaSlider center-block" data-month="may"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Jun'] ?></strong></p>
										<div class="slider junSlider temporadaSlider center-block" data-month="jun"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Jul'] ?></strong></p>
										<div class="slider julSlider temporadaSlider center-block" data-month="jul"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Aug'] ?></strong></p>
										<div class="slider agoSlider temporadaSlider center-block" data-month="ago"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Sep'] ?></strong></p>
										<div class="slider sepSlider temporadaSlider center-block" data-month="sep"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Oct'] ?></strong></p>
										<div class="slider octSlider temporadaSlider center-block" data-month="oct"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Nov'] ?></strong></p>
										<div class="slider novSlider temporadaSlider center-block" data-month="nov"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
									<div class="col-xs-1 text-center">
										<p class="mb2"><strong><?php echo $HotelProfile2Lang['Dec'] ?></strong></p>
										<div class="slider decSlider temporadaSlider center-block" data-month="dece"></div>
										<p class="mt mb2 temporataText">N/S</p>
									</div>
								</div>
							</div>
							<div class="row mb2">
								<label for="hotelDescription"><?php echo $HotelProfile2Lang['Hotel description'] ?></label>
								<div class="white-module mb2 overflowHidden">
									<div data-target="#hotelDesc" data-role="editor1-toolbar" class="btn-toolbar">
										<div class="btn-group">
											<a title="" data-toggle="dropdown" class="btn btn-default dropdown-toggle" data-original-title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><a data-edit="fontSize 4"><font size="5">Huge</font></a></li>
												<li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
												<li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
											</ul>
										</div>
										<div class="btn-group">
											<a title="" data-edit="bold" class="btn btn-default" data-original-title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
											<a title="" data-edit="italic" class="btn btn-default" data-original-title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
											<a title="" data-edit="underline" class="btn btn-default" data-original-title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
										</div>
										<div class="btn-group">
											<a title="" data-edit="insertunorderedlist" class="btn btn-default" data-original-title="Bullet list"><i class="fa fa-list-ul"></i></a>
											<a title="" data-edit="insertorderedlist" class="btn btn-default" data-original-title="Number list"><i class="fa fa-list-ol"></i></a>
										</div>
										<div class="btn-group">
											<a title="" data-edit="justifyleft" class="btn btn-info btn-default" data-original-title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
											<a title="" data-edit="justifycenter" class="btn btn-default" data-original-title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
											<a title="" data-edit="justifyright" class="btn btn-default" data-original-title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
											<a title="" data-edit="justifyfull" class="btn btn-default" data-original-title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
										</div>
										<div class="btn-group">
											<a title="" data-toggle="dropdown" class="btn dropdown-toggle btn-default" data-original-title="Hyperlink"><i class="fa fa-link"></i></a>
											<div class="dropdown-menu input-append">
												<input type="text" data-edit="createLink" placeholder="URL" class="span2">
												<button type="button" class="btn">Add</button>
											</div>
											<a title="" data-edit="unlink" class="btn btn-default" data-original-title="Remove Hyperlink"><i class="fa fa-chain-broken"></i></a>
										</div>
										<input type="text" x-webkit-speech="" id="voiceBtn" data-edit="inserttext" style="display: none;">
									</div>
									<div class="condicionesTextArea" id="hotelDescription"><?php echo $arrayHotelProfile2['descripcion'] ?></div>
									<div class="textAreaCount mt pull-right"></div>
								</div>
							</div>
							<div class="row mb2">
								<label for="HotelConditions"><?php echo $HotelProfile2Lang['Hotel conditions'] ?></label>
								<div class="white-module mb2 overflowHidden">
									<div data-target="#hotelCond" data-role="editor2-toolbar" class="btn-toolbar">
										<div class="btn-group">
											<a title="" data-toggle="dropdown" class="btn btn-default dropdown-toggle" data-original-title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><a data-edit="fontSize 4"><font size="5">Huge</font></a></li>
												<li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
												<li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
											</ul>
										</div>
										<div class="btn-group">
											<a title="" data-edit="bold" class="btn btn-default" data-original-title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
											<a title="" data-edit="italic" class="btn btn-default" data-original-title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
											<a title="" data-edit="underline" class="btn btn-default" data-original-title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
										</div>
										<div class="btn-group">
											<a title="" data-edit="insertunorderedlist" class="btn btn-default" data-original-title="Bullet list"><i class="fa fa-list-ul"></i></a>
											<a title="" data-edit="insertorderedlist" class="btn btn-default" data-original-title="Number list"><i class="fa fa-list-ol"></i></a>
										</div>
										<div class="btn-group">
											<a title="" data-edit="justifyleft" class="btn btn-info btn-default" data-original-title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
											<a title="" data-edit="justifycenter" class="btn btn-default" data-original-title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
											<a title="" data-edit="justifyright" class="btn btn-default" data-original-title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
											<a title="" data-edit="justifyfull" class="btn btn-default" data-original-title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
										</div>
										<div class="btn-group">
											<a title="" data-toggle="dropdown" class="btn dropdown-toggle btn-default" data-original-title="Hyperlink"><i class="fa fa-link"></i></a>
											<div class="dropdown-menu input-append">
												<input type="text" data-edit="createLink" placeholder="URL" class="span2">
												<button type="button" class="btn">Add</button>
											</div>
											<a title="" data-edit="unlink" class="btn btn-default" data-original-title="Remove Hyperlink"><i class="fa fa-chain-broken"></i></a>
										</div>
										<input type="text" x-webkit-speech="" id="voiceBtn" data-edit="inserttext" style="display: none;">
									</div>
									<div class="condicionesTextArea" id="hotelConditions" name="hotelConditions"><?php echo $arrayHotelProfile2['conditions'] ?></div>
									<div class="textAreaCount mt pull-right"></div>
								</div>
							</div>
							<div class="row mb2">
								<div class="col-lg-6">
									<div class="form-group">
										<label for="hotelType"><?php echo $HotelProfile2Lang['Hotel type'] ?></label>
										<select name="hotelType" id="hotelType" class="form-control" required>
											<option value="Sel"><?php echo $HotelProfile2Lang['Select one option'] ?></option>
											<?php foreach ($arrayTiposHotel as $tipo) {
												if ($tipo['id_tipo_hotel'] == $arrayHotelProfile2['tipo_hotel']) {?>
												<option value="<?php echo $tipo['id_tipo_hotel']; ?>" selected><?php echo $tipo['tipo']; ?></option>
												<?php }else{ ?>
												<option value="<?php echo $tipo['id_tipo_hotel']; ?>"><?php echo $tipo['tipo']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="hotelDecoration"><?php echo $HotelProfile2Lang['Hotel style'] ?></label>
											<select name="hotelDecoration" id="hotelDecoration" class="form-control" required>
												<option value="Sel"><?php echo $HotelProfile2Lang['Select one option'] ?></option>
												<?php foreach ($arrayTiposDecoracion as $tipo) {
													if ($tipo['id_decoracion'] == $arrayHotelProfile2['decoracion']) {?>
													<option value="<?php echo $tipo['id_decoracion']; ?>" selected><?php echo $tipo['decoracion']; ?></option>
													<?php }else{ ?>
													<option value="<?php echo $tipo['id_decoracion']; ?>"><?php echo $tipo['decoracion']; ?></option>
													<?php } ?>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row mb2">
										<div class="col-lg-12">
											<p><strong><?php echo $HotelProfile2Lang['room types'] ?></strong></p>
											<div class="form-group">
												<?php foreach ($arrayTiposHabHotel as $tipoHab) { ?>
												<div class="checkbox col-lg-2 col-md-4 col-xs-6">

													<label>
														<input type="checkbox" value="<?php echo $tipoHab['id_tipo_hab'] ?>" name="roomType[]" <?php if ($arrayTiposHabHotel10[$tipoHab['id_tipo_hab']] == 1){ echo 'checked="checked"';} ?>> <?php echo $tipoHab['tipo_hab'] ?>
													</label>

												</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="row mb2">
										<div class="col-lg-12">
											<p><strong><?php echo $HotelProfile2Lang['room features'] ?></strong></p>
											<div class="form-group">
												<?php foreach ($arrayExtrasHotel as $extraHotel) { ?>
												<div class="checkbox col-lg-2 col-md-4 col-xs-6">
													<label>
														<input type="checkbox" value="<?php echo $extraHotel['id_extra'] ?>" name="roomExtra[]" <?php if ($arrayExtrasHotel10[$extraHotel['id_extra']] == 1){ echo 'checked="checked"';} ?>> <?php echo $extraHotel['extra'] ?>
													</label>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="row mb2">
										<div class="col-lg-12">
											<p><strong><?php echo $HotelProfile2Lang['Hotel services'] ?></strong></p>
											<div class="form-group">
												<?php foreach ($arrayServiciosHotel as $serviciosHotel) { ?>
												<div class="checkbox col-lg-2 col-md-4 col-xs-6">
													<label>
														<input type="checkbox" value="<?php echo $serviciosHotel['id_servicio'] ?>" name="hotelServices[]" <?php if ($arrayServiciosHotel10[$serviciosHotel['id_servicio']] == 1){ echo 'checked="checked"';} ?>> <?php echo $serviciosHotel['servicio'] ?>
													</label>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="row mb2">
										<div class="col-lg-12">
											<div class="form-group">
												<label for="fotosDelHotel"><?php echo $HotelProfile2Lang['Upload pictures'] ?></label>
												<input type="file" multiple class="form-control sendFile" name="fotosDelHotel[]" id="fotosDelHotel" accept="image/*" <?php echo(count($arrayFotosHotel) == 0 ? 'required' : '') ?>>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="alert alert-info mt">
												<div class="pull-left">
													<i class="fa fa-lightbulb-o pr"></i>
												</div>
												<p><?php echo $HotelProfile2Lang['Warning upload pictures'] ?></p>
											</div>
										</div>
										<div class="col-lg-12">
											<h3><?php echo $HotelProfile2Lang['Images already uploaded'] ?></h3>
											<?php foreach ($arrayFotosHotel as $foto) { ?>
											<div class="pull-left img-holder">
												<img class="img-thumbnail" src="<?php echo DIR_IMG_FICHA_HOTEL . $_SESSION['h_logueado'] . '/' . $foto['img'] ?>" alt="bg img" width="100">
												<div class="row">
													<a href="<?php echo $urlTree['hotel-profile-2'] ?>/?pri=<?php echo $foto['id'] ?>" title="priorize img"><i class="fa fa-arrow-circle-o-up azul"></i></a>
													<a href="<?php echo $urlTree['hotel-profile-2'] ?>/?del=<?php echo $foto['id'] ?>" title="delete img"><i class="fa fa-times naranja"></i></a>
												</div>
											</div>
											<?php } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-sm-5 col-md-5 col-xs-12 pull-left mb">
											<input name="save-profile-2" type="submit" value="<?php echo $HotelProfile2Lang['save changes button'] ?>" class="btn btn-success btn-lg mt2 btn-block" />
										</div>
									</div>
									<input type="hidden" name="estrellas" id="estrellasHotel" value="<?php echo $arrayHotelProfile2['estrellas']; ?>">
									<input type="hidden" name="minPriceRange" id="minPriceRange" value="<?php echo $arrayHotelProfile2['min_rango']; ?>">
									<input type="hidden" name="maxPriceRange" id="maxPriceRange" value="<?php echo $arrayHotelProfile2['max_rango']; ?>">
									<input type="hidden" name="numHabitaciones" id="numHabitaciones" value="<?php echo $arrayHotelProfile2['n_habitaciones']; ?>">
									<input type="hidden" name="jan" class="monthVal" id="jan" value="<?php echo $arrayHotelTemporada['jan'] ?>">
									<input type="hidden" name="feb" class="monthVal" id="feb" value="<?php echo $arrayHotelTemporada['feb'] ?>">
									<input type="hidden" name="mar" class="monthVal" id="mar" value="<?php echo $arrayHotelTemporada['mar'] ?>">
									<input type="hidden" name="apr" class="monthVal" id="apr" value="<?php echo $arrayHotelTemporada['apr'] ?>">
									<input type="hidden" name="may" class="monthVal" id="may" value="<?php echo $arrayHotelTemporada['may'] ?>">
									<input type="hidden" name="jun" class="monthVal" id="jun" value="<?php echo $arrayHotelTemporada['jun'] ?>">
									<input type="hidden" name="jul" class="monthVal" id="jul" value="<?php echo $arrayHotelTemporada['jul'] ?>">
									<input type="hidden" name="ago" class="monthVal" id="ago" value="<?php echo $arrayHotelTemporada['ago'] ?>">
									<input type="hidden" name="sep" class="monthVal" id="sep" value="<?php echo $arrayHotelTemporada['sep'] ?>">
									<input type="hidden" name="oct" class="monthVal" id="oct" value="<?php echo $arrayHotelTemporada['oct'] ?>">
									<input type="hidden" name="nov" class="monthVal" id="nov" value="<?php echo $arrayHotelTemporada['nov'] ?>">
									<input type="hidden" name="dece" class="monthVal" id="dece" value="<?php echo $arrayHotelTemporada['dece'] ?>">
									<textarea name="hotelDescriptionText" id="hotelDescriptionText"  class="dnone"><?php echo $arrayHotelProfile2['descripcion'] ?></textarea>
									<textarea name="hotelConditionsText" id="hotelConditionsText"  class="dnone"><?php echo $arrayHotelProfile2['conditions'] ?></textarea>
								</form>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="<?php echo DIR_JS ?>bootstrap-wysiwyg.js"></script>
	<script src="<?php echo DIR_JS ?>jquery.hotkeys.js"></script>
	<script src="<?php echo DIR_JS ?>hotelProfile2.min.js"></script>
	<script src="<?php echo DIR_JS ?>quitarHtmlTagsPaste.js"></script>
	<?php include VIEWS . '/templates/logo-advice-modal.php'; ?>
	<?php include VIEWS . '/templates/banner-advice-modal.php'; ?>
	<?php include VIEWS . '/templates/hotel-exit-profile-warning.php'; ?>
	<script>
		$(document).ready(function(){
			$('.sliderStars').slider({
				range: 'min',
				min: 0,
				max:7,
				value:<?php echo $arrayHotelProfile2['estrellas']; ?>,
				create: function(event,ui){
					if($("#estrellasHotel").val() == 0){
						$(this).css('border', '1px solid #ffba00');
						$(this).children('.ui-slider-range').css('background', '#ffba00');
						$(this).children('.ui-slider-handle').css('background', '#ffba00');
						$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
					}else{
						$(this).css('border', '1px solid #ceeef7');
						$(this).children('.ui-slider-range').css('background', '#c1e4ef');
						$(this).children('.ui-slider-handle').css('background', '#65c3df');
						$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
					}
				},
				slide: function (event, ui) {
					$("#sliderStars").val(ui.value);
					$('#estrellasHotel').val(ui.value);
					if($("#estrellasHotel").val() == 0){
						$(this).css('border', '1px solid #ffba00');
						$(this).children('.ui-slider-range').css('background', '#ffba00');
						$(this).children('.ui-slider-handle').css('background', '#ffba00');
						$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
					}else{
						$(this).css('border', '1px solid #ceeef7');
						$(this).children('.ui-slider-range').css('background', '#c1e4ef');
						$(this).children('.ui-slider-handle').css('background', '#65c3df');
						$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
					}
				}
			});
$('.sliderPriceRange').slider({
	range: true,
	min: 0,
	max: 1500,
	values:[<?php echo $arrayHotelProfile2['min_rango']; ?>,<?php echo $arrayHotelProfile2['max_rango']; ?>],
	step:10,
	create: function(event,ui){
		if($("#maxPriceRange").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}
	},
	slide: function (event, ui) {
		$("#sliderPriceRange").val( "€" + ' ' + ui.values[ 0 ] + " - €" + ' ' + ui.values[ 1 ] );
		$('#minPriceRange').val(ui.values[ 0 ]);
		$('#maxPriceRange').val(ui.values[ 1 ]);
		if($("#maxPriceRange").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}

	}
});

$('.sliderRoomRange').slider({
	range: 'min',
	min: 0,
	max: 2000,
	value:<?php echo $arrayHotelProfile2['n_habitaciones']; ?>,
	create: function(event,ui){
		if($("#numHabitaciones").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}
	},
	slide: function (event, ui) {
		$("#sliderRoomRange").val(ui.value);
		$('#numHabitaciones').val(ui.value);
		if($("#numHabitaciones").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}
	}
});
$('.temporadaSlider').each(function(){
	var meses = <?php echo json_encode($arrayHotelTemporada); ?>;
	var mes = $(this).data('month');
	$(this).slider({
		orientation: 'vertical',
		range: 'min',
		min: 0,
		max: 15,
		step: 5,
		value: meses[mes],
		create: function (event, ui){
			var temporada = meses[mes];
			$('#' + mes).val(meses[mes]);
			if(temporada == 5){
				$(this).parent().children('.temporataText').text('<?php echo $HotelProfile2Lang["baja"] ?>');
			}else if(temporada == 10){
				$(this).parent().children('.temporataText').text('<?php echo $HotelProfile2Lang["media"] ?>');
			}else if (temporada == 15){
				$(this).parent().children('.temporataText').text('<?php echo $HotelProfile2Lang["alta"] ?>');
			}else if(temporada == 0){
				$(this).parent().children('.temporataText').text('<?php echo $HotelProfile2Lang["cerrado"] ?>');
			}
		},
		slide: function (event, ui) {
			var temporada = (ui.value);
			var counter = 0;
			$('#' + mes).val(ui.value);
			if(temporada == 5){
				$(this).parent().children('.temporataText').text('<?php echo $HotelProfile2Lang["baja"] ?>');
			}else if(temporada == 10){
				$(this).parent().children('.temporataText').text('<?php echo $HotelProfile2Lang["media"] ?>');
			}else if (temporada == 15){
				$(this).parent().children('.temporataText').text('<?php echo $HotelProfile2Lang["alta"] ?>');
			}else if(temporada == 0){
				$(this).parent().children('.temporataText').text('<?php echo $HotelProfile2Lang["cerrado"] ?>');
			}
			$('.monthVal').each(function(){
				var value = $(this).val();
				if(value == 0){
					counter += 1;
					if (counter == 12){
						$('.temporadaSlider').css('border', '1px solid #ffba00');
						$('.temporadaSlider').children('.ui-slider-range').css('background', '#ffba00');
						$('.temporadaSlider').children('.ui-slider-handle').css('background', '#ffba00');
						$('.temporadaSlider').children('.ui-slider-handle').css('border', '1px solid #ffba00');
					}else{
						$('.temporadaSlider').css('border', '1px solid #ceeef7');
						$('.temporadaSlider').children('.ui-slider-range').css('background', '#c1e4ef');
						$('.temporadaSlider').children('.ui-slider-handle').css('background', '#65c3df');
						$('.temporadaSlider').children('.ui-slider-handle').css('border', '1px solid #65c3df');
					}
				}
			})
		}
	});
});
$('.img-holder').hover(function(){
	$(this).children('.overlayer').fadeToggle('fast');
})
});
</script>
