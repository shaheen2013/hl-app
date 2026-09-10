<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/guest-trends.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'home-menu.php' ?>
            <img class="topbarLogo visible-xs visible-sm" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77"
                 height="70" alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-tachometer"></i> <?php echo $guestTrendsLang['Guest trends'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent chartsContainer">
			<div class="mainContent" id="homeContainer">
				<div class="column-width"></div>

				<div class="item w3" tabindex="0">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $guestTrendsLang['Hotel category trends'] ?></h5>
						<div class="hlChart gaugesChart" id="hotel-category-trends"></div>
						<?php if(empty($arrayEstrellas)) {?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item w3" tabindex="1">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $guestTrendsLang['Average price preference'] ?></h5>
						<div class="hlChart gaugesChart" id="average-price-trends"></div>
						<?php if(empty($arrayPrecio)) {?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item w3" tabindex="2">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $guestTrendsLang['Preferred hotels by users'] ?></h5>
						<div class="hlChart gaugesChart" id="preferred-hotels"></div>
						<?php if(empty($arrayTipoHotel)) {?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item w3" tabindex="3">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $guestTrendsLang['Preferred decoration by users'] ?></h5>
						<div class="hlChart gaugesChart" id="preferred-decoration"></div>
						<?php if(empty($arrayDecoracionHotel)) {?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item w3" tabindex="4">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $guestTrendsLang['Preferred rooms by users'] ?></h5>
						<div class="hlChart gaugesChart" id="preferred-rooms"></div>
						<?php if(empty($arrayTipoHab)) {?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item w3" tabindex="5">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $guestTrendsLang['Preferred rooms features by users'] ?></h5>
						<div class="hlChart gaugesChart" id="preferred-rooms-features"></div>
						<?php if(empty($arrayRoomFeatures)) {?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item w3" tabindex="6">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $guestTrendsLang['Preferred hotel services by users'] ?></h5>
						<div class="hlChart gaugesChart" id="preferred-hotel-services"></div>
						<?php if(empty($arrayHotelServices)) {?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item w3" tabindex="6">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $guestTrendsLang['Users would like to receive those kind of offers'] ?></h5>
						<div class="hlChart gaugesChart" id="preferred-offers"></div>
						<?php if(empty($arrayRecibirOfertas)) {?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'packery.js'?>"></script>
<script src="<?php echo DIR_JS . 'jquery-ui-1.9.2.custom.min.js'?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<?php include LIB . 'trends-charts.php' ?>
<script src="<?php echo DIR_JS . 'charts-position.min.js'?>"></script>