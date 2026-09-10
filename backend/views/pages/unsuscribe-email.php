<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/landing-invite-thanks.php' ?>
<?php include TEMPLATES . 'landing-header.php' ?>
<div class="clearfix"></div>
<div class="user-utility-bar">
	<h1 class="pull-left">
		<i class="fa fa-bell-slash"></i> <?php echo $LandingInviteThanksLang["Remove landing notifications"]; ?>
	</h1>
</div>
<div class="clearfix"></div>
<div class="container">
	<div class="row">
		<div class="col-lg-12 text-center">
		<?php if($error == false && $step == 0){ ?>
			<i class="fa fa-bell-slash fa-5x"></i>
			<h4>Are you sure you want to unsuscribe from landing notifications?</h4>
			<a href="<?php echo $urlTree['unsuscribe-email'] .'/?confirm=' . $_GET['email'] ?>" class="btn btn-lg btn-primary mt2 unsub-btn" title="Unsuscribe from landing notifications">Yes, unsuscribe me</a>
			<p class="mt2">We will send you a mail that you will need to confirm for security reasons</p>
		<?php }else if ($error == false && $step == 1){ ?>
			<i class="fa fa-bell-slash fa-5x"></i>
			<h4>Mail sent</h4>
			<p class="mt2">Please check your email, we sent a confirmation email to you.</p>
		<?php }else if ($error == false && $step == 2){ ?>
			<i class="fa fa-bell-slash fa-5x"></i>
			<h4>Unsuscribe confirmed</h4>
			<p class="mt2">You will not receive more mails from our landing</p>
		<?php }else{ ?>
			<i class="fa fa-bell-slash fa-5x"></i>
			<h4>Ups... this email don´t exists in our records</h4>
			<p class="mt2">Check your email and try again.</p>
		<?php } ?>
		
	</div>
</div>