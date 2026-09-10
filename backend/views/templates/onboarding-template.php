<?php include LANG . $_SESSION['userLang'] . '/onboarding-template.php' ?>
<section class="ob-overlayer">
	<div class="ob-modal center-block">
		<div class="ob-modal-content">
			<div class="col-sm-4 steps-btns">
				<div class="btn-group-bg">
					<div class="btn-group-vertical dblock">
						<button class="btn <?php echo($element == 'first_video' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['first_video'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?> <?php echo $OnboardingTemplateLang['Welcome to hotelinking'] ?></span> </button>
						<button class="btn <?php echo($element == 'basic_info' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['basic_info'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?><?php echo $OnboardingTemplateLang['Setup - Basic info'] ?></span> </button>
						<button class="btn <?php echo($element == 'hotel_profile' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['hotel_profile'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?><?php echo $OnboardingTemplateLang['Setup - Hotel profile'] ?></span></button>
						<button class="btn <?php echo($element == 'booking_info' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['booking_info'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?><?php echo $OnboardingTemplateLang['Setup - Booking info'] ?></span></button>
						<button class="btn <?php echo($element == 'landing_page' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['landing_page'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?><?php echo $OnboardingTemplateLang['Setup - Landing page'] ?></span></button>
						<button class="btn <?php echo($element == 'oferta_1' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['oferta_1'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?><?php echo $OnboardingTemplateLang['Create your first campaign'] ?></span></button>
						<button class="btn <?php echo($element == 'second_video' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['second_video'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?><?php echo $OnboardingTemplateLang['Invites tutorial'] ?></span></button>
						<button class="btn <?php echo($element == 'invite_send' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['invite_send'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?><?php echo $OnboardingTemplateLang['Send invites (optional)'] ?></span></button>
						<button class="btn <?php echo($element == 'launch' ? 'btn-primary' : 'btn-default') ?>"><span><?php echo($onboarding['launch'] == '1' ? '<i class="fa fa-check-circle-o pr verde"></i>' : '<i class="fa fa-circle-thin pr"></i>') ?><?php echo $OnboardingTemplateLang['Launch'] ?></span></button>
					</div>
				</div>
			</div>
			<div class="col-sm-8 text-center ob-main-content">
				<?php if($element == 'first_video') {?>
				<div class="ob-first_video">
					<div class="data-progress" data-progress="10"></div>
					<img src="<?php echo BASE_PATH . DIR_IMG . 'onboarding/welcome_img.jpg'?>" alt=" first video image" width="178" height="250">
					<h2><?php echo $OnboardingTemplateLang['Welcome to hotelinking!'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['Hi!,'] ?><br><?php echo $OnboardingTemplateLang['My name is Ana. In our first time in hotelinking i want to show you a quick glimpse on how this platform works.'] ?></p>
					<button class="btn btn-lg btn-primary mt2 first-video-btn" data-step="first_video" data-id="<?php echo $_SESSION['h_logueado'] ?>"><i class="fa fa-play-circle-o"></i> <?php echo $OnboardingTemplateLang['Watch the video buton'] ?></button>
					<div class="row mt2">
						<a href="<?php echo $urlTree['hotel-profile'] ?>" class="mt2 btn btn-success btn-onboarding-hidden-1"><i class="fa fa-chevron-right"></i> <?php echo $OnboardingTemplateLang['Next step 1 button'] ?></a>
					</div>
				</div>
				<?php } ?>

				<?php if($element == 'basic_info') {?>
				<div class="ob-basic_info">
					<div class="data-progress" data-progress="20"></div>
					<img src="<?php echo BASE_PATH . DIR_IMG . 'onboarding/profile_step.gif'?>" alt="basic info image" width="178" height="228">
					<h2><?php echo $OnboardingTemplateLang['Fill your basic info'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['In order to be a successfull hotelier in hotelinking your users needs to create engagement with you. You achieve that creating a good hotelinking profile.'] ?></p>
					<?php if($url['dir1'] != $urlTree['hotel-profile'] ){?>
					<a href="<?php echo $urlTree['hotel-profile'] ?>" class="btn btn-lg btn-primary mt2 basic-info-btn"><i class="fa fa-user"></i> <?php echo $OnboardingTemplateLang['Go to your basic profile'] ?></a>
					<?php }else{ ?>
					<button class="btn btn-lg btn-primary mt2 fill-basic-info-btn"><i class="fa fa-pencil-square-o"></i> <?php echo $OnboardingTemplateLang['Fill your basic info button'] ?></button>
					<?php } ?>
				</div>
				<?php } ?>

				<?php if($element == 'hotel_profile') {?>
				<div class="ob-hotel_profile">
					<div class="data-progress" data-progress="30"></div>
					<img src="<?php echo BASE_PATH . DIR_IMG . 'onboarding/profile_step.gif'?>" alt="basic info image" width="178" height="228">
					<h2><?php echo $OnboardingTemplateLang['Fill your hotel profile info'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['Make your hotel looks good!, fill the hotel profile carefully'] ?></p>
					<?php if($url['dir1'] != $urlTree['hotel-profile-2'] ){?>
					<a href="<?php echo $urlTree['hotel-profile-2'] ?>" class="btn btn-lg btn-primary mt2 basic-info-btn"><i class="fa fa-user"></i> <?php echo $OnboardingTemplateLang['Go to your hotel profile'] ?></a>
					<?php }else{ ?>
					<div class="btn-group mt2">
						<button class="btn btn-lg btn-default hotel-profile-btn"><i class="fa fa-play-circle-o"></i> <?php echo $OnboardingTemplateLang['Watch the video'] ?></button>
						<button class="btn btn-lg btn-primary fill-basic-info-btn"><i class="fa fa-pencil-square-o"></i> <?php echo $OnboardingTemplateLang['Fill your hotel profile'] ?></button>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				<?php if($element == 'booking_info') {?>
				<div class="ob-booking_info">
					<div class="data-progress" data-progress="40"></div>
					<img src="<?php echo BASE_PATH . DIR_IMG . 'onboarding/profile_step.gif'?>" alt="basic info image" width="178" height="228">
					<h2><?php echo $OnboardingTemplateLang['Fill your hotel booking info'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['Give your guests, info about how to book in your hotel'] ?></p>
					<?php if($url['dir1'] != $urlTree['hotel-profile-datos-de-reserva'] ){?>
					<a href="<?php echo $urlTree['hotel-profile-datos-de-reserva'] ?>" class="btn btn-lg btn-primary mt2 basic-info-btn"><i class="fa fa-user"></i> <?php echo $OnboardingTemplateLang['Go to your hotel booking info'] ?></a>
					<?php }else{ ?>
					<div class="btn-group mt2">
						<button class="btn btn-lg btn-default hotel-booking-btn"><i class="fa fa-play-circle-o"></i> <?php echo $OnboardingTemplateLang['Watch the video'] ?></button>
						<button class="btn btn-lg btn-primary fill-basic-info-btn"><i class="fa fa-pencil-square-o"></i> <?php echo $OnboardingTemplateLang['Fill your booking info'] ?></button>
					</div>
					<?php } ?>
				</div>
				<?php } ?>

				<?php if($element == 'landing_page') {?>
				<div class="ob-landing_page">
					<div class="data-progress" data-progress="50"></div>
					<img src="<?php echo BASE_PATH . DIR_IMG . 'onboarding/profile_step.gif'?>" alt="basic info image" width="178" height="228"> 
					<h2><?php echo $OnboardingTemplateLang['Create your landing page'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['Landing pages are useful for your guests entries'] ?></p>
					<?php if($url['dir1'] != $urlTree['hotel-profile-datos-landing'] ){?>
					<a href="<?php echo $urlTree['hotel-profile-datos-landing'] ?>" class="btn btn-lg btn-primary mt2 basic-info-btn"><i class="fa fa-user"></i> <?php echo $OnboardingTemplateLang['Go to your hotel landing page'] ?></a>
					<?php }else{ ?>
					<div class="btn-group mt2">
						<button class="btn btn-lg btn-default hotel-landing-btn"><i class="fa fa-play-circle-o"></i> <?php echo $OnboardingTemplateLang['Watch the video'] ?></button>
						<button class="btn btn-lg btn-primary fill-basic-info-btn"><i class="fa fa-pencil-square-o"></i> <?php echo $OnboardingTemplateLang['Fill your landing page'] ?></button>
					</div>
					<?php } ?>
				</div>
				<?php } ?>

				<?php if($element == 'oferta_1') {?>
				<?php if(($ofertaStep == 1 || $ofertaStep == 2) && $url['dir1'] == $urlTree['hotel-crear-oferta']) {?>
				<div class="ob-oferta-step-1">
					<?php switch ($element) {
						case 'oferta_1':
						echo '<div class="data-progress" data-progress="60"></div>';
						break;
					} ?>
					<?php switch ($element) {
						case 'oferta_1':
						echo '<img src="'.DIR_IMG.'onboarding/create_offers.gif" alt="Create offers image" width="212" height="221">';
						echo '<h2> '.$OnboardingTemplateLang["Create your first campaign title"].'</h2>';
						break;
					} ?>
					<p><?php echo $OnboardingTemplateLang['Campaigns are the backbone of hotelinking, create your first one to attract more guests'] ?></p>
					<?php if($url['dir1'] != $urlTree['hotel-crear-oferta'] ){?>
					<a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" class="btn btn-lg btn-primary mt2 basic-info-btn"><i class="fa fa-user"></i> <?php echo $OnboardingTemplateLang['Go and create your first campaign'] ?></a>
					<?php }else{ ?>
					<div class="btn-group mt2">
					<button class="btn btn-lg btn-default basic-info-btn"><i class="fa fa-play-circle-o"></i> <?php echo $OnboardingTemplateLang['Watch the video'] ?></button>
					<button class="btn btn-lg btn-primary fill-basic-info-btn"><i class="fa fa-pencil-square-o"></i> <?php echo $OnboardingTemplateLang['Edit campaign basic info'] ?></button>
					</div>
					<?php } ?>
				</div>
				<?php }else if($ofertaStep == 2 && $url['dir1'] == $urlTree['hotel-crear-detalle-oferta']) { ?>
				<div class="ob-oferta-step-2">
					<?php switch ($element) {
						case 'oferta_1':
						echo '<div class="data-progress" data-progress="60"></div>';
						break;
					} ?>
					<img src="<?php echo DIR_IMG.'onboarding/create_offer_detail.gif'?>" alt="Create offers image" width="208" height="209">
					<h2><?php echo $OnboardingTemplateLang['Edit campaing details'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['Good titles, images, and descriptions are key for a good offering'] ?></p>
					<?php if($url['dir1'] != $urlTree['hotel-crear-detalle-oferta'] && $url['dir1'] != $urlTree['hotel-crear-oferta'] ){?>
					<a href="<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>" class="btn btn-lg btn-primary mt2 basic-info-btn"><i class="fa fa-user"></i> <?php echo $OnboardingTemplateLang['Go to edit campaign details'] ?></a>
					<?php }else{ ?>
					<div class="btn-group mt2">
					<button class="btn btn-lg btn-default basic-info-btn"><i class="fa fa-play-circle-o"></i> <?php echo $OnboardingTemplateLang['Watch the video'] ?></button>
					<button class="btn btn-lg btn-primary fill-detail-info-btn"><i class="fa fa-pencil-square-o"></i> <?php echo $OnboardingTemplateLang['Edit campaign details'] ?></button>
					</div>
					<?php } ?>
				</div>
				<?php }else if($ofertaStep == 3 && $url['dir1'] == $urlTree['hotel-publicar-oferta']) {?>
				<div class="oferta-step-3">
					<?php switch ($element) {
						case 'oferta_1':
						echo '<div class="data-progress" data-progress="60"></div>';
						break;
					} ?>
					<img src="<?php echo DIR_IMG.'onboarding/offer_created.gif'?>" alt="Offer created image" width="179" height="208">
					<h2><?php echo $OnboardingTemplateLang['Perfect Job! your campaign has been created'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['Your campaign has been created'] ?></p>
					<div class="row mt2">
						<a href="<?php echo $urlTree['hotel-crear-oferta'] ?>" class="mt2 btn btn-success btn-onboarding-hidden-2"><i class="fa fa-chevron-right"></i> <?php echo $OnboardingTemplateLang['Next step 2'] ?></a>
					</div>
				</div>
				<?php } ?>
				<?php } ?>

				<?php if($element == 'second_video') {?>
				<div class="ob-second_video">
					<div class="data-progress" data-progress="90"></div>
					<img src="<?php echo DIR_IMG.'onboarding/invites_step.gif'?>" alt="invites image" width="172" height="216">
					<h2><?php echo $OnboardingTemplateLang['Guest invite video'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['Hi'] ?><br><?php echo $OnboardingTemplateLang['Almost ready! check out this awesome video on how to invite your guests'] ?></p>
					<button class="btn btn-lg btn-primary mt2 second-video-btn" data-step="second_video" data-id="<?php echo $_SESSION['h_logueado'] ?>"><i class="fa fa-play-circle-o"></i> <?php echo $OnboardingTemplateLang['Watch the video'] ?></button>
					<div class="row mt2">
						<a href="<?php echo $urlTree['hotel-profile'] ?>" class="mt2 btn btn-success btn-onboarding-hidden-3"><i class="fa fa-chevron-right"></i> <?php echo $OnboardingTemplateLang['Next step 3'] ?></a>
					</div>
				</div>
				<?php } ?>

				<?php if($element == 'invite_send') {?>
				<div class="ob-invite_send">
					<div class="data-progress" data-progress="99"></div>
					<img src="<?php echo DIR_IMG.'onboarding/invites_step_2.gif'?>" alt="invites image" width="249" height="177">
					<h2><?php echo $OnboardingTemplateLang['Invite to your first guests'] ?></h2>
					<p><?php echo $OnboardingTemplateLang['Now it´s time to invite your first guests'] ?></p>
					<button class="btn btn-lg btn-primary mt2 third-video-btn" data-step="invite_send" data-id="<?php echo $_SESSION['h_logueado'] ?>"><i class="fa fa-play-circle-o"></i> <?php echo $OnboardingTemplateLang['Watch the video'] ?></button>
					<div class="row dblock">
						<a href="<?php echo $urlTree['hotel-profile'] ?>" class="mt2 btn btn-success btn-onboarding-hidden-4"><i class="fa fa-chevron-right"></i> <?php echo $OnboardingTemplateLang['Next step 3'] ?></a>
					</div>
				</div>
				<?php } ?>

				<?php if($element == 'launch') {?>
				<div class="ob-invite_send">
					<div class="data-progress" data-progress="100"></div>
					<div class="row">
						<img src="<?php echo DIR_IMG.'onboarding/launch.gif'?>" alt="invites image" width="180" height="218">
						<h2><?php echo $OnboardingTemplateLang['You are ready to launch'] ?></h2>
						<p><?php echo $OnboardingTemplateLang['Now you are ready to launch your platform in hotelinking'] ?></p>
					</div>
					<button class="btn btn-lg btn-success mt2 launch-btn" data-step="launch" data-id="<?php echo $_SESSION['h_logueado'] ?>"><i class="fa fa-rocket"></i> <?php echo $OnboardingTemplateLang['Launch now!'] ?></button>
					<div class="row dblock">
						<a href="<?php echo $urlTree['hotel-home']?>" class="btn btn-lg mt2 btn-success btn-go-dashboard" title="Go back to your home page"><i class="fa fa-tachometer"></i> <?php echo $OnboardingTemplateLang['Go to your dashboard now! 2'] ?></a>	
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="ob-steps center-block mt2">
			<p class="blanco"><?php echo $OnboardingTemplateLang['Your onboarding progress:'] ?></p>
			<div class="progress">
				<div class="progress-bar <?php echo($element == 'launch' ? 'progress-bar-success' : '') ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 10%;">
					10%
				</div>
			</div>
		</div>
	</div>
	<?php if($element == 'first_video') {?>
	<div class="first-video-video">
		<button class="close-first-video"><i class="fa fa-times"></i></button>
		<iframe width="853" height="480" src="//www.youtube.com/embed/a8D3dMAKkJU" frameborder="0" allowfullscreen></iframe>	
	</div>
	<?php } ?>
	<?php if($element == 'second_video') {?>
	<div class="second-video-video">
		<button class="close-second-video"><i class="fa fa-times"></i></button>
		<iframe width="853" height="480" src="//www.youtube.com/embed/nzjQ8zJyyxA" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php } ?>
	<?php if($element == 'invite_send') {?>
	<div class="third-video-video">
		<button class="close-third-video"><i class="fa fa-times"></i></button>
		<iframe width="853" height="480" src="//www.youtube.com/embed/LNCMEb7IiQc" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php } ?>
	<?php if($element == 'hotel_profile') {?>
	<div class="hotel-profile-video">
		<button class="close-hotel-profile-video"><i class="fa fa-times"></i></button>
		<iframe width="853" height="480" src="//www.youtube.com/embed/Ur3m6QSO_PI" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php } ?>
	<?php if($element == 'booking_info') {?>
	<div class="hotel-booking-video">
		<button class="close-hotel-booking-video"><i class="fa fa-times"></i></button>
		<iframe width="853" height="480" src="//www.youtube.com/embed/0_RlZhBzz3w" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php } ?>
	<?php if($element == 'landing_page') {?>
	<div class="hotel-landing-video">
		<button class="close-hotel-landing-video"><i class="fa fa-times"></i></button>
		<iframe width="853" height="480" src="//www.youtube.com/embed/z_2aHATNqTI" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php } ?>
	<?php if($element == 'oferta_1') {?>
	<div class="basic-info-video">
		<button class="close-basic-info-video"><i class="fa fa-times"></i></button>
		<iframe width="853" height="480" src="//www.youtube.com/embed/OIc93EK305Y" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php } ?>
</section>