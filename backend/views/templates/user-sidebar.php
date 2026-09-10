<div id="sidebar-wrapper">
	<div id="user-wrapper">
		<div class="user-image-sb pull-left">
			<img class="img-circle" width="50" height="50" src="<?php echo (!empty($_SESSION['image']) ? $_SESSION['image'] : DIR_IMG . 'avatar.jpg') ?>" alt="user image">
		</div>
		<p class="pull-left"><?php echo $_SESSION['name']; ?></p>
		<a href="user-profile" class="hasTooltip pull-right" data-toggle="tooltip" data-placement="right" title="Access to your profile"><i class="fa fa-cog"></i></a>
	</div>
	<div class="list-group mt2">
		<a href="<?php echo $urlTree['tienda'] ?>" class="list-group-item"><i class="fa fa-shopping-cart"></i><span class="pl">rewards shop</span></a>
		<a href="<?php echo $urlTree['user-points'] ?>" class="list-group-item"><i class="rubies rubix1">rubies</i><span class="pl">points management</span></a>
		<a href="<?php echo $urlTree['user-survey-list'] ?>" class="list-group-item"><i class="fa fa-check-square-o"></i><span class="pl">your surveys</span><?php echo($totalEncuestas > 0 ? '<span class="badge">'.$totalEncuestas.'</span>' : '')?></a>
		<a href="<?php echo $urlTree['user-wishlist'] ?>" class="list-group-item"><i class="fa fa-list"></i><span class="pl">your wishlist</span></a>
		<a href="<?php echo $urlTree['user-ofertas'] ?>" class="list-group-item sidebar-active"><i class="fa fa-ticket"></i><span class="pl">your vouchers</span></a>
		<a href="<?php echo $urlTree['logout'] ?>" class="list-group-item" title="Log out"><i class="fa fa-power-off"></i><span class="pl">Log out</span></a>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.hasTooltip').tooltip({
			container: "body"
		});
	})
</script>