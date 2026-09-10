<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
	echo 'No direct access allowed.';
	exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/archived-hotels.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
		<div class="top-bar">
			<?php include TEMPLATES . 'chain-management-menu.php'; ?>
			<img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
		</div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-building"></i> <?php echo $lang['archived hotels'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include(TEMPLATES . 'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-12 mt2">
			<?php $hotel = null;
			if (!empty($arrayHoteles)) { ?>
				<div id="fullContainer">
					<?php foreach ($arrayHoteles as $hotel) { ?>
						<div class="item item col-lg-3 col-md-4 col-sm-6 col-xs-12  mb2">
							<div class="white-module noPadding fichaHotel">
								<div class="overlayer absolute">
									<ul class="fichaOfertaUl">
										<li><a href="<?php echo $url['dir1'] ?>/?change=<?php echo $hotel['id'] ?>" class="btn btn-hollow btn-lg hotelManagementBtn" data-toggle="tooltip" title="Go to hotel dashboard" style="width: 140px">ID: <?php echo $hotel['id'] ?><br><i class="fa fa-tachometer"></i></a></li>
										<li id="hotel" data-id="<?php echo $hotel['id'] ?>" data-brand_id="<?php echo $hotel['brand_id'] ?>"><a onClick="return false" class="btn btn-hollow btn-lg" style="width: 140px" data-toggle="tooltip" title="Active hotel"> <?php echo $lang['active hotel'] ?> <br><i class="fa fa-folder"></i></a></li>
									</ul>
								</div>
								<img class="hotel-img" style="min-height:15rem" src="<?php echo (!empty($hotel['logo']) ? imageSize('original', $hotel['logo']) : DIR_IMG . 'img-placeholder.jpg') ?>" alt="<?php echo $hotel['hotelName']; ?>" />
								<ul class="hotel-stars">
									<?php for ($i = 0; $i < $hotel['estrellas']; $i++) { ?>
										<li><i class="fa fa-star"></i></li>
									<?php } ?>
								</ul>
								<h4><?php echo $hotel['hotelName']; ?></h4>
								<div class="hotel-rating">
									<span class="azul">
										<h3><i class="fa fa-heart"></i> <?php echo $hotel['rating']; ?></h3>
									</span>
								</div>
								<div class="hotel-media col-lg-12 mt">
									<span class="pull-left hotel-location media-info"><i class="fa fa-map-marker"></i> <?php echo $hotel['city']; ?></span>
									<span class="pull-right hotel-offer-numbers media-info"><i class="fa fa-gift"></i> <?php echo $hotel['nofertas']; ?></span>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } else { ?>
				<div class="text-center mt2 container no-data-msg">
					<i class="fa fa-folder-open-o grisClaro fa-5x"></i>
					<h2><?php echo $lang['no hotels'] ?></h2>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php include TEMPLATES . 'create-new-hotel-modal.php'; ?>
<script src="<?php echo DIR_JS . 'isotope.pkgd.min.js' ?>"></script>
<script src="<?php echo DIR_JS . 'imagesloaded.pkgd.min.js' ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en&key=<?php echo GOOGLE_API_KEY ?>"></script>
<script>
	var input = document.getElementById('hotelCity');
	var options = {
		types: ['geocode']
	}
	new google.maps.places.Autocomplete(input, options);
</script>
<script src="<?php echo DIR_JS ?>googlePlacesCity.js"></script>
<script>
	$(document).ready(function() {

		var $container = $('#fullContainer');

		$container.imagesLoaded(function() {
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
		$('.fichaHotel').hover(function() {
			$(this).children('.overlayer').fadeToggle('fast');
		});
		$('.hasTooltip').tooltip({
			container: "body"
		});
		//active an archived hotel
		$('li[id=hotel]').on('click', function(e) {
			var hotel_id = $(this).data("id");
			var brand_id = $(this).data("brand_id");
			activeHotel(hotel_id, brand_id);
			// We clean sessionStorage after reactivating the hotel in order to show correct hotel list
			sessionStorage.clear();
		});

		function activeHotel(hotel_id, brand_id) {
			var chain_id = "<?php echo (array_get($_SESSION, 'c_logueado')) ?>";
			var datos = {
				"hotel_id": hotel_id,
				"chain_id": chain_id,
				"brand_id": brand_id,
				"status": 1,
			};

			$.ajax({
				"url": '<?php echo (SECURE_BASE_PATH . LIB . 'webservices/archive-hotel.php') ?>',
				"data": datos,
				type: 'POST',
				success: function(response) {
					if (response != null) {
						location.href = '<?php echo $url['dir1'] ?>/?activate=' + response.id;
					}
				}
			});
		}
	})
</script>