<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<div class="tienda">
<?php include LANG . $_SESSION['userLang'] . '/tienda.php' ?>
<header class="user-top-bar-header">
	<div class="container-fluid text-center">
		<div class="user-top-bar">
			<?php if (!$detect->isMobile() || $detect->isTablet()) {?>
			<div class="col-lg-2 col-xs-3">
				<a href="<?php echo $urlTree['tienda'] ?>" title="Go back to home" class="user-logo pull-left"><img src="<?php echo DIR_IMG . 'logo-light.png' ?>" width="154" height="32" alt="logo"></a>
			</div>
			<?php }else{ ?>
			<a href="<?php echo $urlTree['tienda'] ?>" title="Go back to home" class="user-logo-mobile pull-left"><img src="<?php echo DIR_IMG . 'big-logo.png' ?>" width="30" height="30" alt="logo"></a>
			<?php } ?>
			<?php if (!$detect->isMobile() || $detect->isTablet()) {?>
			<div class="col-lg-8 col-xs-7">
				<nav class="text-center">
					<ul class="shop-main-nav">
						<li><a href="<?php echo $url['dir1'] . '/?cat=ngr' ?>" title="free nights"><i class="fa fa-moon-o fa-2x"></i><br><?php echo $Tiendalang['free nights'] ?></a></li>
						<li><a href="<?php echo $url['dir1'] . '/?cat=upg' ?>" title="room upgrades"><i class="fa fa-arrow-circle-o-up fa-2x"></i><br><?php echo $Tiendalang['room upgrades'] ?></a></li>
						<li><a href="<?php echo $url['dir1'] . '/?cat=des' ?>" title="booking discounts"><i class="fa fa-usd fa-2x"></i><br><?php echo $Tiendalang['booking discounts'] ?></a></li>
						<li class="hotel-service-hover">
							<a href="#" title="hotel services"><i class="fa fa-coffee fa-2x"></i><br><?php echo $Tiendalang['hotel services'] ?></a>
							<ul class="shop-submenu dnone hotel-services-submenu">
								<?php foreach ($arrayCategorias as $categoria) { ?>
								<li><a href="<?php echo $url['dir1'] . '/?cat=' . $categoria['id'] ?>" title="<?php echo $categoria['categoria']; ?>"><?php echo $categoria['categoria']; ?></a>
								</li>
								<?php } ?>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
			<?php } ?>
			<?php if(!empty($_SESSION['u_logueado'])){ ?>
			<div class="<?php echo($detect->isMobile || !$detect->isTablet ? 'pull-right' : 'col-lg-2 shop-user-avatar col-xs-3') ?>">
				<img class="img-circle pull-right hidden-xs" width="50" height="50" src="<?php echo (!empty($_SESSION['image']) ? $_SESSION['image'] : DIR_IMG . 'avatar.jpg') ?>" alt="user image">
				<img class="img-circle pull-right visible-xs" width="30" height="30" src="<?php echo (!empty($_SESSION['image']) ? $_SESSION['image'] : DIR_IMG . 'avatar.jpg') ?>" alt="user image">
				<a class="shop-user-menu pull-right" href="#"><?php echo($totalEncuestas > 0 ? '<span class="badge pull-left"><i class="fa fa-bell-o"></i></span> ' : '')?> <div class="hidden-xs pull-left pl"> menu</div> <i class="fa fa-chevron-down pr"></i></a>
				<ul class="shop-user-submenu dnone">
					<li><a href="<?php echo $urlTree['tienda'] ?>" title="rewards shop"><i class="fa fa-shopping-cart"></i><span class="pl"><?php echo $Tiendalang['Rewards shop'] ?></span></a></li>
					<li><a href="<?php echo $urlTree['user-points'] ?>" title="points management"><i class="rubies rubix1">rubies</i><span class="pl"><?php echo $Tiendalang['Rewards management'] ?></span></a></li>
					<li><a href="<?php echo $urlTree['user-survey-list'] ?>" title="survey management"><i class="fa fa-check-square-o"></i><span class="pl"><?php echo $Tiendalang['Survey management'] ?></span></a><?php echo($totalEncuestas > 0 ? '<span class="badge pull-right">'.$totalEncuestas.'</span>' : '')?></li>
					<li><a href="<?php echo $urlTree['user-wishlist'] ?>" title="your wishlist"><i class="fa fa-heart"></i><span class="pl"><?php echo $Tiendalang['Your wishlist'] ?></span></a><span class="badge pull-right"><?php echo $totalWishlistBar ?></span></li>
					<li><a href="<?php echo $urlTree['user-ofertas'] ?>" title="your vouchers"><i class="fa fa-ticket"></i><span class="pl"><?php echo $Tiendalang['Your vouchers'] ?></span></a><span class="badge pull-right"><?php echo $totalVouchersBar ?></span></li>
					<li><a href="<?php echo $urlTree['user-profile'] ?>" title="your profile"><i class="fa fa-cog"></i><span class="pl"><?php echo $Tiendalang['Your profile'] ?></span></a></li>
					<li><a href="<?php echo $urlTree['faq'] ?>" title="frequently asked questions"><i class="fa fa-question"></i><span class="pl"><?php echo $Tiendalang['Help'] ?></span></a></li>
					<li><a href="<?php echo $urlTree['logout'] ?>" title="log out" class="naranja"><i class="fa fa-power-off"></i><span class="pl"><?php echo $Tiendalang['Log out'] ?></span></a></li>
				</ul>
				<?php if(!empty($usuarioCheckinBar)) { ?>
				<div class="pull-right">
					<a href="<?php echo $usuarioCheckinBar['urlGUIDHotel'] ?>" class="hasTooltip" data-toggle="tooltip" data-placement="bottom" title="<?php echo $Tiendalang['Currently checked-in at'] ?> <?php echo $usuarioCheckinBar['nombre'] ?>"><span class="badge check-in-advice"><i class="fa fa-lock"></i></span></a>
				</div>
				<?php } ?>
				<?php } else { ?>
				<p class="register-copy pull-right pr"><a href="<?php echo $urlTree['login'] ?>" title="register"><?php echo $Tiendalang['Register now'] ?></a> or <a href="<?php echo $urlTree['login'] ?>" title="login"><?php echo $Tiendalang['login'] ?></a></p>
			</div>
			<?php } ?>
		</div>
