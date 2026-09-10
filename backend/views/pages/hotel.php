<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

	<?php include LANG . $_SESSION['userLang'] . '/hotel.php' ?>
	<?php if(isset($_SESSION['h_logueado'])){
		echo '<div id="wrapper">';
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(isset($_SESSION['u_logueado'])){
		include TEMPLATES . 'user-top-bar.php';
	}else if(isset($_SESSION['staff_logueado'])){
		echo '<div id="wrapper">';
		include TEMPLATES . 'check-sidebar.php';
	}else{
		include TEMPLATES . 'user-top-bar.php';
		echo '	<div class="row"><div class="user-utility-bar"></div></div>';
	}?>
	<div id="page-content-wrapper">
		<?php if(isset($_SESSION['h_logueado'])){ ?>
            <div class="top-bar">
                <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                     alt="top bar logo">
            </div>
		<?php } ?>
		<div class="container">
			<div class="topMenu">
				<div class="media">
					<?php if(!empty ($arrayDatosHotel['logo'])){?>
					<a title="perfil" class="pull-left"><img width="80" height="80" alt="<?php echo $arrayDatosHotel['hotelName'] ?>" src="<?php echo $arrayDatosHotel['logo'] ?>" class="img-circle img-thumbnail"></a>
					<?php }else{ ?>
					<a title="perfil" href="<?php echo $urlTree['hotel-profile'] ?>" class="pull-left"><img width="80" height="80" alt="add your logo here" src="public/img/logo.jpg" class="img-circle img-thumbnail"></a>
					<?php } ?>
					<div class="media-body hotelPageDetails">
						<h1 class="media-heading pull-left"><?php echo $arrayDatosHotel['hotelName'] ?></h1>
						<ul class="pull-left pl mt">
							<?php for($i = 0; $i < $arrayDatosHotel['estrellas']; $i++){
								echo '<li><i class="fa fa-star"></i></li>';
							}?>
						</ul>
						<div class="clearfix"></div>
							<h3 class="cityTitle"><?php echo $arrayDatosHotel['pais'] ?></h3>
							<!-- <p>This hotel is part of: <strong><a href="#" title="Melia hotels and resort">Melia hotels and resort</a></strong></p> -->
					</div>
				</div>
			</div>
			<div class="col-lg-8 ofertaDetalle col-md-7">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="photo-module">
							<div class="unslider">
								<ul>
									<?php foreach ($arrayImagenes as $image) { ?>
									<li><a href="<?php echo DIR_IMG_FICHA_HOTEL ?><?php echo $arrayDatosHotel['id'] ?>/<?php echo $image ?>" data-lightbox="roadtrip"><img src="<?php echo DIR_IMG_FICHA_HOTEL ?><?php echo $arrayDatosHotel['id'] ?>/<?php echo $image ?>"></a></li>
									<?php } ?>
								</ul>
							</div>
						</div>
						<div class="hotelImgGallery mt">
							<ul>
								<?php foreach ($arrayImagenes as $image) { ?>
								<li><a href="<?php echo DIR_IMG_FICHA_HOTEL ?><?php echo $arrayDatosHotel['id'] ?>/<?php echo $image ?>" data-lightbox="roadtrip" class="thumbnail"><img src="<?php echo DIR_IMG_FICHA_HOTEL ?><?php echo $arrayDatosHotel['id'] ?>/small_<?php echo $image ?>"></a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="hotelDescription mt">
							<h3><?php echo $HotelLang['Hotel description'] ?></h3>
							<p>
								<?php echo $arrayDatosHotel['descripcion'] ?>
							</p>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="hotelAmenities mt">
							<h3><?php echo $HotelLang['Hotel Services'] ?></h3>
							<ul class="pull-left">
								<li><strong><?php echo $HotelLang['room types'] ?></strong></li>
								<?php foreach ($arrayTiposHabitacion as $habitacion) {
									echo '<li>'.$habitacion .'</li>';
								} ?>
							</ul>
							<ul class="pull-left">
								<li><strong><?php echo $HotelLang['room services'] ?></strong></li>
								<?php foreach ($arrayExtras as $extra) {
									echo '<li>'.$extra .'</li>';
								} ?>
							</ul>
							<ul class="pull-left">
								<li><strong><?php echo $HotelLang['Services'] ?></strong></li>
								<?php foreach ($arrayServicios as $servicio) {
									echo '<li>'.$servicio .'</li>';
								} ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="hotelConditions mt">
							<h3><?php echo $HotelLang['Hotel conditions'] ?></h3>
							<p>
								<?php echo $arrayDatosHotel['condiciones'] ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-5">
				<div class="btn-group-vertical dblock hotelOptions text-left mb2">
					<a href="#" class="btn btn-default btn-lg hotelBookingBtn"><i class="fa fa-book pr"></i><?php echo $HotelLang['Booking contact info'] ?></a>
					<?php if(!empty ($follow)) {
						if($follow){ ?>
						<a href="<?php echo $url_o ?>/?unfol=<?php echo $arrayDatosHotel['id'] ?>&tipo=hot" class="btn btn-default btn-lg"><i class="fa fa-thumbs-o-down pr"></i> <?php echo $HotelLang['Unfollow hotel'] ?></a>
						<?php }else{ ?>
						<a href="<?php echo $url_o ?>/?fol=<?php echo $arrayDatosHotel['id'] ?>&tipo=hot" class="btn btn-default btn-lg"><i class="fa fa-thumbs-o-up pr"></i> <?php echo $HotelLang['Follow hotel'] ?></a>
						<?php } ?>
						<?php }else{ ?>
						<a href="<?php echo $url_o ?>/?fol=<?php echo $arrayDatosHotel['id'] ?>&tipo=hot" class="btn btn-default btn-lg"><i class="fa fa-thumbs-o-up pr"></i> <?php echo $HotelLang['Follow hotel2'] ?></a>
						<?php } ?>
						<a href="#" class="btn btn-default btn-lg hotelIssueBtn"><i class="fa fa-exclamation-triangle pr"></i> <?php echo $HotelLang['Help'] ?></a>
					</div>
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<h3><strong><?php echo $HotelLang['Price range'] ?></strong></h3>
							<h2><?php echo $arrayDatosHotel['min_rango'] ?> <i class="fa fa-arrows-h"></i> <?php echo $arrayDatosHotel['max_rango'] ?> €</h2>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
							<div id="fichaHotelMap" style="height:200px"></div>
							<address class="hotelLoc mt">
								<strong><?php echo $arrayDatosHotel['street'] ?></strong><br>
								<?php echo $arrayDatosHotel['pais'] ?><br>
							</address>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include (TEMPLATES . 'bookingContactModal.php');?>
<?php include (TEMPLATES . 'issuesContactModal.php');?>
<script src="https://code.jquery.com/jquery-1.9.0.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.0.0.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="<?php echo DIR_JS . 'unslider.js'?>"></script>
<script src="<?php echo DIR_JS . 'lightbox.min.js'?>"></script>
<script>
	$(document).ready(function() {
		//-----------------------------------------------------------
		/*$("#results").load("/lib/webservices/hotel.php", {'page':0}, function() {$("#1-page").addClass('active');});  //initial page number to load

		$(".paginate_click").live("click",function (e) {

			$("#results").prepend('<div class="loading-indication"><i class="fa fa-spinner"></i> Loading...</div>');

			var clicked_id = $(this).attr("id").split("-"); //ID of clicked element, split() to get page number.
			var page_num = parseInt(clicked_id[0]); //clicked_id[0] holds the page number we need

			$('.paginate_click').removeClass('active'); //remove any active class

			//post page number and load returned data into result element
			//notice (page_num-1), subtract 1 to get actual starting point
			$("#results").load("/lib/webservices/hotel.php", {'page': (page_num-1)}, function(){

			});

			$(this).addClass('active'); //add active class to currently clicked element

			return false; //prevent going to herf link
		});*/
		//-----------------------------------------------------------
		$('.unslider').unslider({
			speed: 500,
			delay: 3000,
			keys: true,
			dots: true,
			fluid:true
		});
//googlemaps
var geocoder, map;
geocoder = new google.maps.Geocoder();
geocoder.geocode({
	'address': "<?php echo $arrayDatosHotel['street'] ?>, <?php echo $arrayDatosHotel['pais'] ?>"
}, function(results, status) {
	if (status == google.maps.GeocoderStatus.OK) {
		var myOptions = {
			zoom: 14,
			center: results[0].geometry.location,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById("fichaHotelMap"), myOptions);

		var marker = new google.maps.Marker({
			map: map,
			position: results[0].geometry.location
		});
	}
});
//googlemaps
$('.hotelBookingBtn').click(function(e){
	e.preventDefault();
	$('#bookingContactModal').modal('show');
});
$('.hotelIssueBtn').click(function(e){
	e.preventDefault();
	$('#issuesContactModal').modal('show');
});
});
</script>