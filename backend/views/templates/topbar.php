<?php include LANG . $_SESSION['userLang'] . '/topbar.php' ?>
<header class="publicTopBar">
		<div class="pull-left pl">
			<a href="http://hotelinking.com" title="hotelinking"><img src="<?php echo DIR_IMG?>topBarLogo.png" alt="logo hotelinking" width="142" height="28"></a>
		</div>
		<div class="pull-right pr">
			<span class="blanco"><?php echo $TopBarLang['¿Not an Hotelinking user?'] ?></span> <a href="register" title="register"><?php echo $TopBarLang['Sign up now'] ?></a> <span class="blanco"> <?php echo $TopBarLang['or'] ?></span> <a href="<?php echo $urlTree['login'] ?>" title="login"><?php echo $TopBarLang['Sign in'] ?></a>
		</div>
	<div class="clearfix"></div>
</header>