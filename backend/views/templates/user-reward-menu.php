<?php include LANG . $_SESSION['userLang'] . '/user-reward-menu.php' ?>

<div class="dropdown pull-right mt30">
	<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		<?php echo $UserRewardMenuLang['More reward options'] . ' ' ?>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		<li><a href="<?php echo $urlTree['user-points'] ?>" title="Your rewards points"><?php echo $UserRewardMenuLang['Your reward points'] ?></a></li>
		<li><a href="#" title="Give your rewards to a friend" class="give-rewards"><?php echo $UserRewardMenuLang['Give rewards to a friends'] ?></a></li>
		<li><a href="<?php echo $urlTree['user-points-log'] ?>" title="Your rewards log"><?php echo $UserRewardMenuLang['Your reward log'] ?></a></li>
	</ul>
</div>