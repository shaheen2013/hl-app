<?php include LANG . $_SESSION['userLang'] . '/user-top-bar.php' ?>
<header class="user-top-bar-header">
	<div class="user-top-bar">
		<div class="container relative">
			<div class="pull-left">
			<a href="<?php echo $urlTree['user-ofertas'] ?>" title="<?php echo $UserTopBarLang['Go to Rewards Online Shop'] ?>" class="user-logo pull-left"><img src="<?php echo DIR_IMG . 'logo-light.gif' ?>" height="30" alt="logo"></a>
			</div>
			<?php if(!empty($_SESSION['u_logueado'])){ ?>
			<div class="pull-right shop-user-avatar">
				<?php echo($totalEncuestas > 0 ? '<span class="badge alert-badge"><i class="fa fa-bell-o"></i></span> ' : '')?>
				<a href="#" class="shop-user-menu"><img class="img-circle pull-right" width="50" height="50" src="<?php echo (!empty($_SESSION['image']) ? $_SESSION['image'] : DIR_IMG . 'avatar.jpg') ?>" alt="user image"></a>
				<ul class="shop-user-submenu dnone">
					<li><a href="<?php echo $urlTree['user-ofertas'] ?>" title="your vouchers"><i class="fa fa-ticket"></i><span class="pl"><?php echo $UserTopBarLang['Your vouchers'] ?></span></a><span class="badge pull-right"><?php echo $totalVouchersBar ?></span></li>
					<li><a href="<?php echo $urlTree['user-profile'] ?>" title="your profile"><i class="fa fa-cog"></i><span class="pl"><?php echo $UserTopBarLang['Your profile'] ?></span></a></li>
					<li><a href="<?php echo $urlTree['logout'] ?>" title="log out" class="naranja"><i class="fa fa-power-off"></i><span class="pl"><?php echo $UserTopBarLang['Log out'] ?></span></a></li>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div>
</header>