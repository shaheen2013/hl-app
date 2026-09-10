<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

	<?php include MODEL . 'gestion-encuestasModel.php'; ?>
	<?php include CONTROLLERS . 'gestion-encuestasController.php'; ?>
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div class="mainContent mt2">
			<div class="topMenu">
			<h1 class="pageTitle pull-left"><i class="fa fa-check-square-o"></i> Gestión de encuestas</h1>
		</div>
		<div class="col-lg-12">
			<div class="btn-group pull-left">
				<a href="<?php echo $urlTree['gestion-encuestas'] ?>" class="btn btn-default" title="ver en lista"><i class="fa fa-list"></i></a>
				<a href="<?php echo $url['dir1'] ?>" class="btn btn-default active" title="ver en timeline"><i class="fa fa-th-large"></i></a>
				<button class="btn btn-default"><i class="fa fa-circle verde"></i></button>
				<button class="btn btn-default"><i class="fa fa-circle naranja"></i></button>
			</div>
			<div class="pull-right">
				<form action="" action="POST">
					<input type="texto" class="form-control" id="exampleInputEmail1" placeholder="buscar...">
				</form>
			</div>
			<div class="clearfix"></div>
			<?php foreach ($arrayGestionEncuestas as $encuesta) { ?>
			<div class="white-module mt">
				<div class="media mt2">
					<div class="col-lg-2 col-sm-2 col-md-2 center-block">
						<?php if (!empty($encuesta['img'])){?>
						<img class="img-circle img-thumbnail center-block" src="<?php echo $encuesta['img']; ?>" alt="user avatar" width="60" height="60">
						<?php }else {?>
						<img class="img-circle img-thumbnail center-block" src="<?php echo DIR_IMG;?>avatar.jpg" alt="user avatar" width="60" height="60">
						<?php } ?>

						<small class="text-center center-block mt"><?php echo $encuesta['nombre']; ?></small>
						<div class="center-block text-center mt">
							<span class="fa fa-heart fa-5 dblock"></span>
							<span><?php echo $encuesta['puntuacion']; ?></span>
						</div>
					</div>
					<div class="col-lg-8 col-sm-8 col-md-8 userComment">
						<h4><?php echo $encuesta['fecha']; ?></h4>
						<p><small><strong><i class="fa fa-thumbs-o-up verde"></i> </strong></small>
						<?php echo $encuesta['comentario_pos']; ?></p>
						<p><small><strong><i class="fa fa-thumbs-o-down naranja"></i> </strong></small>
						<?php echo $encuesta['comentario_neg']; ?></p>
						<div class="userCommentForm"></div>
					</div>
					<div class="col-lg-2">
						<div class="list-group">
							<a href="#"  class="list-group-item" title="ver perfil"><i class="fa fa-eye"></i><span class="pl">Detalles del usuario</span></a>
							<a href="#"  class="list-group-item" title="regalar rubies"><i class="fa fa-ticket"></i><span class="pl">Regalar oferta</span></a>
							<a href="#"  class="list-group-item" title="hacer una oferta especial"><i class="fa fa-gift"></i><span class="pl">Regalar puntos</span></a>
							<a href="#"  class="list-group-item hotelResponseBtn" title="comentar"><i class="fa fa-comments-o"></i><span class="pl">Responder al usuario</span></a>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.hotelResponseBtn').click(function(e){
			var form = $(this).parent().parent().parent().children('.userComment').children('.userCommentForm');
			e.preventDefault();
			$('.userCommentForm').hide();
			$('.userCommentForm').empty();
			form.append('<div class="col-lg-1 col-sm-1 col-md-1 mt2"> <img class="img-circle img-thumbnail center-block" src="<?php echo DIR_IMG_FICHA_HOTEL ?>shamrock.jpg" alt="user avatar" width="60" height="60"> </div> <div class="col-lg-11 mt2"> <form action="" method="POST"> <textarea name="userCommentField" class="form-control" id="userCommentField" rows="1"></textarea> <input type="submit" class="btn btn-primary mt" value="enviar respuesta"></form> </div>').fadeIn();
		})
	});
</script>