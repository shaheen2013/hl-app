<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php
//recogemos el idioma del navegador
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
include LANG . 'landing-' . $lang . '.php';
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
	<title><?php echo $Tutitle;?></title>
	<meta charset="UTF-8">
	<meta name="description" content="<?php echo $Tudescription;?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo DIR_CSS; ?>thanks.css">
</head>
<body>
	<img class="comingSoon" src="<?php echo DIR_IMG ?>landings/coming-soon.png" alt="coming soon img" width="200" height="200">
	<header>
	<div class="wrapper">
		<a class="logo" href="/" title="hotelinking logo"><img src="<?php echo DIR_IMG; ?>landings/logo.png" alt=""></a>
	</div>
	</header>
	<section class="wrapper">
		<h1><?php echo $Tu1; ?></h1>
		<h2 class="mb20"><?php echo $Tu2; ?></h2>
		<div class="whats-next">
			<h3><?php echo $Tu3; ?></h3>
			<p><?php echo $Tup; ?></p>
			<h3><?php echo $Tu32; ?></h3>
			       <div class="tweet-btn">
			  		<a class="btn-lg btn" href="https://twitter.com/intent/tweet?url=<?php echo $url_twitter;?>&text=<?php echo $texto;?>&via=<?php echo $via;?>"  target="_blank" height="200", width="400" ><?php echo $Tutweet; ?></a>
			       </div>
			<p><?php echo $Tup2; ?></p>
			<img src="<?php echo DIR_IMG; ?>landings/rubies-1.jpg" width="143" height="141" alt="rubies">
		</div>
	</section>
	<footer>
		<div class="wrapper afix bottom">
			<p class="fleft"><?php echo $rights; ?></p>
			<nav class="fright footer-nav">
				<ul>
					<li><a href="mailto:helpdesk@hotelinking.com" title="contact"><?php echo $contactUs; ?></a></li>
					<li><a href="privacy" title="privacy"><?php echo $privacidad; ?></a></li>
				</ul>
			</nav>
		</div>
	</footer>
	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
</body>
</html>