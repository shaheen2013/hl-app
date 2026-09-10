<?php include LANG . $_SESSION['userLang'] . '/home-menu.php' ?>
<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li class="guest-dashboard-btn"><a href="<?php echo $urlTree['hotel-home'] ?>" title="Guests"><?php echo $HomeMenuLang['Guests'] ?></a></li>
	<li class="campaigns-dashboard-btn"><a href="<?php echo $urlTree['campaigns-dashboard'] ?>" title="Campaigns"><?php echo $HomeMenuLang['Campaigns dashboard'] ?></a></li>
	<li class="reputation-dashboard-btn"><a href="<?php echo $urlTree['reputation-dashboard'] ?>" title="Reputation"><?php echo $HomeMenuLang['Reputation dashboard'] ?></a></li>
	<li class="referrals-dashboard-btn"><a href="<?php echo $urlTree['referrals-dashboard'] ?>" title="Referrals"><?php echo $HomeMenuLang['Referrals dashboard'] ?></a></li>
	<li class="leads-dashboard-btn"><a href="<?php echo $urlTree['leads-dashboard'] ?>" title="Leads"><?php echo $HomeMenuLang['Web leads dashboard'] ?></a></li>
	<li class="guest-trends-btn"><a href="<?php echo $urlTree['guest-trends'] ?>" title="Trends"><?php echo $HomeMenuLang['Guest trends dashboard'] ?></a></li>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile dropdown-toggle" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	<li><a href="<?php echo $urlTree['hotel-home'] ?>" title="Guests"><?php echo $HomeMenuLang['Guests'] ?></a></li>
	<li><a href="<?php echo $urlTree['campaigns-dashboard'] ?>" title="Campaigns"><?php echo $HomeMenuLang['Campaigns dashboard'] ?></a></li>
	<li><a href="<?php echo $urlTree['reputation-dashboard'] ?>" title="Reputation">Reputation</a></li>
	<li><a href="<?php echo $urlTree['referrals-dashboard'] ?>" title="Referrals"><?php echo $HomeMenuLang['Referrals dashboard'] ?></a></li>
	<li><a href="<?php echo $urlTree['leads-dashboard'] ?>" title="Leads"><?php echo $HomeMenuLang['Web leads dashboard'] ?></a></li>
	<li><a href="<?php echo $urlTree['guest-trends'] ?>" title="trends"><?php echo $HomeMenuLang['Guest trends'] ?></a></li>
  </ul>
</div>