<div class="mainContent chartsContainer">
    <div class="col-lg-12">
    	<h1 class="mb2 text-center">Estadísticas para el hotel: <strong><?php echo $hotelStatistics['hotelName'] ?></strong></h1>
        <?php include TEMPLATES . 'private/top-menu.php'?>
        <div class="mainContent mt2" id="homeContainer">
            <div class="column-width"></div>
            <!-- Iframe Pre stay -->
            <div class="item">
                <div class="white-module">
                    <h5>Iframe Pre stay</h5>
                    <div class="hlChart gaugesChart" id="pre-stay-opens">
                    </div>
					<div class="centered-data v-center">
						<span class="azul">ABIERTO</span>
						<h2 class="azul"><?php echo $hotelStatistics['pre_iframe_opens'] ?></h2>
						<span class="azul">VECES</span>
					</div>
                </div>
            </div>
            <!-- Iframe Pre stay -->
            <!-- Iframe share clicks -->
            <div class="item">
                <div class="white-module">
                    <h5>Login</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $fcWin ?></h2>
                        <h4 class="verde"><i class="fa fa-arrow-circle-o-up"></i> <?php echo $fcPercent ?>%</h4>
                        <p class="naranja">LOSS : <strong><?php echo $fcLoss ?></strong></p>
                    </div>
                </div>
            </div>
            <!-- Iframe share clicks -->
            <!-- Iframe show modal -->
            <div class="item">
                <div class="white-module">
                    <h5>Second click (optional)</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $scWin ?></h2>
                        <h4 class="verde"><i class="fa fa-arrow-circle-o-up"></i> <?php echo $scPercent ?>%</h4>
                        <p class="naranja">LOSS : <strong><?php echo $scLoss ?></strong></p>
                    </div>
                </div>
            </div>
            <!-- Iframe show modal -->
            <!-- Iframe Finally shared -->
            <div class="item">
                <div class="white-module">
                    <h5>Shares</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $shares ?></h2>
                        <h4 class="verde"><i class="fa fa-arrow-circle-o-up"></i> <?php echo $sharesPercent ?>%</h4>
                        <p class="naranja">LOSS : <strong><?php echo $sharesLoss ?></strong></p>
                    </div>
                </div>
            </div>
            <!-- Iframe Finally shared -->
            <!-- cancels -->
            <div class="item">
                <div class="white-module">
                    <h5>First time Cancels</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $cancels ?></h2>
                        <h4 class="verde">REINTENTS: <?php echo $reintents ?></h4>
                    </div>
                </div>
            </div>
            <!-- cancels -->
            <!-- permissions -->
            <div class="item">
                <div class="white-module">
                    <h5>Denied permissions</h5>
                    <div class="centered-data v-center" style="width:80%">
                        <p class="naranja">PUBLIC PROFILE: <strong><?php echo $publicProfile ?></strong></p>
                        <p class="naranja">EMAIL: <strong><?php echo $email ?></strong></p>
                        <p class="naranja">USER FRIENDS: <strong><?php echo $userFriends ?></strong></p>
                        <p class="naranja">PUBLISH ACTIONS: <strong><?php echo $publishActions ?></strong></p>
                    </div>
                </div>
            </div>
            <!-- permissions -->
        </div>
    </div>
</div>
<script src="<?php echo DIR_JS . 'packery.js'?>"></script>
<script src="<?php echo DIR_JS . 'jquery-ui-1.9.2.custom.min.js'?>"></script>
<?php include LIB . 'home-charts-private-statistics.php' ?>
<script src="https://code.highcharts.com/highcharts.js"></script>