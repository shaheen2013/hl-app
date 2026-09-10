<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-home.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-tachometer"></i> <?php echo $HotelHomeLang['Guests Dashboard'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent chartsContainer">

			<?php if(!empty($mostrarAlert) && $mostrarAlert){ ?>
			<div class="row">
				<div class="alert alert-info fade in text-center">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong><?php echo $HotelHomeLang['There is no data to show'] ?></strong><br><?php echo $HotelHomeLang['To start showing data, you have to invite guests on Hotelinking'] ?> <br>
					<a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" title="<?php echo $HotelHomeLang['invita tus clientes a hotelinking'] ?>" class="btn btn-default mt" id="homeAlertClose"><?php echo $HotelHomeLang['Invite guests in bulk'] ?></a>
					<div class="row mt">
						<p>We recommend you to create <strong><?php echo $NumOfertasHotel ?></strong> more campaigns</p>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<div class="mainContent" id="homeContainer">
				<div class="column-width"></div>

				<div class="item  w3" tabindex="0">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<h5><?php echo $HotelHomeLang['Rating progression'] ?></h5>
							<?php if(!empty($RatingProgression)) {?>
								<div class="hlChart" id="rating-progression-chart"></div>
							<?php }else{ ?>
								<div class="no-data-module"></div>
							<?php } ?>
					</div>
				</div>

				<div class="item  w100" tabindex="1">
					<div class="white-module drag-module <?php echo(empty($guestsCountries) ? 'no-data-module' : '') ?>">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<h5><?php echo $HotelHomeLang['Guest countries'] ?></h5>
						<?php if(!empty($guestsCountries)) {?>
						<div id="googleMap"></div>
						<?php }else{ ?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item  w3" tabindex="2">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<h5><?php echo $HotelHomeLang['Guest acquisition'] ?></h5>
						<?php if(!empty($newGuestsProgression)) {?>
							<div class="hlChart" id="new-customers-chart"></div>
						<?php }else{ ?>
							<div class="no-data-module"></div>
						<?php } ?>
					</div>
				</div>

				<div class="item" tabindex="3">
					<div class="white-module data-module drag-module numberChart">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<h5><?php echo $HotelHomeLang['Today new guests'] ?></h5>
						<div class="text-center-chart">
							<i class="fa fa-user-plus fa-5x grisClaro"></i>
							<h2 class="chartNumberSmall"><?php echo $todayNewGuests[0]; ?></h2>
						</div>
						<?php if($todayNewGuests[1] == 2){
							echo '<i class="fa fa-arrow-circle-o-up fa-2x verde increase"></i>';
						}else if($todayNewGuests[1] == 1){
							echo '<i class="fa fa-arrow-circle-o-down fa-2x naranja increase"></i>';
						}else{
							echo '<i class="fa fa-arrow-circle-o-right fa-2x increase"></i>';
						}
						?>
					</div>
				</div>

				<div class="item " tabindex="4">
					<div class="white-module data-module drag-module numberChart">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $HotelHomeLang['Average total nights per guest'] ?></h5>
						<div class="text-center-chart">
							<i class="fa fa-bed fa-5x grisClaro"></i>
							<h2 class="chartNumberSmall"><?php echo $nightsGuest[0] ?></h2>
						</div>
						<?php if($nightsGuest[1] == 2){
							echo '<i class="fa fa-arrow-circle-o-up fa-2x verde increase"></i>';
						}else if($nightsGuest[1] == 1){
							echo '<i class="fa fa-arrow-circle-o-down fa-2x naranja increase"></i>';
						}else{
							echo '<i class="fa fa-arrow-circle-o-right fa-2x increase"></i>';
						}
						?>
					</div>
				</div>

				<div class="item " tabindex="5">
					<div class="white-module data-module drag-module numberChart">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $HotelHomeLang['Average total spend per guest'] ?></h5>
						<div class="text-center-chart">
							<i class="fa fa-usd fa-5x grisClaro"></i>
							<h2 class="chartNumberSmall"><?php echo $spentGuest[0] ?></h2>
						</div>
						<?php if($spentGuest[1] == 2){
							echo '<i class="fa fa-arrow-circle-o-up fa-2x verde increase"></i>';
						}else if($spentGuest[1] == 1){
							echo '<i class="fa fa-arrow-circle-o-down fa-2x naranja increase"></i>';
						}else{
							echo '<i class="fa fa-arrow-circle-o-right fa-2x increase"></i>';
						}
						?>
					</div>
				</div>

				<div class="item " tabindex="6">
					<div class="white-module data-module drag-module numberChart">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $HotelHomeLang['Average reward points per guest'] ?></h5>
						<div class="text-center-chart">
							<i class="rubies rubix4">rubies</i>
							<h2 class="chartNumberSmall"><?php echo $pointsGuest[0] ?></h2>
						</div>
						<?php if($pointsGuest[1] == 2){
							echo '<i class="fa fa-arrow-circle-o-up fa-2x verde increase"></i>';
						}else if($pointsGuest[1] == 1){
							echo '<i class="fa fa-arrow-circle-o-down fa-2x naranja increase"></i>';
						}else{
							echo '<i class="fa fa-arrow-circle-o-right fa-2x increase"></i>';
						}
						?>
					</div>
				</div>

				<div class="item " tabindex="7">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $HotelHomeLang['Hotel overall reputation'] ?></h5>
						<div class="hlChart gaugesChart" id="overall-reputation-chart"></div>
						<div class="centered-data v-center">
							<i class="fa fa-heart azul fa-3x"></i>
							<h2 class="azul"><?php echo $overallReputation[0]; ?></h2>
						</div>
						<?php if($overallReputation[1] == 2){
							echo '<i class="fa fa-arrow-circle-o-up fa-2x verde increase"></i>';
						}else if($overallReputation[1] == 1){
							echo '<i class="fa fa-arrow-circle-o-down fa-2x naranja increase"></i>';
						}else{
							echo '<i class="fa fa-arrow-circle-o-right fa-2x increase"></i>';
						}
						?>

					</div>
				</div>

				<div class="item " tabindex="8">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $HotelHomeLang['Reputation by gender'] ?></h5>
						<div class="hlChart gaugesChart" id="gender-reputation-chart"></div>
						<?php if ($reputationByGender['m'] == 0 && $reputationByGender['h'] == 0) { ?>
							<div class="no-data-module"></div>
						<?php }else{ ?>
							<div class="centered-data v-center">
								<div class="dualDataLeftSide pull-left">
									<i class="fa fa-female azul3 fa-2x"></i>
									<h3 class="azul3"><?php echo $reputationByGender['m'] ?></h3>
								</div>
								<div class="dualDataRightSide pull-right">
									<i class="fa fa-male azul fa-2x"></i>
									<h3 class="azul"><?php echo $reputationByGender['h'] ?></h3>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>

				<div class="item " tabindex="9">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $HotelHomeLang['Reputation by age'] ?></h5>
						<div class="hlChart gaugesChart" id="age-reputation-chart"></div>
						<?php if($reputationByAge[0] == 0 && $reputationByAge[1] == 0 && $reputationByAge[2] == 0 && $reputationByAge[3] == 0){ ?>
							<div class="no-data-module"></div>
						<?php }else{ ?>
							<div class="centered-data v-center">
								<div class="dualDataLeftSide pull-left">
									<span class="naranja">0-17</span>
									<h3 class="naranja"><?php echo $reputationByAge[0]; ?></h3>
									<span class="verde">24-48</span>
									<h3 class="verde"><?php echo $reputationByAge[2]; ?></h3>
								</div>
								<div class="dualDataRightSide pull-right">
									<span class="marron">18-23</span>
									<h3 class="marron"><?php echo $reputationByAge[1]; ?></h3>
									<i class="fa fa-chevron-up azul"></i><span class="azul"> 49</span>
									<h3 class="azul"><?php echo $reputationByAge[3]; ?></h3>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="item " tabindex="10">
					<div class="white-module drag-module">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<!-- <div class="no-data-module"></div> -->
						<h5><?php echo $HotelHomeLang['Social media reach'] ?></h5>
						<div class="text-center-chart">
							<i class="fa fa-share-alt fa-5x grisClaro"></i>
							<h2 class="chartNumberSmall"><?php echo $socialMediaReach[0] ?></h2>
						</div>
						<?php if($socialMediaReach[1] == 2){
							echo '<i class="fa fa-arrow-circle-o-up fa-2x verde increase"></i>';
						}else if($socialMediaReach[1] == 1){
							echo '<i class="fa fa-arrow-circle-o-down fa-2x naranja increase"></i>';
						}else{
							echo '<i class="fa fa-arrow-circle-o-right fa-2x increase"></i>';
						}
						?>
					</div>
				</div>
				<div class="item  w3" tabindex="11">
					<div class="white-module drag-module module-overflow <?php echo(empty($top10Spendings) ? 'no-data-module' : '') ?>">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<h5><?php echo $HotelHomeLang['Top 10 guests by total spend'] ?></h5>
						<div class="table-responsive mt relative">
							<table class="table table-striped">
								<tr class="table-header">
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Name'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Location'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Total spend'] ?></span>
									</td>
								</tr>
								<?php foreach ($top10Spendings as $user) { ?>
								<tr class="table-row">
									<td>
										<img class="img-circle img-thumbnail" src="<?php echo ($user['img'] == '0' ? DIR_IMG . 'avatar.jpg' : $user['img'])?>" width="40" height="40"><span class="pl"><a href="<?php echo $user['urlGuid'] ?>"><?php echo $user['nombre'] ?></a></span>
									</td>
									<td>
										<?php echo $user['location'] ?>
									</td>
									<td>
										<?php echo $user['spendings'] ?> $
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
				<div class="item  w3" tabindex="12">
					<div class="white-module drag-module module-overflow <?php echo(empty($top10Checkins) ? 'no-data-module' : '') ?>">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<h5><?php echo $HotelHomeLang['Top ten guests by number of check-ins'] ?></h5>
						<div class="table-responsive mt relative">
							<table class="table table-striped">
								<tr class="table-header">
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Name'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Location'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Check-ins'] ?></span>
									</td>
								</tr>
								<?php foreach ($top10Checkins as $user) { ?>
								<tr class="table-row">
									<td>
										<img class="img-circle img-thumbnail" src="<?php echo ($user['img'] == '0' ? DIR_IMG . 'avatar.jpg' : $user['img'])?>" width="40" height="40"><span class="pl"><a href="<?php echo $user['urlGuid'] ?>"><?php echo $user['nombre'] ?></a></span>
									</td>
									<td>
										<?php echo $user['location'] ?>
									</td>
									<td>
										<?php echo $user['checkins'] ?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
				<div class="item  w3" tabindex="13">
					<div class="white-module drag-module module-overflow <?php echo(empty($top10Nights) ? 'no-data-module' : '') ?>">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<h5><?php echo $HotelHomeLang['Top ten guests by number of nights'] ?></h5>
						<div class="table-responsive mt relative">
							<table class="table table-striped">
								<tr class="table-header">
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Name'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Location'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Total nights'] ?></span>
									</td>
								</tr>
								<?php foreach ($top10Nights as $user) { ?>
								<tr class="table-row">
									<td>
										<img class="img-circle img-thumbnail" src="<?php echo ($user['img'] == '0' ? DIR_IMG . 'avatar.jpg' : $user['img'])?>" width="40" height="40"><span class="pl"><a href="<?php echo $user['urlGuid'] ?>"><?php echo $user['nombre'] ?></a></span>
									</td>
									<td>
										<?php echo $user['location'] ?>
									</td>
									<td>
										<?php echo $user['noches'] ?>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
				<div class="item  w3" tabindex="14">
					<div class="white-module drag-module module-overflow <?php echo(empty($top10Points) ? 'no-data-module' : '') ?>">
						<a href="#" class="dragger-btn" title="drag this element"><i class="fa fa-arrows"></i></a>
						<h5><?php echo $HotelHomeLang['Top ten guests by reward points'] ?></h5>
						<div class="table-responsive mt relative">
							<table class="table table-striped">
								<tr class="table-header">
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Name'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Location'] ?></span>
									</td>
									<td>
										<span class="pull-left"><?php echo $HotelHomeLang['Reward points'] ?></span>
									</td>
								</tr>
								<?php foreach ($top10Points as $user) { ?>
								<tr class="table-row">
									<td>
										<img class="img-circle img-thumbnail" src="<?php echo ($user['img'] == '0' ? DIR_IMG . 'avatar.jpg' : $user['img'])?>" width="40" height="40"><span class="pl"><a href="<?php echo $user['urlGuid'] ?>"><?php echo $user['nombre'] ?></a></span>
									</td>
									<td>
										<?php echo $user['location'] ?>
									</td>
									<td>
										<?php echo $user['puntos'] ?> <i class="rubies rubix2">rubies</i>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo DIR_JS . 'packery.js'?>"></script>
<script src="<?php echo DIR_JS . 'jquery-ui-1.9.2.custom.min.js'?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<?php include LIB . 'home-charts.php' ?>
<script src="<?php echo DIR_JS . 'charts-position.min.js'?>"></script>
<?php if($url['dir1'] == $urlTree['hotel-home'] && (!empty($mostrarModal) && $mostrarModal)){ ?>
<script src="<?php echo DIR_JS . 'bootstrap-tour.min.js'?>"></script>
<script src="<?php echo DIR_JS . 'hotel-tour.min.js'?>"></script>
<?php } ?>

<script>
//user adquisition;
$(document).ready(function() {
	//Cierra la alerta superior permanentemente
	$('.see-whats-new-btn').click(function(e){
		e.preventDefault();
		$('#new-version-modal').modal('show');
	})
	$('#homeAlertClose').click(function(){
		var userId="<?php echo $_SESSION['h_logueado'] ?>";
		$.ajax({
			url: "/lib/home-actions.php",
			data: 'action=alertClose&userId='+ userId +'',
			type: 'POST',
			success: function() {
				window.location.href = "<?php echo $urlTree['gestion-usuarios'] ?>";
			}
		});
	});

	$('.tour-tour .btn').click(function(){
		if($(this).data('role') === 'end'){
			var userId="<?php echo $_SESSION['h_logueado'] ?>";
			$.ajax({
				url: "/lib/home-actions.php",
				data: 'action=modalClose&userId='+ userId +'',
				type: 'POST',
				success: function(data){
					console.log(data);
				}
			});
		}
	});
});
</script>