</header>
<?php if ($detect->isMobile() && !$detect->isTablet()) {?>
<div class="mobile-search-section">
	<h3><?php echo $Tiendalang['Search offers you love'] ?></h3>
	<a href="#" class="closeAdvancedSearch" title="<?php echo $Tiendalang['close advanced search'] ?>"><i class="fa fa-times fa-2x"></i></a>
	<form action="<?php echo $urlTree['tienda'] ?>/" method="get" id="tiendaForm" class="main-shop-form center-block">
		<div class="form-group col-lg-3">
			<label class="sr-only" for="ciudad"><?php echo $Tiendalang['City'] ?></label>
			<input type="text" class="form-control" name="cty" id="ciudad" placeholder="<?php echo $Tiendalang['By city name...'] ?>">
		</div>
		<div class="form-group col-lg-3">
			<label class="sr-only" for="hotel-field"><?php echo $Tiendalang['Hotel'] ?></label>
			<input type="text" class="form-control" id="hotel" placeholder="<?php echo $Tiendalang['By hotel name...'] ?>">
		</div>
		<div class="form-group col-lg-2 shop-start-date">
			<label class="sr-only" for="start-date"><?php echo $Tiendalang['Start date'] ?></label>
			<input type="text" class="form-control fechaFin" name="from" id="start-date" placeholder="<?php echo $Tiendalang['Start date...'] ?>">
		</div>
		<div class="form-group col-lg-2 shop-end-date">
			<label class="sr-only" for="end-date"><?php echo $Tiendalang['End'] ?></label>
			<input type="text" class="form-control fechaInicio" name="till" id="end-date" placeholder="<?php echo $Tiendalang['End date...'] ?>">
		</div>
		<div class="form-group col-lg-2">
			<button type="submit" class="form-control btn btn-primary"><i class="fa fa-search"></i> <?php echo $Tiendalang['Find offers'] ?></button>
		</div>
		<div class="advancedSearch">
			<h3><?php echo $Tiendalang['Filter rewards by:'] ?></h3>
			<a href="#" class="closeAdvancedSearch" title="close advanced search"><i class="fa fa-times fa-2x"></i></a>
			<div class="col-lg-12">
				<label for="rubiesRange"><?php echo $Tiendalang['Rubies'] ?> (<i class="rubies rubix1">rubies</i>): </label> <strong><input class="noBorder mb col-xs" type="text" id="rubiesRange" disabled name="rubiesRange"></strong>
				<div class="slider rubiesRange mt"></div>
			</div>
			<div class="col-lg-12">
				<label for="hotelRange" class="mt2"><?php echo $Tiendalang['Categoría del hotel:'] ?> </label> <strong><input class="noBorder mb col-xs" type="text" id="hotelRange" disabled name="hotelRange"></strong>
				<div class="slider hotelRange mt"></div>
			</div>
			<div class="col-lg-12 mb2">
				<label for="hotelRating" class="mt2"><?php echo $Tiendalang['Rating del hotel:'] ?> </label> <strong><input class="noBorder mb col-xs" type="text" id="hotelRating" disabled name="hotelRating"></strong>
				<div class="slider hotelRating mt"></div>
			</div>
			<div class="col-lg-4 mt2">
				<button type="submit" class="form-control btn btn-primary"><i class="fa fa-search"></i> <?php echo $Tiendalang['Find offers'] ?></button>
			</div>
		</div>
		<input type="hidden" name="pt1" id="minRubiesRange">
		<input type="hidden" name="pt2" id="maxRubiesRange">
		<input type="hidden" name="es1" id="minHotelRange">
		<input type="hidden" name="es2" id="maxHotelRange">
		<input type="hidden" name="rat1" id="minHotelRating">
		<input type="hidden" name="rat2" id="maxHotelRating">
		<input type="hidden" name="hcn" id="hotelId">
	</form>
</div>
<?php }else{ ?>
<div class="container-fluid search-section">
	<div class="row text-center">
		<h1 class="shop-header"><?php echo $Tiendalang['Search the offers you love'] ?></h1>
		<h2 class="shop-subheader"><?php echo $Tiendalang['Among thousands of cities and hotels around the globe'] ?></h2>
		<div class="container">
			<form action="<?php echo $urlTree['tienda'] ?>/" method="get" id="tiendaForm" class="form-inline main-shop-form center-block">
				<div class="form-group col-lg-3">
					<label class="sr-only" for="ciudad"><?php echo $Tiendalang['City'] ?></label>
					<input type="text" class="form-control" name="cty" id="ciudad" placeholder="<?php echo $Tiendalang['By city name...'] ?>">
				</div>
				<div class="form-group col-lg-3">
					<label class="sr-only" for="hotel-field"><?php echo $Tiendalang['Hotel'] ?></label>
					<input type="text" class="form-control" id="hotel" placeholder="<?php echo $Tiendalang['By hotel name...'] ?>">
				</div>
				<div class="form-group col-lg-2 shop-start-date">
					<label class="sr-only" for="start-date"><?php echo $Tiendalang['Start date'] ?></label>
					<input type="text" class="form-control fechaFin" name="from" id="start-date" placeholder="<?php echo $Tiendalang['Start date...'] ?>">
				</div>
				<div class="form-group col-lg-2 shop-end-date">
					<label class="sr-only" for="end-date"><?php echo $Tiendalang['End'] ?></label>
					<input type="text" class="form-control fechaInicio" name="till" id="end-date" placeholder="<?php echo $Tiendalang['End date...'] ?>">
				</div>
				<div class="form-group col-lg-2">
					<button type="submit" class="form-control btn btn-primary"><i class="fa fa-search"></i> <?php echo $Tiendalang['Find offers 2'] ?></button>
				</div>
				<div class="advancedSearch">
					<h3><?php echo $Tiendalang['Filter rewards by:'] ?></h3>
					<a href="#" class="closeAdvancedSearch" title="close advanced search"><i class="fa fa-times fa-2x"></i></a>
					<div class="col-lg-12">
						<label for="rubiesRange" class="mt2"><?php echo $Tiendalang['Rango de rubies'] ?> (<i class="rubies rubix1">rubies</i>): </label> <strong><input class="noBorder mb col-xs" type="text" id="rubiesRange" disabled name="rubiesRange"></strong>
						<div class="slider rubiesRange mt"></div>
					</div>
					<div class="col-lg-12">
						<label for="hotelRange" class="mt2"><?php echo $Tiendalang['Categoría del hotel:'] ?> </label> <strong><input class="noBorder mb col-xs" type="text" id="hotelRange" disabled name="hotelRange"></strong>
						<div class="slider hotelRange mt"></div>
					</div>
					<div class="col-lg-12">
						<label for="hotelRating" class="mt2"><?php echo $Tiendalang['Rating del hotel:'] ?> </label> <strong><input class="noBorder mb col-xs" type="text" id="hotelRating" disabled name="hotelRating"></strong>
						<div class="slider hotelRating mt"></div>
					</div>
					<div class="col-lg-12 mt2">
						<p><strong><?php echo $Tiendalang['Filtros adicionales'] ?></strong></p>
						<ul class="categoryListTienda">
							<li><input type="checkbox" value="condiciones" name="filtros[]" class="filtrosChecker"> <?php echo $Tiendalang['Ver solo sin condiciones'] ?></li>
							<li><input type="checkbox" value="condiciones" name="filtros[]" class="filtrosChecker"> <?php echo $Tiendalang['Ver solo mis preferentes'] ?></li>
						</ul>
					</div>
					<div class="col-lg-4 mt2">
						<button type="submit" class="form-control btn btn-primary"><i class="fa fa-search"></i> <?php echo $Tiendalang['Find offers'] ?></button>
					</div>
				</div>
				<input type="hidden" name="pt1" id="minRubiesRange">
				<input type="hidden" name="pt2" id="maxRubiesRange">
				<input type="hidden" name="es1" id="minHotelRange">
				<input type="hidden" name="es2" id="maxHotelRange">
				<input type="hidden" name="rat1" id="minHotelRating">
				<input type="hidden" name="rat2" id="maxHotelRating">
				<input type="hidden" name="hcn" id="hotelId">
                <!--google-->
                <input type="hidden" name="place_id" id="place_id">
                <input type="hidden" name="place_name" id="place_name">
                <input type="hidden" name="place_country" id="place_country">
                <input type="hidden" name="place_type" id="place_type">
                <input type="hidden" name="latNE" id="latNE">
                <input type="hidden" name="lngNE" id="lngNE">
                <input type="hidden" name="latSW" id="latSW">
                <input type="hidden" name="lngSW" id="lngSW">
                <input type="hidden" name="place_adm_area" id="place_adm_area"><!--google-->
			</form>
		</div>
	</div>
	<div class="type-selector text-center">
		<p><?php echo $Tiendalang['¿What do you want to search?'] ?></p>
		<ul class="type-selector-ul">
			<li><a href="#" data-toggle="tooltip" data-placement="top" title="Change search to find best offers" class="shop-switch offer-switch switch-active hasTooltip"><i class="rubies rubix3"><?php echo $Tiendalang['Rubies'] ?></i></a></li>
			<!-- 			<li><a href="#" data-toggle="tooltip" data-placement="top" title="Change search to find best hotels" class="shop-switch hotel-switch hasTooltip"><i class="fa fa-building fa-2x"></i></a></li> -->
		</ul>
	</div>
</div>
<?php } ?>
<div class="container pl0 pr0">
	<?php if (!$detect->isMobile() || $detect->isTablet()) {?>
	<div class="row shop-filters">
		<div class="pull-left">
			<a href="#" title="shop filters" class="shop-filters-link asBtn"><i class="fa fa-search-plus"></i> <span><?php echo $Tiendalang['Filter offers'] ?></span></a>
		</div>
		<div class="pull-right">
			<ul class="shop-sort-list">
				<li><span><?php echo $Tiendalang['Order offers by:'] ?></span></li>
				<li><a href="#" title="<?php echo $Tiendalang['rewards'] ?>" class="sortBtn" data-sort-by="cost"><i class="rubies rubix1">rubies</i></a></li>
				<li><a href="#" title="<?php echo $Tiendalang['calendar'] ?>" class="sortBtn" data-sort-by="startDate"><i class="fa fa-calendar"></i></a></li>
				<li><a href="#" title="<?php echo $Tiendalang['heart'] ?>" class="sortBtn" data-sort-by="rating"><i class="fa fa-heart" ></i></a></li>
				<li><a href="#" title="<?php echo $Tiendalang['star'] ?>" class="sortBtn" data-sort-by="category"><i class="fa fa-star"></i></a></li>
			</ul>
		</div>
	</div>
	<?php }else{ ?>
	<nav class="text-center">
		<ul class="shop-main-nav list-inline">
			<li><a href="<?php echo $url['dir1'] . '/?cat=ngr' ?>" title="free nights"><i class="fa fa-moon-o"></i><br><?php echo $Tiendalang['free nights'] ?></a></li>
			<li><a href="<?php echo $url['dir1'] . '/?cat=upg' ?>" title="room upgrades"><i class="fa fa-arrow-circle-o-up"></i><br><?php echo $Tiendalang['room upgrades mobile'] ?></a></li>
			<li><a href="<?php echo $url['dir1'] . '/?cat=des' ?>" title="Discounts"><i class="fa fa-usd"></i><br><?php echo $Tiendalang['booking discounts mobile'] ?></a></li>
			<li class="hotel-service-hover">
				<a href="#" title="hotel services"><i class="fa fa-coffee"></i><br><?php echo $Tiendalang['hotel services mobile'] ?></a>
			</li>
		</ul>
		<ul class="shop-submenu dnone hotel-services-submenu">
			<li><a href="#" class="closeSubCategories" title="<?php echo $Tiendalang['close hotel service modal mobile'] ?>"><i class="fa fa-times fa-2x"></i></a></li>
			<?php foreach ($arrayCategorias as $categoria) { ?>
			<li><a href="<?php echo $url['dir1'] . '/?cat=' . $categoria['id'] ?>" title="<?php echo $categoria['categoria']; ?>"><?php echo $categoria['categoria']; ?></a>
			</li>
			<?php } ?>
		</ul>
	</nav>
	<div class="mobile-shop-filters">
		<ul class="shop-sort-list">
			<li><a href="#" title="<?php echo $Tiendalang['rewards'] ?>" class="sortBtn" data-sort-by="cost"><i class="rubies rubix1">rubies</i></a></li>
			<li><a href="#" title="<?php echo $Tiendalang['calendar'] ?>" class="sortBtn" data-sort-by="startDate"><i class="fa fa-calendar"></i></a></li>
			<li><a href="#" title="<?php echo $Tiendalang['heart'] ?>" class="sortBtn" data-sort-by="rating"><i class="fa fa-heart" ></i></a></li>
			<li><a href="#" title="<?php echo $Tiendalang['star'] ?>" class="sortBtn" data-sort-by="category"><i class="fa fa-star"></i></a></li>
		</ul>
	</div>
	<?php } ?>
	<div class="row" id="shop-container">
		<?php if (!empty($arrayOfertas)){ ?>
		<?php foreach ($arrayOfertas as $oferta) { ?>
		<div class="shop-item" data-cost="<?php echo $oferta['puntos']; ?>" data-start-date="<?php echo $oferta['inicio_ord']; ?>" data-end-date="<?php echo $oferta['fin']; ?>" data-category="<?php echo $oferta['estrellas'] ?>" data-rating="<?php echo $oferta['rating']; ?>">
			<div class="offer-banner">
				<?php if($oferta['id_tipo_oferta'] == 'chk'){
					echo '<div class="offer-checkin pull-left"><i class="fa fa-lock"></i></div>';
				} ?>
				<div class="banner-sprite end-banner pull-left"></div>
				<div class="banner-price pull-left"><?php echo ($oferta['adq_ret'] == 'adq' ? '<i class="rubies rubix1 rubiesHL">rubies</i>' :'<i class="rubies rubix1">rubies</i>') ?> <?php echo number_format($oferta['puntos'],0,",","."); ?></div>
				<div class="banner-sprite start-banner pull-left"></div>
			</div>
			<?php if($oferta['wishlist'] == '0'){ ?>
			<a href="<?php echo $urlTree['tienda'] ?>/?wlst=<?php echo $oferta['id']; ?>" class="shop-offer-like"><i class="fa fa-heart"></i></a>
			<?php }else{ ?>
			<a href="<?php echo $urlTree['tienda'] ?>/?unwlst=<?php echo $oferta['id']; ?>" class="shop-offer-like"><i class="fa fa-heart verde fa-2x"></i></a>
			<?php } ?>
			<div class="shop-item-container">
				<div class="can-buy-offer <?php echo( $oferta['puedeAdquirir'] == '0' ? 'no-se-puede-comprar' : 'se-puede-comprar') ?>"></div>
				<a href="<?php echo $urlTree['oferta'] ?>/<?php echo $oferta['url']; ?>/<?php echo $oferta['id']; ?>" class="shop-offer-link" title="<?php echo $oferta['nombre']; ?>"></a>
				<div class="offer-text">
					<h3><a href="<?php echo $urlTree['oferta'] ?>/<?php echo $oferta['url']; ?>/<?php echo $oferta['id']; ?>" title="<?php echo $oferta['nombre']; ?>"><?php echo $oferta['nombre']; ?></a></h3>
					<div class="row">
						<div class="pull-left shop-hotel-name mb">
							<?php if ($oferta['logo'] == '0' || !file_exists( DIR_IMG_FICHA_HOTEL . $oferta['id_hotel'] . '/logo/' . $oferta['logo'])){?>
							<img class="img-circle pull-left" src="<?php echo DIR_IMG;?>logo.jpg" alt="<?php echo $oferta['hotelName']; ?>" width="30" height="30">
							<?php }else {?>
							<img class="img-circle pull-left" src="<?php echo DIR_IMG_FICHA_HOTEL ?><?php echo $oferta['id_hotel']; ?>/logo/<?php echo $oferta['logo'];?>" alt="<?php echo $oferta['hotelName']; ?>" width="30" height="30">
							<?php } ?> <a class="pull-left pl pr" href="<?php echo $oferta['urlGUID']; ?>"><?php echo $oferta['hotelName']; ?></a>
							<?php for ($i=0; $i < $oferta['estrellas']; $i++) {
								echo '<i class="fa fa-star"></i>';
							} ?>
						</div>
					</div>
				</div>
				<div class="shop-offer">
					<?php if ($oferta['img'] && file_exists( DIR_IMG_OFERTAS . $oferta['id'] . '/' .$oferta['img'])) { ?>
					<img class="offer-img" src="<?php echo DIR_IMG_OFERTAS . $oferta['id']; ?>/<?php echo $oferta['img']; ?>" alt="<?php echo $oferta['nombre']; ?>" width="275"/>
					<?php }else{ ?>
					<img class="offer-img" src="<?php echo DIR_IMG;?>img-placeholder.jpg" alt="<?php echo $oferta['nombre']; ?>" width="275"/>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php } else { ?>
		<div class="alert alert-danger" role="alert"><?php echo $Tiendalang['Sorry, there are no results to show. Try again using different search criterias please'] ?></div>
		<?php } ?>
	</div>
</div>
<div class="mobile-separator visible-xs">
	
</div>
<div class="visible-xs mobile-filters">
	<a href="#" title="filters" class="shop-filters-link asBtn"><i class="fa fa-filter"></i><br><?php echo $Tiendalang['Filters mobile'] ?></a>
	<a href="#" title="search" class="shop-search-link"><i class="fa fa-search"></i><br><?php echo $Tiendalang['Search mobile'] ?></a>
	<a href="#" title="sorting" class="sorting-search-link"><i class="fa fa-sort-amount-asc"></i><br><?php echo $Tiendalang['Sorting mobile'] ?></a>
</div>
</div>
<script src="<?php echo DIR_JS . 'tienda.min.js' ?>"></script>
<script src="<?php echo DIR_JS . 'isotope.pkgd.min.js'?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en&key=<?php echo GOOGLE_API_KEY ?>"></script>
<script>
	var location_being_changed;
	var input = document.getElementById('ciudad');
	var options = {types: ['geocode']}
	var autocomplete = new google.maps.places.Autocomplete(input, options);
	onPlaceChange = function () {
        location_being_changed = false;
    };
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var thisplace = autocomplete.getPlace();
		
		//Guardamos el tipo de lugar de búsqueda
		var elem = document.getElementById("place_type");
	   	elem.value = thisplace.address_components[0].types[0];
		//NE
		var ne =  thisplace.geometry.viewport.getNorthEast();
		var latNE = ne.lat();
		var lngNE = ne.lng();
		$('#latNE').val(latNE);
		$('#lngNE').val(lngNE);
		//SW
		var sw =  thisplace.geometry.viewport.getSouthWest();
		var latSW = sw.lat();
		var lngSW = sw.lng();
		$('#latSW').val(latSW);
		$('#lngSW').val(lngSW);	
	
		var arrayComponents = thisplace.address_components;
		for (index = 0; index < arrayComponents.length; ++index) {
			if (arrayComponents[index].types[0]=='place_id'){
				$('#place_id').val(arrayComponents[index].short_name);
			}else if(arrayComponents[index].types[0]=='locality'){
				$('#place_name').val(arrayComponents[index].short_name);
			}else if(arrayComponents[index].types[0]=='administrative_area_level_1'){
				$('#place_adm_area').val(arrayComponents[index].short_name);
			}else if(arrayComponents[index].types[0]=='country'){
				$('#place_country').val(arrayComponents[index].short_name);
			}
		}
    });
	google.maps.event.addDomListener(input, 'keydown', function (e) {
		if (e.keyCode === 13) {
			if (location_being_changed) {
				e.preventDefault();
				e.stopPropagation();
			}
		} else {
			// the user is typing
			location_being_changed = true;
		}
	});
