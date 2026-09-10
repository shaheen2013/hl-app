<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
	echo 'No direct access allowed.';
	exit;
} ?>

<?php include_once LANG . $_SESSION['userLang'] . '/referral-share.php'; ?>
<div class="referral-share-2">
	<!-- <img src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel ?>/fotoBg/<?php echo $datosHotel['fotoBg'] ?>" alt="Background image of hotel" class="rs-bg"> -->
	<img src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $datosHotel['fotoBg'] ?>" alt="Background image of hotel" class="rs-bg">
	<div class="dlpOverlayer"></div>
	<img src="<?php echo $datosHotel['logo'] ?>" alt="Hotel logo" class="img-thumbnail img-circle rs-logo" width="100" height="100">

	<div class="rs-text">
		<h2 class="mt0"><?php echo $datosHotel['hotelName'] ?></h2>
		<?php if (!empty($ofertaPoststayHotel['nombre_oferta'])) { ?>
			<h1><?php echo $referralShareLang["share your experience"] ?></h1>
			<h3><?php echo $ofertaPoststayHotel['nombre_oferta'] ?></h3>
		<?php } else { ?>
			<h1><?php echo $referralShareLang["share your experience sin oferta"] ?></h1>
		<?php } ?>
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<?php if (!empty($ofertaReferralHotel['nombre'])) { ?>
					<p><?php echo $referralShareLang["share"] ?><br><strong><?php echo $ofertaReferralHotel['nombre'] ?></strong> <?php echo $referralShareLang['with booking'] ?></p>
				<?php } ?>
				<button class="btn btn-facebook btn-icon mt" onclick="fb_login();"><i class="fa fa-facebook pr"></i> <?php echo $referralShareLang['share facebook'] ?></button>
				<button class="btn btn-twitter btn-icon mt btn-twitterForm hide"><i class="fa fa-twitter pr"></i> <?php echo $referralShareLang['share twitter'] ?></button>
				<div class="row mt2">
					<div class="col-lg-12">
						<small><?php echo $referralShareLang['Check out wich rewards you can win by sharing your experiences at'] ?> <?php echo $datosHotel['hotelName'] ?><?php echo $referralShareLang['Check out the rewards you can win'] ?> </small><a href="#" data-toggle="modal" data-target="#rewardsListModal"> <?php echo $referralShareLang['click here'] ?>.</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="fb-root"></div>

<form method="POST" name="twitterForm" class="twitterForm">
	<input type="hidden" name="twitterForm" value="true">
</form>

