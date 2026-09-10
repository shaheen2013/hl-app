<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/checkin-paso1.php' ?>
<div id="wrapper">
	<?php if(!empty($_SESSION['h_logueado'])){
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(!empty($_SESSION['staff_logueado'])){
		include TEMPLATES . 'check-sidebar.php';
	} ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include(TEMPLATES . 'check-in-top-menu.php'); ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-sign-in"></i> <?php echo $lang['Checkin user'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12">
				<div class="col-lg-4 col-lg-offset-1">
					<h2 class="text-center"><?php echo $lang['Already using Hotelinking?'] ?></h2>
					<form action="<?php echo $urlTree['checkin-paso1'] ?>" class="mt4" method="POST">
						<label for="search"><?php echo $lang['Search user by email or membership id'] ?></label>
						<div class="input-group">
							<span class="input-group-addon">@</span>
							<input type="text" class="form-control input-lg" id="search" name="search" placeholder="<?php echo $lang['User email or membership code'] ?>">
						</div>
						<input type="submit" class="btn btn-primary btn-lg mt2" value="<?php echo $lang['Search Guest'] ?>">
					</form>
					<?php if (!empty($usuarioBuscado['id'])){ ?>
					<div id="userFound">
						<h3><?php echo $lang['User found'] ?></h3>
						<div class="col-lg-6">
							<div class="white-module noPadding fichaUsuario">
								<div class="overlayer absolute">
									<ul class="fichaOfertaUl">
										<li><a href="<?php echo $usuarioBuscado['urlGuid'] ?>" class="btn btn-hollow btn-lg ofertaDetailsBtn" data-toggle="tooltip" title="<?php echo $lang['View Guest Profile'] ?>"><i class="fa fa-eye"></i></a></li>
									</ul>
								</div>
								<img class="user-img" src="<?php echo (!empty($usuarioBuscado['img']) ? $usuarioBuscado['img'] : DIR_IMG . 'avatar.jpg' ) ?>" alt="<?php echo $usuarioBuscado['nombre'] ?>"/>
								<h4><?php echo $usuarioBuscado['nombre'] ?></h4>
								<div class="user-media col-lg-12 mt2">
									<span class="pull-left user-twitter media-info"><?php echo(!empty($usuarioBuscado['tw_followers']) ? '<i class="fa fa-twitter"></i>' . $usuarioBuscado['tw_followers'] : '')?>
									<span class="pull-left user-twitter media-info"><?php echo(!empty($usuarioBuscado['fb_friends']) ? '<i class="fa fa-facebook"></i>' . $usuarioBuscado['fb_friends'] : '')?>
									</span>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col-lg-6">
							<form action="<?php echo $url['dir1'] ?>" class="mt4" method="POST">
								<label for="givePoints"><?php echo $lang['Give points checkin'] ?></label>
								<?php if(empty($_SESSION['staff_logueado'])){ ?>
								<div class="input-group">
									<span class="input-group-addon"><i class="rubies rubix2">rubies</i></span>
									<input type="number" min="0" class="form-control input-lg" id="givePoints" name="givePoints" placeholder="0">
								</div>
								<?php } ?>
								<label for="checkIn" class="mt2"><?php echo $lang['checkin date'] ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
									<input type="text" class="form-control input-lg" id="checkInUser" name="checkIn" value="<?php echo (date ("d-m-Y")) ?>">
								</div>
								<input type="hidden" name="userId" value="<?php echo $usuarioBuscado['id'] ?>">
								<input type="submit" name="checkInBtn" class="btn btn-lg btn-success btn-block mt2" value="<?php echo $lang['Check-in guest now'] ?>">
							</form>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="col-lg-2 text-center">
					<h2><strong><?php echo $lang['Or'] ?></strong></h2>
				</div>
				<div class="col-lg-4">
					<h2 class="text-center"><?php echo $lang['Invite guest'] ?></h2>
					<form action="<?php echo $url['dir1'] ?>" class="mt4" method="POST">
						<div class="row">
							<div class="col-lg-12">
								<label for="inviteUser"><?php echo $lang['Invite user by email'] ?></label>
								<div class="input-group">
									<span class="input-group-addon">@</span>
									<input type="email" class="form-control input-lg" id="inviteUser" name="inviteUser" placeholder="User email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6  mt2">
								<label for="givePoints"><?php echo $lang['¿Give points for check in?'] ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="rubies rubix2">rubies</i></span>
									<input type="number" min="0" class="form-control input-lg" id="givePoints" name="givePoints" placeholder="0">
								</div>
							</div>
							<div class="col-lg-6  mt2">
								<label for="checkIn"><?php echo $lang['ckecking date invite'] ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
									<input type="text" class="form-control input-lg" id="checkInGuest" name="checkIn" value="<?php echo (date ("d-m-Y")) ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<input type="submit" class="btn btn-primary btn-lg mt2" value="<?php echo $lang['Send invite and Chek-in guest'] ?>">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include TEMPLATES . 'userHasCouponsModal.php'; ?>
<script>
	$(document).ready(function(){
		$('#checkInUser, #checkInGuest').datepicker({
			dateFormat: "dd-mm-yy"
		});
		$('.fichaUsuario').hover(function(){
			$(this).children('.overlayer').fadeToggle('fast');
		});
		<?php if(!empty ($tieneCupones) && $tieneCupones == 1){?>
			$('#userHasCouponsModal').modal('show');
			<?php } ?>
		});
	</script>