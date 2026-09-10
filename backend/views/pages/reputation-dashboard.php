<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/reputation-dashboard.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-tachometer"></i> <?php echo $ReputationDashboardLang['Reputation Dashboard'] ?></h1>
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
					<h5><?php echo $ReputationDashboardLang['Rating progression (Last 12 months)'] ?></h5>
					<?php if(!empty($ratingProgression)) {?>
						<div class="hlChart" id="rating-progression-chart"></div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>

			<div class="item w3" tabindex="1">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<h5><?php echo $ReputationDashboardLang['Surveys Progression (Last 12 months)'] ?></h5>
					<?php if(!empty($surveyProgression)) {?>
						<div class="hlChart" id="survey-progression-chart"></div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>

			<div class="item" tabindex="2">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $ReputationDashboardLang['Surveys Vs Checkouts'] ?></h5>
					<div class="hlChart gaugesChart" id="checkout-survey-chart"></div>
					<?php if($surveysVSCheckouts[0] > 0) {?>
					<div class="centered-data v-center">
						<i class="fa fa-check-square-o fa-3x azul"></i>
						<h2 class="azul"><?php echo $surveysVSCheckouts[0] ?>%</h2>
					</div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>

			<div class="item" tabindex="3">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $ReputationDashboardLang['Reputation by age'] ?></h5>
						<div class="hlChart gaugesChart" id="age-reputation-chart"></div>
						<?php if($reputationByAge[0] > 0 || $reputationByAge[1] > 0 || $reputationByAge[2] > 0 || $reputationByAge[3] > 0) {?>
						<div class="centered-data v-center">
							<div class="dualDataLeftSide pull-left">
								<span class="naranja">0-18</span>
								<h3 class="naranja"><?php echo $reputationByAge[0]; ?></h3>
								<i class="fa fa-chevron-up verde"></i><span class="verde"> 30</span>
								<h3 class="verde"><?php echo $reputationByAge[2]; ?></h3>
							</div>
							<div class="dualDataRightSide pull-right">
								<span class="marron">19-30</span>
								<h3 class="marron"><?php echo $reputationByAge[1]; ?></h3>
								<i class="fa fa-chevron-up azul"></i><span class="azul"> 55</span>
								<h3 class="azul"><?php echo $reputationByAge[3]; ?></h3>
							</div>
						</div>
						<?php }else{ ?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
			</div>

			<div class="item" tabindex="4">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $ReputationDashboardLang['Social media reach'] ?></h5>
						<div class="text-center-chart">
						<i class="fa fa-share-alt fa-5x grisClaro"></i>
						<h2 class="chartNumberSmall"><?php echo $smReach[0] ?></h2>
						</div>
				</div>
			</div>

			<div class="item" tabindex="5">
				<div class="white-module drag-module">
				<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $ReputationDashboardLang['Social media conversion'] ?></h5>
						<div class="text-center-chart">
						<i class="fa fa-check-circle-o fa-5x grisClaro"></i>
						<h2 class="chartNumberSmall"><?php echo $smConversion ?>%</h2>
						</div>
				</div>
			</div>

		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'packery.js'?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="<?php echo DIR_JS . 'charts-position.min.js'?>"></script>
<?php include LIB . 'reputation-charts.php' ?>