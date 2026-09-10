<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/referrals-dashboard.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-tachometer"></i> <?php echo $ReferralsDashboardLang['Referrals Dashboard'] ?></h1>
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
					<h5><?php echo $ReferralsDashboardLang['Total check-ins from referrals'] ?></h5>
					<?php if(!empty($refZero)) {?>
						<div class="hlChart" id="checkins-progression-chart"></div>
					<?php }else{ ?>
						<div class="no-data-module"></div>
					<?php } ?>
				</div>
			</div>
			<div class="item w3" tabindex="1">
				<div class="white-module drag-module">
					<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $ReferralsDashboardLang['Landing conversion by month'] ?></h5>
						<?php if(!empty($lanZero)) {?>
							<div class="hlChart" id="landing-conversion-progression-chart"></div>
						<?php }else{ ?>
							<div class="no-data-module"></div>
						<?php } ?>
				</div>
			</div>
			<div class="item" tabindex="2">
				<div class="white-module drag-module">
					<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $ReferralsDashboardLang['Referrals by social media'] ?></h5>
					<div class="hlChart gaugesChart" id="sm-referrals-chart"></div>
					<?php if($referralsBySocialMedia['tw'] == 0 && $referralsBySocialMedia['fb'] == 0 && $referralsBySocialMedia['in'] == 0 && $referralsBySocialMedia['tw'] == 0){ ?>
						<div class="no-data-module"></div>
					<?php }else{ ?>
						<div class="centered-data v-center">
							<div class="dualDataLeftSide pull-left">
								<i class="fa fa-facebook azul2 fa-2x"></i>
								<h3 class="azul"><?php echo $referralsBySocialMedia['fb'] ?>%</h3>
								<i class="fa fa-twitter azul fa-2x"></i>
								<h3 class="azul"><?php echo $referralsBySocialMedia['tw'] ?>%</h3>
							</div>
							<div class="dualDataRightSide pull-right">
								<i class="fa fa-instagram azul fa-2x"></i>
								<h3 class="azul"><?php echo $referralsBySocialMedia['in'] ?>%</h3>
								<i class="fa fa-linkedin azul fa-2x"></i>
								<h3 class="azul"><?php echo $referralsBySocialMedia['li'] ?>%</h3>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="item" tabindex="3">
				<div class="white-module drag-module">
					<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<!-- <div class="no-data-module"></div> -->
					<h5><?php echo $ReferralsDashboardLang['Landing conversion'] ?></h5>
					<div class="text-center-chart">
						<i class="fa fa-desktop fa-5x grisClaro"></i>
						<h2 class="chartNumberSmall"><?php echo $landingConversion ?></h2>
					</div>
				</div>
			</div>
			<div class="item w3" tabindex="4">
				<div class="white-module drag-module module-overflow">
					<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
					<h5><?php echo $ReferralsDashboardLang['Top ten referrers'] ?></h5>
					<?php if(!empty($top10Referrers)){ ?>
						<div class="table-responsive mt relative">
						<table class="table table-striped">
							<tr class="table-header">
								<td>
									<span class="pull-left"><?php echo $ReferralsDashboardLang['Name'] ?></span>
								</td>
								<td>
									<span class="pull-left"><?php echo $ReferralsDashboardLang['Localization'] ?></span>
								</td>
								<td>
									<span class="pull-left"><?php echo $ReferralsDashboardLang['Referrals'] ?></span>
								</td>
							</tr>
							<?php foreach ($top10Referrers as $referrer) { ?>
								<tr class="table-row">
									<td>
											<img class="img-circle img-thumbnail" src="<?php echo ($referrer['img'] == '0' ? DIR_IMG . 'avatar.jpg' : $referrer['img'])?>" width="40" height="40"><span class="pl"><a href="<?php echo $referrer['urlGuid'] ?>"><?php echo $referrer['nombre'] ?></a></span>
									</td>
									<td>
										<?php echo $referrer['location'] ?>
									</td>
									<td>
										<?php echo $referrer['referrals'] ?>
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
					<h5><?php echo $ReferralsDashboardLang['Top ten referrers by Earnings'] ?></h5>
					<?php if(!empty($top10ReferrersByEarnings)){ ?>
					<div class="table-responsive mt relative">
						<table class="table table-striped">
							<tr class="table-header">
								<td>
									<span class="pull-left"><?php echo $ReferralsDashboardLang['Name'] ?></span>
								</td>
								<td>
									<span class="pull-left"><?php echo $ReferralsDashboardLang['Localization'] ?></span>
								</td>
								<td>
									<span class="pull-left"><?php echo $ReferralsDashboardLang['Earnings'] ?></span>
								</td>
							</tr>
							<?php foreach ($top10ReferrersByEarnings as $earnings) { ?>
								<tr class="table-row">
									<td>
											<img class="img-circle img-thumbnail" src="<?php echo ($earnings['img'] == '0' ? DIR_IMG . 'avatar.jpg' : $earnings['img'])?>" width="40" height="40"><span class="pl"><a href="<?php echo $earnings['urlGuid'] ?>"><?php echo $earnings['nombre'] ?></a></span>
									</td>
									<td>
										<?php echo $earnings['location'] ?>
									</td>
									<td>
										<?php echo $earnings['earnings'] ?>
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
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'packery.js'?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="<?php echo DIR_JS . 'charts-position.min.js'?>"></script>
<?php include LIB . 'referrals-charts.php' ?>