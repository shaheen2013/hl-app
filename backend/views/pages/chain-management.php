<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<?php include LANG . $_SESSION['userLang'].'/chain-management.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'chain-management-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-building"></i> <?php echo $lang['Chain management'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-12 mt2">
			<div id="fullContainer">
					<div class="modal-header">
						<h4 class="modal-title"><?php echo $lang['Please fill the basic info for your new hotel'] ?></h4>
					</div>
					<div class="modal-body">
						<p><?php echo $lang['In order to add an hotel to your chain you need to fill the fields below (at least the (*) mandatory fields)'] ?></p>
						<form role="form" class="mt2 validation-form" method="post" enctype="multipart/form-data" >
							<div class="row">
								<div class="col-lg-8 col-md-6">
									<div class="form-group">
										<label for="hotelName"><?php echo $lang['Nombre de tu hotel'] ?></label>
										<input type="text" class="form-control" id="hotelName" name="hotelName"  placeholder="<?php echo $lang['El nombre de tu hotel es...'] ?>" required >
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label for="hotelCity"><?php echo $lang['Ciudad'] ?></label>
										<input class="form-control" name="hotelCity" id="hotelCity" required >
										<input id="place_name" type="hidden" name="place_name" value=""/>
										<input id="place_country" type="hidden" name="place_country" value=""/>
										<input id="place_adm_area" type="hidden" name="place_adm_area" value=""/>
										<input id="lat" type="hidden" name="lat" value=""/>
										<input id="lng" type="hidden" name="lng" value=""/>
										<input id="place_id" type="hidden" name="place_id" value=""/>
                                        <input id="country_name" type="hidden" name="country_name" value=""/>
									</div>
								</div>
								<div class="col-lg-12 mt2">
									<div class="form-group">
										<label for="hotelStreet"><?php echo $lang['Address'] ?></label>
										<input type="text" class="form-control" id="hotelStreet" name="hotelStreet" placeholder="<?php echo $lang['Your hotel address goes here'] ?>" required>
									</div>
								</div>
							</div>
							<div class="row mt2">
								<div class="col-lg-12">
									<div class="form-group">
										<label for="hotelWebsite"><?php echo $lang['Página web'] ?></label>
										<input type="text" class="form-control" id="hotelWebsite" name="hotelWebsite" placeholder="<?php echo $lang['La página web de tu hotel aqui'] ?>" required >
									</div>
								</div>
							</div>
							<!-- <div class="row mt2">
								<div class="col-lg-12">
									<div class="form-group">
										<label for="hotelWebsite"><?php echo $lang['Sube el logo de tu hotel'] ?></label>
										<input type="file" class="form-control sendFile" name="logoHotel" id="logoHotel" required>
									</div>
								</div>
							</div> -->

							<div class="clearfix"></div>
							<div class="col-lg-5 mb">
								<input type="submit" value="<?php echo $lang['Create new hotel button'] ?>" class="btn btn-success btn-lg mt2 btn-block" name="hotelConfirmButton" onclick="sessionStorage.clear()">
							</div>
							<?php if(!empty($arrayDatosHotel['email'])) {?>
							<input type="hidden" name="emailAnterior" value="<?php echo $arrayDatosHotel['email']; ?>" />
							<?php } ?>
							<?php if(!empty($arrayDatosHotel['verificado'])) {?>
							<input type="hidden" name="verificado" value="<?php echo $arrayDatosHotel['verificado']; ?>" />
							<?php } ?>
						</form>
						<div class="clearfix"></div>
					</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'isotope.pkgd.min.js'?>"></script>
<script src="<?php echo DIR_JS . 'imagesloaded.pkgd.min.js'?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en&key=<?php echo GOOGLE_API_KEY ?>"></script>
<script>
	var input = document.getElementById('hotelCity');
	var options = {types: ['geocode']}
	new google.maps.places.Autocomplete(input, options);
</script>
<script src="<?php echo DIR_JS ?>googlePlacesCity.js"></script>
<script>
	$(document).ready(function(){

		var $container = $('#fullContainer');

		$container.imagesLoaded(function () {
			$container.masonry({
				itemSelector: '.item',
				columnWidth: '.item',
				transitionDuration: 0,
				getSortData: {
					cost: '[data-cost] parseInt',
					startDate: '[data-start-date]',
					endDate: '[data-end-date]',
					category: '[data-category] parseInt',
					rating: '[data-rating] parseFloat'
				}
			});
		});

	})
</script>

