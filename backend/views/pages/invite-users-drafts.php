<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/invite-users-drafts.php' ?>
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
				<h1 class="pull-left"><i class="fa fa-list"></i> <?php echo $InviteUsersDraftsLang['Saved guest lists'] ?></p></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
	<div class="col-lg-12">
		<?php //colocar el ! para que se vea correctamente ?>
		<?php if (!empty($arrayListas)) { ?>
		<div class="clearfix"></div>
		<div class="table-responsive mt relative">
			<table class="table table-striped">
				<tr class="table-header">
					<td>
						<span class="pull-left"><?php echo $InviteUsersDraftsLang['Guest list name'] ?></span> <a href="<?php echo $urlTree['invite-users-drafts'] ?>/?ord=nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
					</td>
					<td>
						<span class="pull-left"><?php echo $InviteUsersDraftsLang['Created on'] ?></span> <a href="<?php echo $urlTree['invite-users-drafts'] ?>/?ord=fecha" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
					</td>
					<td>
						<span class="pull-left"><?php echo $InviteUsersDraftsLang['Users in this list'] ?></span> <a href="<?php echo $urlTree['invite-users-drafts'] ?>/?ord=n" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
					</td>
					<td>
						<span class="pull-right"><?php echo $InviteUsersDraftsLang['Actions'] ?></span></a>
					</td>
				</tr>
				<?php foreach ($arrayListas as $lista) { ?>
					<tr class="table-row">
						<td>
							<a href="<?php echo $urlTree['invitar-usuarios-2'].'/'.$lista['id'].'/' ?>" title="<?php echo $lista['nombre'] ?>"><?php echo $lista['nombre'] ?></a>
						</td>
						<td>
							<?php echo $lista['fecha'] ?>
						</td>
						<td>
							<?php echo $lista['n'] ?>
						</td>
						<td>
							<div class="btn-group pull-right">
								<a class="btn btn-warning deleteList" href="#" data-list="<?php echo $lista['id'] ?>"><i class="fa fa-times"></i></a>
							</div>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<?php include TEMPLATES . 'paginacion-template.php'; ?>
		<?php include TEMPLATES . 'confirm-delete-list.php' ?>
		<?php } else { ?>
				<div class="text-center mt2 container no-data-msg">
					<i class="fa fa-list grisClaro fa-5x"></i>
					<h2><?php echo $InviteUsersDraftsLang['Thers no lists at this moment'] ?></h2>
					<h4><?php echo $InviteUsersDraftsLang['You need to invite guests in order to put them into a new list'] ?></h4>
					<a href="<?php echo $urlTree['invitar-usuarios'] ?>/?alert=1" class="btn btn-lg btn-success mt2"><?php echo $InviteUsersDraftsLang['Start inviting guests to your hotel'] ?></a>
				</div>
		<?php } ?>
	</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.deleteList').click(function(e){
			e.preventDefault();
			var listId = $(this).data('list');
			$('.deleteListBtn').attr('href', "<?php echo $urlTree['invite-users-drafts'] ?>/?del="+ listId +"");
			$('#confirm-delete-list').modal('show');
		});
	})
</script>