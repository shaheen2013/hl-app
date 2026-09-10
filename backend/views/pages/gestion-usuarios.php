<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/gestion-usuarios.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'invite-users-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-users"></i> <?php echo $lang['Guests Database'] ?></h1>
				<div class="breadcrumbs pull-right">
				<ul>
					<?php include (TEMPLATES .'breadcrumbs.php'); ?>
				</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
		<div class="col-lg-12">
			<?php if (!empty($arrayUsuarios)) { ?>
			<div class="clearfix"></div>
			<div class="table-responsive mt relative">
				<table class="table table-striped">
					<tr class="table-header">
						<td>
							<span class="pull-left"><?php echo $lang['Guest Name'] ?></span> <a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $lang['Nationality'] ?></span> <a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=location" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><i class="fa fa-twitter"></i></span> <a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=tw_followers" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><i class="fa fa-facebook"></i></span> <a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=fb_friends" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $lang['Reward points'] ?></span> <a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=puntos" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $lang['Total spending'] ?></span> <a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=total_spendings" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $lang['Total nights'] ?></span> <a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=total_nights" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $lang['Source'] ?></span><a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=invitador_nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<td>
							<span class="pull-left"><?php echo $lang['Status'] ?></span><a href="<?php echo $urlTree['gestion-usuarios'] ?>/?ord=status" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
						</td>
						<?php if($_SESSION['permisos']['LY'] == 1){ ?>
						<td>
							<span class="pull-right"><?php echo $lang['Actions'] ?></span></a>
						</td>
						<?php } ?>
					</tr>
					<?php foreach ($arrayUsuarios as $usuario) { ?>
					<tr class="table-row">
						<td>
						<?php if (!empty($usuario['img'])){?>
						<img class="img-circle img-thumbnail" src="<?php echo $usuario['img'] ?>" alt="user avatar" width="50" height="50">
						<?php }else {?>
						<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG;?>avatar.jpg" alt="user avatar" width="50" height="50">
						<?php } ?>
						<?php if($usuario['nombre'] == ''){ ?>
							<span class="pl"><?php echo $usuario['email'] ?><small> (temp name)</small></span>
						<?php } else{ ?>
							<a href="<?php echo $usuario['urlGuid'] ?>"><span class="pl"><?php echo $usuario['nombre'] ?></span></a>
						<?php } ?>
						</td>
						<td>
							<?php echo $usuario['location'] ?>
						</td>
						<td>
							<?php echo $usuario['tw_followers'] ?>
						</td>
						<td>
							<?php echo $usuario['fb_friends'] ?>
						</td>
						<td>
							<?php echo $usuario['puntos'] ?>
						</td>
						<td>
							<?php echo $usuario['total_spendings'] ?>
						</td>
						<td>
							<?php echo $usuario['total_nights'] ?>
						</td>
						<td>
							<?php if ($usuario['invitador_nombre'] != '-') {?>
								<a href="<?php echo $usuario['invitador_urlGuid'] ?>" title="<?php echo $usuario['invitador_nombre'] ?>"><?php echo $usuario['invitador_nombre'] ?></a>
							<?php } else { ?>
								You
							<?php } ?>
						</td>
						<td>
							<?php echo $usuario['status'] ?>
						</td>
						<?php if($_SESSION['permisos']['LY'] == 1) {?>
						<td>
							<div class="btn-group-vertical pull-right">
								<button type="button" class="btn btn-default give-rewards-to-user" data-id="<?php echo $usuario['id']?>" title="<?php echo $lang['Give reward points'] ?>"><i class="rubies rubix2">rubies</i></button>
							</div>
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php include TEMPLATES . 'paginacion-template.php'; ?>
			<?php include TEMPLATES . 'give-rewards-user-modal.php'; ?>
			<?php } else { ?>
				<div class="text-center mt2 container no-data-msg">
					<i class="fa fa-users grisClaro fa-5x"></i>
					<h2>There´re no guests at this moment</h2>
					<h4><?php echo $lang['Right now you don´t have guests in your hotelinking account, please, send invites to them and start to adquire data'] ?></h4>
					<a href="<?php echo $urlTree['invitar-usuarios'] ?>/?alert=1" class="btn btn-lg btn-success mt2"><?php echo $lang['Send invites to your first guests'] ?></a>
				</div>
			<?php } ?>
		</div>
		</div>
	</div>
</div>
<?php include TEMPLATES . 'createInvitesModal.php'; ?>
<script>
$(document).ready(function(){
	$('.give-rewards-to-user').click(function(e){
		e.preventDefault();
		var userId = $(this).data('id');
		$('#give-rewards-user-modal').modal('show');
		$('#userId').val(userId);
	})
			$('.btnVideotutorial').click(function(e){
				e.preventDefault();
				$('#createInvitesModal').modal({
					show: true
				});
			})
})
</script>