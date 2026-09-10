<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/landing-invite-hotel.php' ?>
<?php include TEMPLATES . 'landing-header.php' ?>
<?php if($stepOne){ ?>
<div class="clearfix"></div>
<div class="user-utility-bar">
	<h1 class="pull-left"><i class="fa fa-sign-in"></i> <?php echo $LandingInviteHotelLang['Request your invite'] ?></h1>
</div>
<div class="clearfix"></div>
<div class="container">
	<div class="row">
		<div class="text-center">
			<h2><?php echo $LandingInviteHotelLang['You are a step away from starting your loyalty program'] ?> <strong class="verde"><?php echo $LandingInviteHotelLang['for free'] ?></strong></h2>
			<h4><?php echo $LandingInviteHotelLang['In order to evaluate your registration process, we need additional info'] ?></h4>
			<div class="col-lg-8 col-lg-offset-2 col-xs-12">
				<form action="<?php echo $urlTree['landing-invite-thanks'] ?>" method="POST" class="text-left mt2">
					<fieldset>
						<div class="form-group mt2">
							<label for="hotelWebSite"><?php echo $LandingInviteHotelLang['Your hotel website'] ?></label>
							<input type="text" class="form-control input-lg" name="hotelWebSite" placeholder="<?php echo $LandingInviteHotelLang['hotel website...'] ?>" required <?php echo (!empty($_GET['website']) ? 'value="'.$_GET['website'].'"' : '') ?>>
						</div>
						<div class="form-group mt2">
							<label for="hotelPhone"><?php echo $LandingInviteHotelLang['Your telephone number'] ?></label>
							<input type="phone" class="form-control input-lg" name="hotelPhone" placeholder="<?php echo $LandingInviteHotelLang['hotel telephone number...'] ?>" required <?php echo (!empty($_GET['phone']) ? 'value="'.$_GET['phone'].'"' : '') ?>>
						</div>
						<div class="form-group mt2 mb4">
							<label for="sliderStars"><?php echo $LandingInviteHotelLang['Your hotel rating (stars)'] ?></label>
							<input class="noBorder mb col-xs" type="text" value="0" id="sliderStars" disabled name="hotelCategory" required>
							<input type="hidden" id="estrellasHotel" name="categoriaHotel" value="0">
							<div class="slider sliderStars"></div>
							<?php if (!empty($fillRating)) {?>
								<p class="alert alert-danger text-center mt2" role="alert"><?php echo $LandingInviteHotelLang['Category cant be cero'] ?> <strong>0</strong></p>
							<?php } ?>
						</div>
						<?php if(!empty($idInvite)) {?>
							<input type="hidden" name="id" value="<?php echo $idInvite ?>">
						<?php } ?>
						<button type="submit" class="btn btn-primary input-lg"><?php echo $LandingInviteHotelLang['I want to start my loyalty program'] ?></button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(function(){
		$('.sliderStars').slider({
			range: 'min',
			min: 0,
			max:7,
			value: 0,
			slide: function (event, ui) {
				$("#sliderStars").val(ui.value);
				$('#estrellasHotel').val(ui.value);
			}
		})
	})
</script>
<?php }else{ ?>
<div class="clearfix"></div>
<div class="user-utility-bar">
	<h1 class="pull-left"><i class="fa fa-ban"></i> <?php echo $LandingInviteHotelLang['Ups!...'] ?></h1>
</div>
<div class="clearfix"></div>
<div class="container">
	<div class="row">
		<div class="col-lg-12 text-center">
			<h2 class="naranja"><?php echo $LandingInviteHotelLang['You didn´t provided a valid mail in the previous step'] ?></h2>
			<h4><?php echo $LandingInviteHotelLang['Please'] ?> <a href="<?php echo $urlTree['landing']?>"><?php echo $LandingInviteHotelLang['come back'] ?></a> <?php echo $LandingInviteHotelLang['and fill the email field'] ?></h4>
		</div>
	</div>
</div>
<?php } ?>