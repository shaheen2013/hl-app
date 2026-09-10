<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-survey-thanks.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="user-utility-bar">
	<h1><i class="fa fa-check-circle-o"></i> <?php echo $UserSurveyThanksLang['Thanks for making this survey!!'] ?></h1>
</div>
		<div class="mainContent mt2" id="fullContainer">
			<div class="container text-center">
				<i class="fa fa-check-circle fa-6x verde"></i>
				<h2><strong><?php echo $UserSurveyThanksLang['Muchas gracias!'] ?></strong><br><br><?php echo $UserSurveyThanksLang['acabas de ganar'] ?> <i class="rubies rubiesHL rubix3">rubies</i><?php echo $UserSurveyThanksLang['de hotelinking'] ?></h2>
				<h3><?php echo $UserSurveyThanksLang['Por cada amigo que utilice los enlaces que has compartido y reserve en el hotel te entregaremos'] ?> <i class="rubies rubiesHL rubix2">rubies</i></strong> <?php echo $UserSurveyThanksLang['rubies más'] ?></h3>
				<a href="<?php echo $urlTree['tienda'] ?>" title="panel de control" class="btn btn-lg btn-primary mt"><?php echo $UserSurveyThanksLang['Volver a la página de inicio'] ?></a>
			</div>
		</div>
