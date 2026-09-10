<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-survey.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="user-utility-bar">
	<h1><i class="fa fa-check-square-o"></i> <?php echo $UserSurveyLang['Ready to make this survey!'] ?></h1>
</div>
<div class="container text-center" id="fullContainer">
	<div class="col-lg-12">
		<h1><?php echo $UserSurveyLang['You are about to publish an opinion for:'] ?></h1>
		<?php if(empty($datosHotel['logo'])){?>
		<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG; ?>logo.jpg" alt="<?php echo $datosHotel['nombre_hotel'] ?>" width="100" height="100">
		<?php }else{ ?>
		<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG_FICHA_HOTEL; ?><?php echo $datosHotel['id_hotel'] ?>/logo/<?php echo $datosHotel['logo'] ?>" alt="<?php echo $datosHotel['nombre_hotel'] ?>" width="100" height="100"></a>
		<?php } ?>
		<h2><?php echo $datosHotel['nombre_hotel'] ?></h2>
	</div>
	<div class="col-lg-12  mt2">
		<p><?php echo $UserSurveyLang['Esta petición de opinión la has recibido por tu estancia reciente en'] ?> <strong><?php echo $datosHotel['nombre_hotel'] ?></strong>. <?php echo $UserSurveyLang['Esperamos que hayas disfrutado y que vuelvas pronto!'] ?></p>
	</div>
    <div class="col-lg-12  mt2 text-center">
        <a href="<?php echo $urlTree['user-survey-2'] ?>/?id=<?php echo $datosHotel['id_encuesta'] ?>" class="btn btn-lg btn-success"><i class="fa fa-question-circle"></i> <?php echo $UserSurveyLang['Publicar opinión'] ?></a>
        </div>
	<div class="col-lg-12 mt2 text-left">
		<ul class="mt">
			<li><?php echo $UserSurveyLang['Termina la encuesta y te entregaremos'] ?> <strong><?php echo $arrayPuntos['survey'] ?> <i class="rubies rubiesHL rubix1"rubies></i></strong> <?php echo $UserSurveyLang['para que los disfrutes en hotelinking'] ?></li>
			<li class="mt"><?php echo $UserSurveyLang['Cuando termines la encuesta podrás compartirla con tus amigos y te entregaremos'] ?> <strong><?php echo $arrayPuntos['shr_survey'] ?> <i class="rubies rubiesHL rubix1">rubies</i></strong> <?php echo $UserSurveyLang['adicionales por compartirla con tus amigos'] ?></li>
			<li class="mt"><?php echo $UserSurveyLang['Por cada amigo que utilice los enlaces que has compartido y reserve en el hotel te entregaremos'] ?> <strong><?php echo $arrayPuntos['referral'] ?> <i class="rubies rubiesHL rubix1">rubies</i></strong> <?php echo $UserSurveyLang['More'] ?></li>
		</ul>
	</div>
</div>