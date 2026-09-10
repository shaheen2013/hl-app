<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" lang="en"><![endif]-->
<!--[if IE 7 ]>    <html class="ie7" lang="en"><![endif]-->
<!--[if IE 8 ]>    <html class="ie8" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
	<base href="<?php echo BASE_PATH ?>">
	<meta charset="UTF-8">
	<meta name="description" content="<?php echo $landingNew['Description'] ?>">
	<meta name="keywords" content="<?php echo $landingNew['keywords'] ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $landingNew['title'] ?></title>
	<link rel="stylesheet" href="<?php echo DIR_CSS ?>landing.css">
	<?php if(!$detect->isMobile()){ ?>
	<link rel="stylesheet" href="<?php echo DIR_CSS ?>lightbox.css">
	<?php } ?>
</head>
<body>
	<div class="top-section"> <!-- top section -->
		<div class="container">
			<div class="row">
				<header class="col-lg-12 top-bar">
					<?php if(!$detect->isMobile() ){ ?>
					<div class="btn-fixed-holder text-center btn-fixed-holder-hidden">
						<a href="#" class="btn btn-blue" title="Request yor invite">Request your invite</a>
					</div>
					<?php } ?>
					<a href="<?php echo BASE_PATH ?>landing" title="Hotelinking home" class="pull-left logo"><img srcset="<?php echo DIR_IMG . 'landing/' ?>logo.png 1x, <?php echo DIR_IMG . 'landing/' ?>logo@2x.png 2x" src="<?php echo DIR_IMG . 'landing/' ?>logo@2x.png" alt="hotelinking logo" width="159" height="32"/></a>
					<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
					<a href="#" title="main menu" class="pull-right mobile-menu"><i class="sprite hamburguer-icon"></i></a>
					<a href="#" title="close main menu" class="close-mobile-menu"><i class="sprite close-icon"></i></a>
					<ul class="mobile-submenu">
						<li><a class="features-btn" href="#" title="<?php echo $landingNew['menu 1 tooltip'] ?>"><?php echo $landingNew['menu 1'] ?></a></li>
						<li><a class="benefits-btn" href="#" title="<?php echo $landingNew['menu 2 tooltip'] ?>"><?php echo $landingNew['menu 2'] ?></a></li>
						<li><a href="http://blog.hotelinking.com" title="blog">Blog</a></li>
						<li><a href="<?php echo $urlTree['landing-faq'] ?>" title="<?php echo $landingNew['menu 3 tooltip'] ?>"><?php echo $landingNew['menu 3'] ?></a></li>
						<li><a href="https://www.facebook.com/hotelinking" title="hotelinking facebook">Facebook</a></li>
						<li><a href="https://twitter.com/hotelinking" title="hotelinking twitter">Twitter</a></li>
						<li><a href="<?php echo BASE_PATH ?>hotel-login" title="access to login">login</a></li>
					</ul>
					<?php } else { ?>
					<nav class="pull-left main-menu">
						<ul>
							<li><a class="features-btn" href="#" title="<?php echo $landingNew['menu 1 tooltip'] ?>"><?php echo $landingNew['menu 1'] ?></a></li>
							<li><a class="benefits-btn" href="#" title="<?php echo $landingNew['menu 2 tooltip'] ?>"><?php echo $landingNew['menu 2'] ?></a></li>
							<li><a href="http://blog.hotelinking.com" title="blog">Blog</a></li>
							<li><a href="<?php echo $urlTree['landing-faq'] ?>" title="<?php echo $landingNew['menu 3 tooltip'] ?>"><?php echo $landingNew['menu 3'] ?></a></li>
						</ul>
					</nav>
					<ul class="pull-right secondary-menu">
						<li class="social-media facebook-li"><a href="https://www.facebook.com/hotelinking" title="<?php echo $landingNew['menu 4 tooltip'] ?>"><i class="sprite facebook-icon"></i></a></li>
						<li class="social-media"><a href="https://twitter.com/hotelinking" title="<?php echo $landingNew['menu 5 tooltip'] ?>"><i class="sprite twitter-icon"></i></a></li>
						<li class="login-btn"><a class="btn-hollow login-btn" href="<?php echo BASE_PATH ?>hotel-login" title="<?php echo $landingNew['menu 6 tooltip'] ?>"><?php echo $landingNew['menu 6'] ?></a></li>
					</ul>
					<?php } ?>
				</header>
			</div>
			<div class="row text-center headings">
				<h1><?php echo $landingNew['Acquire more Loyal Guests'] ?></h1>
				<h2><?php echo $landingNew['The first digital loyalty for hotels and chains'] ?></h2>
			</div>
			<?php if($landingMailError){ ?>
			<p class="alert text-center"><?php echo $landingNew['Invite already message'] ?></p>
			<?php } ?>
			<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
			<form action="<?php echo $urlTree['landing-invite-hotel'] ?>" class="row text-center mail-form" method="POST">
				<div class="col-lg-6 col-lg-offset-3">
					<input type="email" class="first-input-mail input-lg mail-input <?php echo($landingMailError ? 'input-error' : '') ?>" name="email" placeholder="Your email here...">
					<input type="submit" class="btn btn-mobile-form" value="<?php echo $landingNew['Get early access'] ?>">
					<small class="blanco mt"><?php echo $landingNew['No credit card required'] ?></small>
				</div>
			</form>
			<?php } else { ?>
			<form action="<?php echo $urlTree['landing-invite-hotel'] ?>" class="row text-center mail-form" method="POST">
				<div class="col-lg-6 col-lg-offset-3">
					<input type="email" class="first-input-mail input-lg mail-input <?php echo($landingMailError ? 'input-error' : '') ?>" name="email" placeholder="<?php echo $landingNew['your email here..'] ?>">
					<input type="submit" class="btn btn-form" value="<?php echo $landingNew['Get early access'] ?>">
					<small class="blanco mt"><?php echo $landingNew['No credit card required'] ?></small>
				</div>
			</form>
			<?php } ?>
		</div>
		<div class="col-lg-12">
			<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>

			<?php } else { ?>
			<div class="row text-center computer-screens">
				<div class="col-lg-12">
					<img data-srcset="<?php echo DIR_IMG . 'landing/' ?>big-screens-img.png 1x, <?php echo DIR_IMG . 'landing/' ?>big-screens-img@2x.png 2x" width="1280" height="365" data-src="<?php echo DIR_IMG . 'landing/' ?>big-screens-img.png" alt="hotelinking platform views" src='<?php echo DIR_IMG . 'landing/' ?>big-screens-img.png'>
				</div>
			</div>
			<?php } ?>
			<button class="btn btn-down btn-down-absolute"><i class="sprite blue-arrow-down"></i></button>
		</div>
		<div class="clearfix"></div>
		<section class="loyalty-section main-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<header class="text-center">
							<h1><?php echo $landingNew['All tools for one hotel loyalty solution'] ?></h1>
							<h2><?php echo $landingNew['Loyalty is the next big thing for the hospitality industry'] ?></h2>
							<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
							<?php } else { ?>
							<ul class="text-center tools-list">
								<li>
									<a href="#" data-section="loyal-section" title="loyalty tool"><i class="mobile-sprite mobile-diamond-icon"></i></a>
									<strong>Loyalty</strong>
								</li>
								<li>
									<a href="#" data-section="marketing-section" title="marketing tool"><i class="mobile-sprite mobile-database-icon"></i></a>
									<strong><?php echo $landingNew['Tool 2'] ?></strong>
								</li>
								<li>
									<a href="#"  data-section="social-media-section" title="Guest engagement tool"><i class="mobile-sprite mobile-heart-icon"></i></a>
									<strong><?php echo $landingNew['Tool 3'] ?></strong>
								</li>
								<li>
									<a href="#" data-section="reputation-section" title="Satisfaction tool"><i class="mobile-sprite mobile-reputation-icon"></i></a>
									<strong><?php echo $landingNew['Tool 4'] ?></strong>
								</li>
								<li>
									<a href="#" data-section="acquisition-section" title="Acquisition tool"><i class="mobile-sprite mobile-cart-icon"></i></a>
									<strong><?php echo $landingNew['Tool 5'] ?></strong>
								</li>
								<li>
									<a href="#" data-section="big-data-section" title="Big data tool"><i class="mobile-sprite mobile-kpi-icon"></i></a>
									<strong><?php echo $landingNew['Tool 6'] ?></strong>
								</li>
								<li>
									<a href="#" data-section="api-section" title="Api integration"><i class="mobile-sprite mobile-api-icon"></i></a>
									<strong><?php echo $landingNew['Tool 7'] ?></strong>
								</li>
							</ul>
							<?php } ?>
						</header>
					</div>
				</div>
			</div>
		</section>
		<?php if($detect->isMobile()){ ?>
		<?php } else { ?>
		<div class="network">
			<canvas id="background-canvas" width="1920" height="400"></canvas>
			<canvas id="line-canvas" width="1920" height="400"></canvas>
			<!-- left side -->
			<span class="white-circle wc-1"></span>
			<img class="portrait portrait-1" id="portrait_1" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-1.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-1@2x.png 2x" width="161" height="161" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-1.png" alt="Twitter user" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="n-icon n-twitter-icon" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw==' data-src="<?php echo DIR_IMG . 'landing/' ?>twitter-icon@2x.png" alt="twitter icon" width="30" height="30">
			<span class="rating-text left-rating-text">8.9</span>
			<span class="comment-text text-1"><?php echo $landingNew['Araña 1'] ?></span>
			<span class="white-circle wc-2"></span>
			<img class="portrait portrait-2" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-2.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-2@2x.png 2x" width="95" height="98" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-2.png" alt="Twitter user 2" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="n-icon n-twitter-icon-2" data-src="<?php echo DIR_IMG . 'landing/' ?>twitter-icon@2x.png" alt="twitter icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="comment-text text-2"><?php echo $landingNew['Araña 2'] ?></span>
			<img class="portrait portrait-3" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-3.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-3@2x.png 2x" width="62" height="63" src="<?php echo DIR_IMG . 'landing/' ?>portrait-3.png" alt="Twitter user 3" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="n-icon n-facebook-icon" data-src="<?php echo DIR_IMG . 'landing/' ?>facebook-icon@2x.png" alt="facebook icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="comment-text text-3"><?php echo $landingNew['Araña 3'] ?></span>
			<img class="n-icon n-facebook-icon-2" data-src="<?php echo DIR_IMG . 'landing/' ?>facebook-icon@2x.png" alt="facebook icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="portrait portrait-4" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-4.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-4@2x.png 2x" width="103" height="105" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-4.png" alt="Twitter user 4" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="white-circle wc-3"></span>
			<img class="n-icon n-instagram-icon" data-src="<?php echo DIR_IMG . 'landing/' ?>instagram-icon@2x.png" alt="instagram icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="portrait portrait-5" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-5.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-5@2x.png 2x" width="44" height="46" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-5.png" alt="Twitter user 5" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="n-icon n-instagram-icon" data-src="<?php echo DIR_IMG . 'landing/' ?>instagram-icon@2x.png" alt="instagram icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="n-icon n-twitter-icon-3" data-src="<?php echo DIR_IMG . 'landing/' ?>twitter-icon@2x.png" alt="twitter icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="comment-text text-4"><?php echo $landingNew['Araña 4'] ?></span>
			<img class="n-icon n-instagram-icon-2" data-src="<?php echo DIR_IMG . 'landing/' ?>instagram-icon@2x.png" alt="instagram icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<!-- Right side -->
			<img class="n-icon n-twitter-icon-4" data-src="<?php echo DIR_IMG . 'landing/' ?>twitter-icon@2x.png" alt="twitter icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="rating-text right-rating-text">9.2</span>
			<span class="comment-text text-8"><?php echo $landingNew['Araña 5'] ?></span>
			<img class="portrait portrait-10" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-10.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-10@2x.png 2x" width="149" height="150" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-10.png" alt="Twitter user 10" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="n-icon n-facebook-icon-5" data-src="<?php echo DIR_IMG . 'landing/' ?>facebook-icon@2x.png" alt="facebook icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="portrait portrait-9" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-9.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-9@2x.png 2x" width="99" height="101" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-9.png" alt="Twitter user 9" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="white-circle wc-7"></span>
			<img class="n-icon n-facebook-icon-4" data-src="<?php echo DIR_IMG . 'landing/' ?>facebook-icon@2x.png" alt="facebook icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="comment-text text-7"><?php echo $landingNew['Araña 6'] ?></span>
			<img class="portrait portrait-8" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-8.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-8@2x.png 2x" width="115" height="117" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-8.png" alt="Twitter user 8" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<img class="n-icon n-linkedin-icon" data-src="<?php echo DIR_IMG . 'landing/' ?>linkedin-icon@2x.png" alt="linkedin icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="comment-text text-6"><?php echo $landingNew['Araña 7'] ?></span>
			<img class="portrait portrait-7" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-7.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-7@2x.png 2x" width="63" height="64" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-7.png" alt="Twitter user 7" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="white-circle wc-8"></span>
			<img class="n-icon n-facebook-icon-3" data-src="<?php echo DIR_IMG . 'landing/' ?>facebook-icon@2x.png" alt="facebook icon" width="30" height="30" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<span class="comment-text text-5"><?php echo $landingNew['Araña 8'] ?></span>
			<img class="portrait portrait-6" data-srcset="<?php echo DIR_IMG . 'landing/' ?>portrait-6.png 1x, <?php echo DIR_IMG . 'landing/' ?>portrait-6@2x.png 2x" width="42" height="44" data-src="<?php echo DIR_IMG . 'landing/' ?>portrait-6.png" alt="Twitter user 6" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<!-- Center -->
			<span class="white-circle wc-4"></span>
			<span class="white-circle wc-5"></span>
			<span class="white-circle wc-6"></span>
			<img class="portrait hotelinking-circle" data-srcset="<?php echo DIR_IMG . 'landing/' ?>hotelinking-circle.png 1x, <?php echo DIR_IMG . 'landing/' ?>hotelinking-circle@2x.png 2x" width="90" height="90" data-src="<?php echo DIR_IMG . 'landing/' ?>hotelinking-circle.png" alt="hotelinking-circle" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
			<i class="sprite white-arrow"></i>
			<span class="comment-text text-in-the-middle"><?php echo $landingNew['Araña 9'] ?></strong></span>
		</div>
		<?php } ?>
	</div> <!-- top section -->
	<div class="container">
		<div class="row loyal-section">
			<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
			<i class="mobile-sprite mobile-diamond-icon"></i>
			<?php } ?>
			<div class="col-lg-12">
				<header class="text-center">
					<h2><?php echo $landingNew['Loyalty title'] ?></h2>
				</header>
				<p><?php echo $landingNew['Loyalty text'] ?></p>
				<?php if($detect->isMobile()){ ?>
				<?php } else { ?>
				<div class="text-center loyalty-image">
					<img width="600" height="305" data-src="<?php echo DIR_IMG . 'landing/' ?>loyalty-img.jpg" alt="loyalty" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="go-to-top">
		<button class="btn btn-up btn-down-absolute"><i class="sprite blue-arrow-up"></i></button>
	</div>
	<div class="container"> <!-- marketing section -->
		<div class="row marketing-section">
			<section>
				<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
				<i class="mobile-sprite mobile-database-icon"></i>
				<?php } else { ?>
				<p class="click-here-arrow hidden-sm">
					<span>Click to enlarge</span>
					<i class="sprite black-arrow"></i>
				</p>
				<?php } ?>
				<article>
					<div class="col-lg-6 col-sm-6">
						<header>
							<h2 class="margin-0-tagline"><?php echo $landingNew['Marketing tagline'] ?></h2>
						</header>
						<p><?php echo $landingNew['Marketing text'] ?></p>
					</div>
					<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
					<?php } else { ?>
					<div class="col-lg-6 col-sm-6 mac-air-img">
						<a href="<?php echo DIR_IMG . 'landing/' ?>campaings-screenshot.jpg" data-lightbox="<?php echo DIR_IMG . 'landing/' ?>campaings-screenshot.jpg"><img data-lightbox="image-1"data-srcset="<?php echo DIR_IMG . 'landing/' ?>mac-air-1.jpg 1x, <?php echo DIR_IMG . 'landing/' ?>mac-air-1@2x.jpg 2x" width="559" height="497" data-src="<?php echo DIR_IMG . 'landing/' ?>mac-air-1.jpg" alt="marketing screen preview" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='></a>
					</div>
					<?php } ?>
				</article>
			</section>
		</div>
	</div> <!-- marketing section -->
	<div class="go-to-top">
		<button class="btn btn-up btn-down-absolute"><i class="sprite blue-arrow-up"></i></button>
	</div>
	<div class="container"> <!-- social media section -->
		<div class="row social-media-section">
			<section>
				<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
				<i class="mobile-sprite mobile-heart-icon"></i>
				<?php } ?>
				<article>
					<div class="col-lg-12">
						<article>
							<header class="text-center">
								<h2><?php echo $landingNew['Referral tagline'] ?></h2>
							</header>
							<p><?php echo $landingNew['Referral text 1'] ?></p>
							<h3 class="text-center"><?php echo $landingNew['Referral text 2'] ?></h3>
						</article>
					</div>
				</article>
				<div class="infografia">
					<i class="sprite rubi-left hidden-sm"></i>
					<div class="col-lg-3 text-center col-sm-3">
						<i class="sprite info-icon-1"></i>
						<p><?php echo $landingNew['Referral graph 1'] ?></p>
					</div>
					<i class="sprite next-arrow-down arrow-down-1 hidden-sm"></i>
					<div class="col-lg-3 text-center col-sm-3">
						<i class="sprite info-icon-2"></i>
						<p><?php echo $landingNew['Referral graph 2'] ?></p>
					</div>
					<i class="sprite next-arrow-up arrow-down-2 hidden-sm"></i>
					<div class="col-lg-3 text-center col-sm-3">
						<i class="sprite info-icon-3"></i>
						<p><?php echo $landingNew['Referral graph 3'] ?></p>
					</div>
					<i class="sprite next-arrow-down arrow-down-3 hidden-sm"></i>
					<div class="col-lg-3 text-center col-sm-3">
						<i class="sprite info-icon-4"></i>
						<p><?php echo $landingNew['Referral graph 4'] ?></p>
					</div>
					<i class="sprite rubi-right hidden-sm"></i>
				</div>
				<div class="clearfix"></div>
				<div class="col-lg-12 text-center">
					<ul class="logo-list">
						<li><i class="sprite pinterest-logo"></i></li>
						<li><i class="sprite facebook-logo"></i></li>
						<li><i class="sprite twitter-logo"></i></li>
						<li><i class="sprite linkedin-logo"></i></li>
						<li><i class="sprite instagram-logo"></i></li>
					</ul>
				</div>
			</section>
		</div>
	</div> <!-- social media section -->
	<div class="go-to-top">
		<button class="btn btn-up btn-down-absolute"><i class="sprite blue-arrow-up"></i></button>
	</div>
	<div class="container"> <!-- reputation section -->
		<div class="row reputation-section">
			<section>
				<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
				<i class="mobile-sprite mobile-reputation-icon"></i>
				<?php } else { ?>
				<p class="click-here-arrow hidden-sm">
					<span>Click to enlarge</span>
					<i class="sprite black-arrow"></i>
				</p>
				<?php } ?>
				<article>
					<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
					<?php } else { ?>
					<div class="col-lg-6 col-sm-6 mac-air-img">
						<a href="<?php echo DIR_IMG . 'landing/' ?>survey-screenshot.jpg" data-lightbox="<?php echo DIR_IMG . 'landing/' ?>survey-screenshot.jpg"><img data-srcset="<?php echo DIR_IMG . 'landing/' ?>mac-air-2.jpg 1x, <?php echo DIR_IMG . 'landing/' ?>mac-air-2@2x.jpg 2x" width="446" height="468" data-src="<?php echo DIR_IMG . 'landing/' ?>mac-air-2.jpg" alt="marketing screen preview" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='></a>
					</div>
					<?php } ?>
					<div class="col-lg-6 col-sm-6">
						<header>
							<h2 class="margin-0-tagline"><?php echo $landingNew['Reputation tagline'] ?></h2>
						</header>
						<p><?php echo $landingNew['Reputation text'] ?></p>
					</div>
				</article>
			</section>
		</div>
	</div> <!-- reputation section -->
	<div class="go-to-top">
		<button class="btn btn-up btn-down-absolute"><i class="sprite blue-arrow-up"></i></button>
	</div>
	<div class="quotes">
		<div class="container">
			<div class="col-lg-8 col-lg-offset-2 text-center">
				<h3><span>"</span><?php echo $landingNew['Quote'] ?> <span>"</span></h3>
			</div>
		</div>
	</div>
	<div class="container"> <!-- Acquisition section -->
		<div class="row acquisition-section">
			<section>
				<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
				<i class="mobile-sprite mobile-cart-icon"></i>
				<?php } else { ?>

				<?php } ?>
				<article>
					<div class="col-lg-12">
						<header class="text-center">
							<h2><?php echo $landingNew['Acquisition tagline'] ?></h2>
						</header>
						<p><?php echo $landingNew['Acquisition text'] ?></p>
					</div>
					<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
					<?php } else { ?>
					<div class="clearfix"></div>
					<div class="text-center shop-img">
						<img data-srcset="<?php echo DIR_IMG . 'landing/' ?>shop-devices.jpg 1x, <?php echo DIR_IMG . 'landing/' ?>shop-devices@2x.jpg 2x" width="644" height="388" data-src="<?php echo DIR_IMG . 'landing/' ?>shop-devices.jpg" alt="shop screen preview" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
					</div>
					<?php } ?>
				</article>
			</section>
		</div>
	</div> <!-- Acquisition section -->
	<div class="go-to-top">
		<button class="btn btn-up btn-down-absolute"><i class="sprite blue-arrow-up"></i></button>
	</div>
	<div class="container"><!-- Big data section -->
		<div class="row big-data-section">
			<section>
				<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
				<i class="mobile-sprite mobile-kpi-icon"></i>
				<?php } else { ?>
				<?php } ?>
				<article>
					<div class="col-lg-12">
						<header class="text-center">
							<h2><?php echo $landingNew['Big data tagline'] ?></h2>
						</header>
						<p><?php echo $landingNew['Big Data Text'] ?></p>
					</div>

				</article>
			</section>
		</div>
	</div><!-- Big data section -->
	<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
	<?php } else { ?>
	<div class="big-data-img">
		<img width="1920" height="1032" data-src="<?php echo DIR_IMG . 'landing/' ?>kpi.jpg" alt="kpi" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
	</div>
	<?php } ?>
	<div class="go-to-top">
		<button class="btn btn-up btn-down-absolute"><i class="sprite blue-arrow-up"></i></button>
	</div>
	<div class="container"> <!-- api section -->
		<div class="row api-section">
			<section>
				<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
				<i class="mobile-sprite mobile-api-icon"></i>
				<?php } else { ?>

				<?php } ?>
				<article>
					<div class="col-lg-6">
						<header>
							<h2><?php echo $landingNew['API integrations tagline'] ?></h2>
						</header>
						<p><?php echo $landingNew['API text'] ?></p>
					</div>
					<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
					<?php } else { ?>
					<div class="col-lg-6 text-center">
						<img data-srcset="<?php echo DIR_IMG . 'landing/' ?>api.gif 1x, <?php echo DIR_IMG . 'landing/' ?>api.gif 2x" width="228" height="288" data-src="<?php echo DIR_IMG . 'landing/' ?>api.gif" alt="shop screen preview"src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
					</div>
					<?php } ?>
				</article>
				<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
				<form action="/" class="row text-center mail-form" method="POST">
					<div class="col-lg-6 col-lg-offset-3">
						<input type="email" class="input-lg mail-input" name="email" placeholder="<?php echo $landingNew['Your email here...'] ?>">
						<input type="submit" class="btn btn-mobile-form" value="<?php echo $landingNew['Start free now!'] ?>">
						<small class="mt"><?php echo $landingNew['No credit card required'] ?></small>
					</div>
				</form>
				<?php } else { ?>
				<form action="/" class="row text-center mail-form" method="POST">
					<div class="col-lg-6 col-lg-offset-3">
						<input type="email" class="input-lg mail-input" name="email" placeholder="<?php echo $landingNew['Your email here...'] ?>">
						<input type="submit" class="btn btn-form" value="<?php echo $landingNew['Start free now!'] ?>">
						<small class="mt"><?php echo $landingNew['No credit card required'] ?></small>
					</div>
				</form>
				<?php } ?>
			</section>
		</div>
	</div> <!-- api section -->
	<div class="go-to-top">
		<button class="btn btn-up btn-down-absolute"><i class="sprite blue-arrow-up"></i></button>
	</div>
	<div class="blue-section">
		<header>
			<div class="col-lg-12 text-center headings">
				<h1><?php echo $landingNew['Hotels Benefits title'] ?></h1>
				<h2><?php echo $landingNew['Hotels Benefits title 2'] ?></h2>
			</div>
		</header>
		<section class="benefits">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite shoping-cart-icon blue-icon"></i>
						<h2><?php echo $landingNew['More direct bookings'] ?></h2>
						<p><?php echo $landingNew['More direct bookings text'] ?></p>
					</div>
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite kpi-icon blue-icon"></i>
						<h2><?php echo $landingNew['Understand your guests'] ?></h2>
						<p><?php echo $landingNew['Understand your guests text'] ?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite heart-icon blue-icon"></i>
						<h2><?php echo $landingNew['Increase guest feedback'] ?></h2>
						<p><?php echo $landingNew['Increase guest feedback text'] ?></p>
					</div>
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite diana-icon blue-icon"></i>
						<h2><?php echo $landingNew['Create customized offering'] ?></h2>
						<p><?php echo $landingNew['Create customized offering text'] ?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite virus-icon blue-icon"></i>
						<h2><?php echo $landingNew['Enhaced viral marketing'] ?></h2>
						<p><?php echo $landingNew['Enhaced viral marketing text'] ?></p>
					</div>
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite computer-icon blue-icon"></i>
						<h2><?php echo $landingNew['Automated marketing solutions'] ?></h2>
						<p><?php echo $landingNew['Automated marketing solutions text'] ?></p>
					</div>
				</div>
			</div>
		</section>
		<header>
			<div class="col-lg-12 text-center headings">
				<h1><?php echo $landingNew['Guest Benefits title'] ?></h1>
				<h2><?php echo $landingNew['Guest Benefits title 2'] ?></h2>
			</div>
		</header>
		<section class="benefits">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite mobile-icon blue-icon"></i>
						<h2><?php echo $landingNew['Customized reward offers'] ?></h2>
						<p><?php echo $landingNew['Customized reward offers text'] ?></p>
					</div>
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite card-icon blue-icon"></i>
						<h2><?php echo $landingNew['Digital loyalty card'] ?></h2>
						<p><?php echo $landingNew['Digital loyalty card text'] ?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 text-center benefit">
						<i class="sprite people-icon blue-icon"></i>
						<h2><?php echo $landingNew['Faster ways to earn rewards'] ?></h2>
						<p><?php echo $landingNew['Faster ways to earn rewards text'] ?></p>
					</div>
				</div>
			</div>
		</section>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
					<form action="<?php echo $urlTree['landing-invite-hotel'] ?>" class="row text-center mail-form" method="POST">
						<div class="col-lg-6 col-lg-offset-3">
							<input type="email" class="input-lg mail-input" name="email" placeholder="<?php echo $landingNew['Your email here...'] ?>">
							<input type="submit" class="btn btn-mobile-form" value="<?php echo $landingNew['Start free now!'] ?>">
							<small class="blanco mt"><?php echo $landingNew['No credit card required'] ?></small>
						</div>
					</form>
					<?php } else { ?>
					<form action="<?php echo $urlTree['landing-invite-hotel'] ?>" class="row text-center mail-form" method="POST">
						<div class="col-lg-6 col-lg-offset-3">
							<input type="email" class="input-lg mail-input" name="email" placeholder="<?php echo $landingNew['Your email here...'] ?>">
							<input type="submit" class="btn btn-form" value="<?php echo $landingNew['Start free now!'] ?>">
							<small class="blanco mt"><?php echo $landingNew['No credit card required'] ?></small>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="col-lg-12">
						<a href="<?php echo BASE_PATH ?>landing" title="Hotelinking home" class="pull-left logo"><img srcset="<?php echo DIR_IMG . 'landing/' ?>logo.png 1x, <?php echo DIR_IMG . 'landing/' ?>logo@2x.png 2x" src="<?php echo DIR_IMG . 'landing/' ?>logo@2x.png" alt="hotelinking logo" width="159" height="32"/></a>
						<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
						<a href="#" title="main menu" class="pull-right mobile-menu"><i class="sprite hamburguer-icon"></i></a>
						<?php } else { ?>
						<nav class="pull-left main-menu">
							<ul>
								<li><a class="features-btn" href="#" title="<?php echo $landingNew['menu 1 tooltip'] ?>"><?php echo $landingNew['menu 1'] ?></a></li>
								<li><a class="benefits-btn" href="#" title="<?php echo $landingNew['menu 2 tooltip'] ?>"><?php echo $landingNew['menu 2'] ?></a></li>
								<li><a href="landing-faq" title="<?php echo $landingNew['menu 3 tooltip'] ?>"><?php echo $landingNew['menu 3'] ?></a></li>
							</ul>
						</nav>
						<ul class="pull-right secondary-menu">
							<li class="social-media facebook-li"><a href="https://www.facebook.com/hotelinking" title="<?php echo $landingNew['menu 4 tooltip'] ?>"><i class="sprite facebook-icon"></i></a></li>
							<li class="social-media"><a href="https://twitter.com/hotelinking" title="<?php echo $landingNew['menu 5 tooltip'] ?>"><i class="sprite twitter-icon"></i></a></li>
							<li class="login-btn"><a class="btn-hollow login-btn" href="<?php echo BASE_PATH ?>hotel-login" title="<?php echo $landingNew['menu 6 tooltip'] ?>"><?php echo $landingNew['menu 6'] ?></a></li>
						</ul>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<?php if($detect->isMobile() && !$detect->isTablet() ){ ?>
	<?php } else { ?>
	<div class="footer-img">
		<img data-srcset="<?php echo DIR_IMG . 'landing/' ?>iphone-footer.jpg 1x, <?php echo DIR_IMG . 'landing/' ?>iphone-footer@2x.jpg 2x" width="898" height="879" data-src="<?php echo DIR_IMG . 'landing/' ?>iphone-footer@2x.jpg" alt="shop screen preview" src='data:image/gif;base64,R0lGODlhAQABAIAAAP//////zCH5BAEHAAAALAAAAAABAAEAAAICRAEAOw=='>
	</div>
	<?php } ?>
</div>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<?php if($detect->isMobile()){ ?>
<script src="<?php echo DIR_JS . 'landing-mobile.min.js' ?>"></script>
<?php } else { ?>
<script src="<?php echo DIR_JS . 'landing.min.js' ?>"></script>
<script src="<?php echo DIR_JS . 'lightbox.min.js' ?>"></script>
<?php } ?>
<script src="<?php echo DIR_JS . 'lazyload.min.js' ?>"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-51442675-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>