<?php include(TEMPLATES . 'share-rewards-list.php'); ?>
<?php include(TEMPLATES . 'share-twitter-modal.php'); ?>
<?php include(TEMPLATES . 'share-facebook-modal.php'); ?>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>
	//Share
	$('.btn-twitterForm').click(function() {
		//Twitter login form
		$('.twitterForm').submit();
	});

	//Character counter for twitter text area
	setInterval(function() {
		var max = 120;
		var len = $('#twitterShareField').val().length;
		if (len >= max) {
			$('#charNum').text(' you have reached the limit');
		} else {
			var char = max - len;
			$('#charNum').text(char + ' characters left');
		}
	}, 1000);

	<?php if (!empty($_SESSION['tw-msg']) && empty($_POST['refShareStep2twText'])) { ?>

		//Open twitter modal if twitter login has performed
		$(function() {
			setTimeout(function() {
				<?php if (isset($_SESSION['twd-eml']) && !empty($_SESSION['twd-eml'])) { ?>
					guardarDatosTwitter();
					$('#twitterShareModal').modal('show');
				<?php } else if (isset($_SESSION['twd-eml'])) { ?>
					//No email
					$.ajax({
						url: "/lib/webservices/msgFeedback.php",
						data: "nError=4057&lang=<?php echo $_SESSION['userLang'] ?>",
						type: 'POST',
						success: function(output) {
							data = $.parseJSON(output);
							showError(data);
						}
					});
				<?php } ?>
			}, 100);

			function guardarDatosTwitter() {
				$.ajax({
					url: "/lib/webservices/sendAdditionalShareEmail.php",
					data: "twdata=1&hotelEmail=<?php echo $datosHotel['email'] ?>&hotelName=<?php echo $datosHotel['hotelName'] ?>&logo=<?php echo $datosHotel['logo'] ?>&fotoBg=<?php echo $datosHotel['fotoBg'] ?>&hotelUrl=<?php echo $datosHotel['hotelUrl'] ?>&lang=<?php echo $_SESSION["userNavLang"] ?>&hlid=<?php echo $hotel ?>&shTy=post",
					type: 'POST',
					async: false,
					success: function(output) {
						if (output != '') {
							data = $.parseJSON(output);
							//Guardamos urlShare en localStorage
							localStorage.setItem('urlShare', data.urlShare.url);
						}
						//Añadimos urlShare de localStorage al texto de twitter (Solo se pone en localStorage 1 vez)
						var urlShare = localStorage.getItem('urlShare');
						var box = $("#twitterShareField");
						<?php !empty($datosHotel['twitterAccount']) ? $viaTw = '@' . $datosHotel['twitterAccount'] : $viaTw = '' ?>
						box.val(box.val() + ' ' + urlShare + ' <?php echo $viaTw ?>');
					}
				});
			}

			$('#twitterShareModal').on('hide.bs.modal', function() {
				<?php unset($_SESSION['tw-msg']); ?>
			});

		});

	<?php } ?>

	window.fbAsyncInit = function() {
		FB.init({
			appId: '<?php echo FACEBOOK_APP_ID ?>',
			oauth: true,
			status: false, // check login status
			cookie: true, // enable cookies to allow the server to access the session
			xfbml: false, // parse XFBML
			version: 'v6.0'
		});

	};

	(function() {
		var e = document.createElement('script');
		e.src = document.location.protocol + '//connect.facebook.net/en_US/sdk.js';
		e.async = true;
		document.getElementById('fb-root').appendChild(e);
	}());

	function guardarDatosFacebook(response, fbAccessToken) {
		if (typeof response.friends === "undefined") {
			var friends = 0;
		} else {
			var friends = response.friends.summary.total_count;
		}
		$.ajax({
			url: "/lib/webservices/sendAdditionalShareEmail.php",
			data: 'fbid=' + response.id + '&fbname=' + response.name + '&fbemail=' + response.email + '&fbfriends=' + friends + '&hotelEmail=<?php echo $datosHotel['email'] ?>&hotelName=<?php echo $datosHotel['hotelName'] ?>&logo=<?php echo $datosHotel['logo'] ?>&fotoBg=<?php echo $datosHotel['fotoBg'] ?>&hotelUrl=<?php echo $datosHotel['hotelUrl'] ?>&lang=<?php echo $_SESSION["userNavLang"] ?>&hlid=<?php echo $hotel ?>&fbt=' + fbAccessToken + '&shTy=post',
			type: 'POST',
			async: false,
			success: function(output) {
				if (output != '') {
					response = $.parseJSON(output);
					//Guardamos urlShare en localStorage
					localStorage.setItem('urlShare', response.urlShare.url);
				}
				//Añadimos urlShare de localStorage al texto de twitter (Solo se pone en localStorage 1 vez)
				var urlShare = localStorage.getItem('urlShare');
			}
		});
	}

	function fb_login() {
		FB.login(function(response) {
			if (response.authResponse) {
				var fbAccessToken = response.authResponse.accessToken;
				FB.api('/me?fields=name,email,friends', function(response) {
					localStorage.setItem('fbimage', 'https://graph.facebook.com/' + response.id + '/picture?width=200');
					//console.log(response);

					localStorage.setItem('fbname', response.name);
					localStorage.setItem('fbid', response.id);

					if (typeof response.email === "undefined") {
						//No email
						$.ajax({
							url: "/lib/webservices/msgFeedback.php",
							data: "nError=4054&lang=<?php echo $_SESSION['userLang'] ?>",
							type: 'POST',
							success: function(output) {
								data = $.parseJSON(output);
								showError(data);
							}
						});
					} else {
						guardarDatosFacebook(response, fbAccessToken);
						//Monstrar modal share facebook
						$('#facebookShareModal').modal('show');
					}
				});
			} else {
				//user hit cancel button
				console.log('User cancelled login or did not fully authorize.');
			}
		}, {
			scope: "email, public_profile, user_friends, publish_actions, user_location, user_birthday"
		});
	}

	function fb_share() {
		var socialMediaShareText = '<?php echo $socialMediaShareText; ?>';
		if (socialMediaShareText == '') {
			// Default text if no hotel custom text
			var socialMediaShareText = "<?php echo $referralShareLang['i´ve stayed and totally recommend it! get a'] ?> <?php echo $ofertaReferralHotel['nombre'] ?> <?php echo $referralShareLang['thanks to me, just by clicking on the image above!'] ?>";
		}
		FB.api(
			'me/<?php echo FACEBOOK_SHARE_TYPE ?>:share_experience_at?fb:explicitly_shared=true',
			'post', {
				'hotel': {
					'og:url': "" + localStorage.getItem('urlShare') + "",
					"og:original_url": "<?php echo $datosHotel['hotelUrl'] ?>",
					'og:title': "<?php echo $referralShareLang['great Experience at'] ?> <?php echo $datosHotel['hotelName'] ?>",
					'og:type': '<?php echo FACEBOOK_SHARE_TYPE ?>:hotel',
					'og:image': "<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel ?>/fotoBg/<?php echo $datosHotel['fotoBg'] ?>",
					'og:description': "" + socialMediaShareText + "",
					'og:site_name': "<?php echo $datosHotel['hotelUrl'] ?>",
					'fb:app_id': '<?php echo FACEBOOK_APP_ID ?>'
				}
			},
			function(response) {
				if (response['error']) {
					//No share
				} else {
					console.log(response);
					//Share OK
					var id_share_fb = response['id']
					var fbid = localStorage.getItem('fbid');
					$.ajax({
						url: "/lib/webservices/referral-share-actions-ws.php",
						data: 'sm=fb&smUId=' + fbid + '&hId=<?php echo $hotel ?>&shId=' + id_share_fb + '&idTSh=4',
						type: 'POST',
						success: function(output) {
							data = $.parseJSON(output);
							if (data.rsG.code == "401") {
								//Usuario tiene una oferta stay sin canjear, no puede conseguir una nueva
								showError(data.rsG.msg);
							} else if (data.rsG.code == "200" || data.rsG.code == "404") {
								showError(data.rsG.msg);
							}
						}
					});
				}
			}
		);
	}
</script>