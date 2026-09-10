<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/leads-dashboard.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'home-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-tachometer"></i> <?php echo $LeadsDashboardLang['Leads Dashboard'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent chartsContainer" id="homeContainer">
			<div class="column-width"></div>
			<div class="item w3" tabindex="0">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $LeadsDashboardLang['Leads by month'] ?></h5>
					<?php if(!empty($leadZero)){ ?>
					<div class="hlChart" id="leads-progression-chart"></div>
					<?php }else{ ?>
					<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item w3" tabindex="1">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $LeadsDashboardLang['Funnel of conversions'] ?></h5>
					<?php if(!empty($totZero)){?>
					<div class="hlChart" id="funnel-chart"></div>
					<?php }else{ ?>
					<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'packery.js'?>"></script>
<script src="<?php echo DIR_JS . 'charts-position.min.js'?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/funnel.js"></script>
<?php include LIB . 'lead-charts.php' ?>