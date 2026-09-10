<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/gestion-encuestas.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php //include top menu ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-check-square-o"></i> <?php echo $gestionencuestasLang['reputation management'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
			<?php if (!empty($arrayGestionEncuestas)) {?>
				<div class="clearfix"></div>
				<div class="table-responsive mt relative">
					<table class="table table-striped">
						<tr class="table-header">
							<td>
								<span class="pull-left"><?php echo $gestionencuestasLang['Name'] ?></span><a href="<?php echo $urlTree['gestion-encuestas'] ?>/?ord=nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $gestionencuestasLang['Date'] ?></span> <a href="<?php echo $urlTree['gestion-encuestas'] ?>/?ord=fecha" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $gestionencuestasLang['Comments'] ?></span>
							</td>
							<td>
								<span class="pull-left"><?php echo $gestionencuestasLang['Rating'] ?></span><a href="<?php echo $urlTree['gestion-encuestas'] ?>/?ord=puntuacion" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $gestionencuestasLang['Shared'] ?></span><a href="<?php echo $urlTree['gestion-encuestas'] ?>/?ord=visitas" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $gestionencuestasLang['Actions'] ?></span></a>
							</td>
						</tr>
						<?php foreach ($arrayGestionEncuestas as $encuesta) { ?>
						<tr class="table-row">
							<td>
								<?php if (!empty($encuesta['img'])){?>
								<img class="img-circle img-thumbnail" src="<?php echo $encuesta['img']; ?>" alt="user avatar" width="50" height="50">
								<?php }else {?>
								<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG;?>avatar.jpg" alt="user avatar" width="50" height="50">
								<?php } ?>
								<a href="<?php echo $encuesta['urlGuid']?>"><?php echo $encuesta['nombre']; ?></a>
							</td>
							<td>
								<?php echo $encuesta['fecha']; ?>
							</td>
							<td class="tableCommentCol">
								<p><small><strong><i class="fa fa-thumbs-o-up verde"></i> </strong></small>
									<?php echo $encuesta['comentario_pos']; ?></p>
									<p><small><strong><i class="fa fa-thumbs-o-down naranja"></i> </strong></small>
										<?php echo $encuesta['comentario_neg']; ?></p>
									</td>
									<td>
										<i class="fa fa-heart"></i> <?php echo $encuesta['puntuacion']; ?>
									</td>
									<td>
										<?php echo($encuesta['shared'] == '1' ? 'Yes' : 'No') ?>
									</td>
									<td>
										<div class="btn-group-vertical pull-right">
											<?php if($encuesta['shared'] == '1') {?>
											<a href="https://twitter.com/<?php echo $encuesta['twitter_user'] ?>/status/<?php echo $encuesta['id_share'] ?>" target="_blank" class="btn btn-default" title="<?php echo $gestionencuestasLang['go to tweet'] ?>"><i class="fa fa-twitter"></i></a>
											<?php } ?>
										</div>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<?php include TEMPLATES . 'paginacion-template.php'; ?>
					</div>
				</div>
			<?php } else { ?>
				<div class="text-center mt2 container no-data-msg">
					<i class="fa fa-check-square-o grisClaro fa-5x"></i>
					<h2><?php echo $gestionencuestasLang['No one did a survey for your hotel for now...'] ?></h2>
					<h4><?php echo $gestionencuestasLang['It will not take so long, please, be patient'] ?></h4>
					<h5><?php echo $gestionencuestasLang['By the way... did you checked in all your guests in hotelinking?'] ?></h5>
					<a href="<?php echo $urlTree['invitar-usuarios'] ?>/?alert=1" class="btn btn-lg btn-success mt2"><?php echo $gestionencuestasLang['Start inviting guests to your hotel'] ?></a>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
