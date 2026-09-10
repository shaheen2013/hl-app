<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<?php include LANG . $_SESSION['userLang'].'/digital-loyalty-program-thanks.php' ?>

	<div class="section text-center iframe-thanks-text">
		<i class="fa fa-envelope-o fa-6x"></i>
		<h1><?php echo $DigitalLoyaltyProgramLang['We sent you an email'] ?></h1>
		<?php if($isFacebook != 'Facebook'){ ?>
			<p><?php echo $DigitalLoyaltyProgramLang['msg if is not facebook'] ?></p>
		<?php }else{ ?>
			<p><?php echo $DigitalLoyaltyProgramLang['msg if it is facebook'] ?></p>
		<?php } ?>
		<div class="text-center">
			<a href="<?php echo $urlTree['redeem-offer'].'/?hlhid='.$_GET['guid'].'&hlpc='.$_GET['promoCode'] ?>" class="btn btn-large btn-success mt20 btn-redirect" target="_blank"><?php echo $DigitalLoyaltyProgramLang['Or book now with your applied promocode!'] ?></a>
		</div>
	</div>
<?php if($isFacebook != 'Facebook'){ ?>
	<script>
		$('.btn-redirect').click(function(){

			setTimeout(function(){
				parent.window.close();
			},200);
		})
	</script>
<?php } ?>