</script>
<script>
	$(document).ready(function(){
		$('.hotel-service-hover>a').click(function(e){
			e.preventDefault();
			$('.hotel-services-submenu').slideToggle('fast').toggleClass('dnone');
		});
	});

	$('.hotel-switch').click(function(e){
		e.preventDefault();
		$('.shop-switch').removeClass('switch-active');
		$(this).addClass('switch-active');
		$('.shop-header').addClass('h1bittop');
		$('.shop-subheader').addClass('h2bittop');
		$('#start-date, #end-date').attr('disabled', true);
		setTimeout(
			function()
			{
				$('.shop-header').text('Search for hotels you love');
				$('.shop-subheader').text('Among cities around the world');
				$('.shop-header').removeClass('h1bittop');
				$('.shop-subheader').removeClass('h2bittop');
			}, 500);
	});

	$('.offer-switch').click(function(e){
		e.preventDefault();
		$('.shop-switch').removeClass('switch-active');
		$(this).addClass('switch-active');
		$('.shop-header').addClass('h1bittop');
		$('.shop-subheader').addClass('h2bittop');
		$('#start-date, #end-date').removeAttr('disabled');
		setTimeout(
			function()
			{
				$('.shop-header').text('Search the offers you love');
				$('.shop-subheader').text('Among thousands of cities and hotels around the globe');
				$('.shop-header').removeClass('h1bittop');
				$('.shop-subheader').removeClass('h2bittop');
			}, 500);
	});
</script>