<?php $this->layout('_layout::index');?>

<?php include LANG . $_SESSION['userLang'] . '/logout.php' ?>
<div class="logout-background">
	<div class="logout-page">	
			<img class="image-logout" src="<?php echo $this->asset('/public/images/logout_hotelinking.png') ?>" alt="logo" >
			<h2><?php echo $LogoutLang['you have been successfully loged out'] ?></h2>
			<a style="background-color:black!important" href="/" title="volver al inicio"  class="ui button primary-color bg"><?php echo $LogoutLang['Go back to Hotelinkings home page'] ?></a>
		</div>
	</div>
</div>