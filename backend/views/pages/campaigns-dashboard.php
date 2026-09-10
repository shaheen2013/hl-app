<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/campaigns-dashboard.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-tachometer"></i></i> <?php echo $CampaignsDashboardLang['Campaigns Dashboard'] ?></h1>
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
					<h5><?php echo $CampaignsDashboardLang['Total campaigns by month'] ?></h5>
					<?php if(!empty($totalCampaignsByMonth)){ ?>
						<div class="hlChart" id="campaign-progression-chart"></div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item w3" tabindex="1">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $CampaignsDashboardLang['Retention Vs Adquisition by month'] ?></h5>
					<?php if(!empty($retentionVSAdquisitionByMonth)){ ?>
						<div class="hlChart" id="campaign-retVsAdq-chart"></div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item w3" tabindex="2">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $CampaignsDashboardLang['Most campaigns redeemed by category'] ?></h5>
					<?php if(!empty($campaignsByCategory)){ ?>
					<div class="hlChart" id="campaigns-category-chart"></div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item w3" tabindex="3">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $CampaignsDashboardLang['Most campaigns redeemed by generation'] ?></h5>
					<?php if(!empty($mostRedeemedByGeneration)){ ?>
					<div class="hlChart" id="campaigns-generation-chart"></div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item w3" tabindex="4">
				<div class="white-module drag-module module-overflow">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<h5><?php echo $CampaignsDashboardLang['The most Redeemed campaigns last month'] ?></h5>
					<?php if(!empty($mostRedeemed)){ ?>
						<div class="table-responsive mt relative">
							<table class="table table-striped">
								<tr class="table-header">
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Name'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Redeemed'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Method'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Category'] ?></span
									></td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Start'] ?></span
									></td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['End'] ?></span
									></td>
								</tr>
								<?php foreach ($mostRedeemed as $most) { ?>
								<tr class="table-row">
									<td>
									<a href="<?php echo $urlTree['oferta'] . '/' .$most['nombre_san'] .'/' . $most['id']?>" title="<?php echo $most['nombre'] ?>"> <?php echo $most['nombre'] ?></a>
									</td>
									<td>
										<?php echo $most['n'] ?>
									</td>
									<td>
										<?php echo $most['method'] ?>
									</td>
									<td>
										<?php echo $most['categoria'] ?>
									</td>
									<td>
										<?php echo $most['inicio'] ?>
									</td>
									<td>
										<?php echo $most['fin'] ?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item w3" tabindex="5">
				<div class="white-module drag-module module-overflow">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<h5><?php echo $CampaignsDashboardLang['The most wishlisted campaigns'] ?></h5>
					<?php if(!empty($mostWishlisted)){ ?>
						<div class="table-responsive mt relative">
							<table class="table table-striped">
								<tr class="table-header">
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Name'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Wishlisted'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Method'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Category'] ?></span
									></td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['Start'] ?></span
									></td>
									<td>
										<span class="pull-left"><?php echo $CampaignsDashboardLang['End'] ?></span
									></td>
								</tr>
								<?php foreach ($mostWishlisted as $wish) { ?>
								<tr class="table-row">
									<td>
									<a href="<?php echo $urlTree['oferta'] . '/' .$wish['nombre_san'] .'/' . $wish['id']?>" title="<?php echo $wish['nombre'] ?>"> <?php echo $wish['nombre'] ?></a>
									</td>
									<td>
										<?php echo $wish['n'] ?>
									</td>
									<td>
										<?php echo $wish['method'] ?>
									</td>
									<td>
										<?php echo $wish['categoria'] ?>
									</td>
									<td>
										<?php echo $wish['inicio'] ?>
									</td>
									<td>
										<?php echo $wish['fin'] ?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item ih1 " tabindex="6">
				<div class="white-module data-module drag-module numberChart">
					<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $CampaignsDashboardLang['Total campaigns created'] ?></h5>
					<div class="text-center-chart">
						<i class="fa fa-gift fa-5x grisClaro"></i>
						<h2 class="chartNumberSmall"><?php echo $totalCampaignsCreated ?></h2>
					</div>
				</div>
			</div>
			<div class="item ih1" tabindex="7">
				<div class="white-module drag-module <?php echo(empty($retVSAdq) ? 'no-data-module' : '') ?>">
					<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $CampaignsDashboardLang['Retention VS Adquisition'] ?></h5>
					<div class="hlChart gaugesChart" id="repVsAdq-chart"></div>
					<?php if ($retVSAdq['ret'] > 0 || $retVSAdq['adq'] > 0) { ?>
						<div class="centered-data v-center">
							<div class="dualDataLeftSide pull-left">
								<i class="fa fa-diamond naranja fa-2x"></i>
								<h3 class="naranja"><?php echo $retVSAdq['ret'] ?></h3>
							</div>
							<div class="dualDataRightSide pull-right">
								<i class="fa fa-diamond azul fa-2x"></i>
								<h3 class="azul"><?php echo $retVSAdq['adq'] ?></h3>
							</div>
						</div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item ih1" tabindex="8">
				<div class="white-module data-module drag-module numberChart">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $CampaignsDashboardLang['Check in offers preferred hour'] ?></h5>
					<div class="text-center-chart">
						<i class="fa fa-clock-o fa-5x grisClaro"></i>
						<h2 class="chartNumberSmall"><?php echo $preferredHour ?></h2>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'packery.js'?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="<?php echo DIR_JS . 'charts-position.min.js'?>"></script>
<?php include LIB . 'campaigns-charts.php' ?>