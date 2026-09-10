<?php include LANG . $_SESSION['userLang'] . '/check-out-steps.php' ?>
<ul class="steps hidden-sm">
	<li><a href="<?php echo $urlTree['checkout'] ?>">
		<h4 class="list-group-item-heading"><?php echo $CheckOutStepsLang['Step 1'] ?></h4>
		<p class="list-group-item-text"><?php echo $CheckOutStepsLang['Find user in hotel'] ?></p>
	</a></li>
	<li><a href="<?php echo $urlTree['checkout-user'] ?>">
		<h4 class="list-group-item-heading"><?php echo $CheckOutStepsLang['Step 2'] ?></h4>
		<p class="list-group-item-text"><?php echo $CheckOutStepsLang['Check out user'] ?></p>
	</a></li>
	<li><a href="#">
		<h4 class="list-group-item-heading"><?php echo $CheckOutStepsLang['Step 3'] ?></h4>
		<p class="list-group-item-text"><?php echo $CheckOutStepsLang['Guest rating'] ?></p>
	</a></li>
</ul>

<div class="dropdown visible-sm hamburguer-btn pull-left">
<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	<li><a href="<?php echo $urlTree['checkout'] ?>">
		<h4 class="list-group-item-heading"><?php echo $CheckOutStepsLang['Step 1'] ?></h4>
		<p class="list-group-item-text"><?php echo $CheckOutStepsLang['Find user in hotel'] ?></p><br>
	</a></li>
	<li><a href="<?php echo $urlTree['checkout-user'] ?>">
		<h4 class="list-group-item-heading"><?php echo $CheckOutStepsLang['Step 2'] ?></h4>
		<p class="list-group-item-text"><?php echo $CheckOutStepsLang['Check out user'] ?></p><br>
	</a></li>
	<li><a href="#">
		<h4 class="list-group-item-heading"><?php echo $CheckOutStepsLang['Step 3'] ?></h4>
		<p class="list-group-item-text"><?php echo $CheckOutStepsLang['Guest rating'] ?></p>
	</a></li>  </ul>
</div>