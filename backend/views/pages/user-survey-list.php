<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-survey-list.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="container">
		<div class="user-utility-bar">
			<h1 class="pull-left"><strong><i class="fa fa-check-square-o"></i> <?php echo $UserSurveyListLang['Check out your pending surveys'] ?></strong>
			</h1>
		</div>
		<div class="clearfix"></div>
	<div class="row">
		<div id="fullContainer">
			<?php if(!empty($arrayEncuestas)){ ?>
			<div class="table-responsive relative">
				<table class="table table-striped">
					<tr class="table-header">
						<td>
							<span class="pull-left"><?php echo $UserSurveyListLang['Hotel name'] ?></span><a href="<?php echo $urlTree['user-survey-list'] ?>/?ord=hotelName" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $UserSurveyListLang['Check-in date'] ?></span><a href="<?php echo $urlTree['user-survey-list'] ?>/?ord=chkin_date" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $UserSurveyListLang['Check-out date'] ?></span> <a href="<?php echo $urlTree['user-survey-list'] ?>/?ord=chkout_date" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $UserSurveyListLang['Rewards earned'] ?></span><a href="<?php echo $urlTree['user-survey-list'] ?>/?ord=puntos" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $UserSurveyListLang['State'] ?></span><a href="<?php echo $urlTree['user-survey-list'] ?>/?ord=done" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td> 
						<td>
							<span class="pull-right"><?php echo $UserSurveyListLang['Actions'] ?></span>
						</td>
					</tr>
					<?php foreach ($arrayEncuestas as $encuesta) { ?>
					<tr class="table-row">
						<td>
							<?php if (!empty($encuesta['logo'])){ ?>
							<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG_FICHA_HOTEL;?><?php echo $encuesta['id_hotel'] ?>/logo/<?php echo $encuesta['logo'] ?>" alt="user avatar" width="50" height="50"> <span class="pl"><a href="<?php echo $encuesta['urlGuid'] ?>"><?php echo $encuesta['hotelName'] ?></a> </span>
							<?php } else { ?>
							<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG;?>logo.jpg" alt="user avatar" width="50" height="50"><span class="pl"><a href="<?php echo $encuesta['urlGuid'] ?>"><?php echo $encuesta['hotelName'] ?></a> </span>
							<?php } ?>
						</td>
						<td>
							<?php echo $encuesta['chkin_date'] ?>
						</td>
						<td>
							<?php echo $encuesta['chkout_date'] ?>
						</td>
						<td>
							<i class="rubies rubix1">rubies</i> <?php echo $encuesta['puntos'] ?>
						</td>
						<td>
							<?php if($encuesta['done'] == 0){
								echo '<span class="naranja">pending</span>';
							}else{
								echo '<span class="verde">completed</span>';
							} ?>
						</td>
						<td>
							<?php if($encuesta['done'] == 0) { ?>
							<div class="btn-group pull-right">
								<a href="<?php echo $urlTree['user-survey'] ?>/?id=<?php echo $encuesta['id'] ?>" class="btn btn-default btn-success" title="Complete survey"><i class="fa fa-check-square-o"></i> <?php echo $UserSurveyListLang['Take survey'] ?></a>
							</div>
							<?php }else{ ?>
							<div class="pull-right"><i class="fa fa-heart pl"> </i> <?php echo $encuesta['rating']; if($encuesta['nShares'] == 0) { ?>
								<a class="btn btn-danger pl2" href="<?php echo $urlTree['user-survey-3'] ?>/?id=<?php echo $encuesta['id'] ?>">Share pending</a>
								<?php } ?>
							</div>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php include TEMPLATES . 'paginacion-template.php'; ?>
			<?php }else{ ?>
			<div class="text-center mt2">
				<i class="fa fa-check-square-o fa-5x grisClaro"></i>
				<h2><?php echo $UserSurveyListLang['There are no pending surveys'] ?></h2>
				<h4><?php echo $UserSurveyListLang['Visit hotels and earn points by giving your honest and kind opinion about them'] ?></h4>
				<h5><?php echo $UserSurveyListLang['Meanswhile maybe you are interested to know'] ?> <a href="<?php echo $urlTree['faq'] ?>" title="How to earn reward points"> <?php echo $UserSurveyListLang['how to earn reward points'] ?></a></h5>
			</div>
			<?php } ?>
		</div>
	</div>
</div>