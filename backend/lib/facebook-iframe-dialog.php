<script>
	//spinner
	$(function(){$('#spinner').hide(); var opts = {lines: 13,length: 28,width: 14,radius: 42,scale: 0.25,corners: 1,color: '#FFF',opacity: 0.25,rotate: 0,direction: 1,speed: 0.5,trail: 15,fps: 20,zIndex: 2e9,className: 'spinner',top: '50%',left: '50%',shadow: false,hwaccel: false,position: 'absolute'}; var target = document.getElementById('spinner'); var spinner = new Spinner(opts).spin(target); })
	function createCookie(name,value,days) {if (days) {var date = new Date(); date.setTime(date.getTime()+(days*24*60*60*1000)); var expires = "; expires="+date.toGMTString(); } else var expires = ""; document.cookie = name+"="+value+expires+"; path=/";};
	function readCookie(name) {var nameEQ = name + "="; var ca = document.cookie.split(';'); for(var i=0;i < ca.length;i++) {var c = ca[i]; while (c.charAt(0)==' ') c = c.substring(1,c.length); if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length); } return null;};
	function eraseCookie(name) {createCookie(name,"",-1); };
	function thanks(){var content = $('.bookingShare'); content.empty(); <?php if($shareData['shareType'] === '2') {?> var thanksMsg = '<?php echo $hotelBookingShareLang["We have emailed you your voucher"] ?>'; <?php }else if($shareData['shareType'] === '3') {?> var thanksMsg = '<?php echo $hotelBookingShareLang["stay: We have emailed you your voucher"] ?>'; <?php }else if($shareData['shareType'] === '4') {?> var thanksMsg = '<?php echo $hotelBookingShareLang["We have emailed you your voucher"] ?>'; <?php } ?> content.append('<div class="section text-center iframe-thanks-text"><i class="fa fa-check-circle-o verde fa-6x"></i><br><h1><?php echo $hotelBookingShareLang["Thanks for sharing"] ?></h1><p>'+thanksMsg+'</p></div>');};
	function isInArray(value, array) {return array.indexOf(value) > -1; };
	function sendDeclined (permissions, hid){$.ajax({url: "/lib/webservices/<?php echo $shareData['webservice'] ?>", data: 'event=declined_permissions&hId='+hid+'&perm='+permissions+'&shareData=<?php echo $shareData['shareType'] ?>', type: 'POST', success : function (){createCookie('hlfDecPerm', true, 1); } }); };
	function fbReintent (){if(!readCookie('hlhr')){$.ajax({url: "/lib/webservices/<?php echo $shareData['webservice'] ?>", data: 'event=hitReintent&hId=<?php echo $hotel ?>&shareData=<?php echo $shareData['shareType'] ?>', type: 'POST', success : function (){createCookie('hlhr', true, 1); } }); } fb_login(); };

	//Guardar datos de FB
	function guardarDatosFacebook(response, fbAccessToken){
		if (typeof response.friends === "undefined") {
			var friends = 0;
		}else{
			var friends = response.friends.summary.total_count;
		}
		if (typeof response.location === "undefined") {
			var locationId = null;
			var locationName = null;
		}else{
			var locationId = response.location.id;
			var locationName = response.location.name;
		}

		<?php empty($shareType)? $shareType='pre': $shareType=$shareType; ?>

		$.ajax({
			url: "/lib/webservices/sendAdditionalShareEmail.php",
			data: 'fbid='+response.id+'&fbname='+response.name+'&fbemail='+response.email+'&fbfriends='+friends+'&gender='+response.gender+'&locale='+response.locale+'&ageMin='+response.age_range.min+'&ageMax='+response.age_range.max+'&locationId='+locationId+'&locationName='+locationName+'&hotelEmail=<?php echo $datosHotel["email_hotel"] ?>&hotelName=<?php echo $datosHotel['hotelName'] ?>&logo=<?php echo $datosHotel['logo'] ?>&fotoBg=<?php echo $datosHotel['fotoBg'] ?>&hotelUrl=<?php echo $datosHotel['hotelUrl'] ?>&websiteReserva=<?php echo $datosHotel['websiteReserva'] ?>&lang=<?php echo $_SESSION["userNavLang"] ?>&hlid=<?php echo $hotel ?>&fbt='+fbAccessToken+'&shTy=<?php echo $shareType ?>&shSm=FB',
			type: 'POST',
			async: false,
			success: function(output) {
				//console.log(output);
				response = $.parseJSON(output);
				if(response['error']=='200'){
					localStorage.setItem('urlShare', response.urlShare.url);
					var urlShare = localStorage.getItem('urlShare');
					fb_share();
				}else{
					$.ajax({
						url: "/lib/webservices/msgFeedback.php",
						data: "nError=4062&lang=<?php echo $_SESSION['userLang'] ?>",
						type: 'POST',
						success: function(output) {
							data = $.parseJSON(output);
							showError(data);
						}
					});
				}
			}
		});
	}

//Login
function fb_login(){
	$('#spinner, .overlayer').fadeIn();
	FB.login(function(response) {
		if (response.authResponse) {
			var fbAccessToken = response.authResponse.accessToken;
			checkPermissions(response, fbAccessToken);
		} else {
			$('#spinner, .overlayer').fadeOut();
			var content = $('.bookingShare');
			content.empty().fadeIn();
			<?php if($shareData['shareType'] === '2') {?>
				var loginMsg = '<?php echo $hotelBookingShareLang["Parece que no nos has dado permisos"].$ofertaShare['nombre_oferta'].$hotelBookingShareLang["Parece que no nos has dado permisos2"] ?>';
			<?php }else if($shareData['shareType'] === '3') {?>
				var loginMsg = '<?php echo $hotelBookingShareLang["stay: Parece que no nos has dado permisos"] ?>';
			<?php }else if($shareData['shareType'] === '4') {?>
				var loginMsg = '<?php echo $hotelBookingShareLang["Parece que no nos has dado permisos"].$ofertaShare['nombre_oferta'].$hotelBookingShareLang["Parece que no nos has dado permisos2"] ?>';
			<?php } ?>
			content.append('<div class="section text-center iframe-thanks-text"><i class="fa fa-exclamation-triangle fa-6x naranja"></i><br><h1><?php echo $hotelBookingShareLang["Upsss..."] ?></h1><p>'+loginMsg+'</p><button class="btn btn-facebook" onCLick="fbReintent()"><i class="fa fa-facebook-official pr"></i> <?php echo $hotelBookingShareLang["Reintenta el login"] ?></button></div>');
			if(!readCookie('hlc')){
				$.ajax({
					url: "/lib/webservices/<?php echo $shareData['webservice'] ?>",
					data: 'event=canceled&hId=<?php echo $hotel ?>&shareData=<?php echo $shareData['shareType'] ?>',
					type: 'POST',
					success : function (){
						createCookie('hlc', true, 1);
					}
				});
			}
		}
	}, {
		scope: 'public_profile,email,user_friends,publish_actions'
	})
}

//Check permissions
function checkPermissions(loginResponse, fbAccessToken){
	FB.api('/me/permissions', function(response) {
		var declined = [];
		for (i = 0; i < response.data.length; i++) {
			if (response.data[i].status == 'declined') {
				declined.push(response.data[i].permission)
			}
		}
		if(declined.length > 0){
			$('#spinner, .overlayer').fadeOut();
			var content = $('.bookingShare');
			content.empty().fadeIn();
			<?php if($shareData['shareType'] === '2') {?>
				var permissionsMsg = '<?php echo $hotelBookingShareLang["Parece que nos has revocado información"].$ofertaShare['nombre_oferta'].$hotelBookingShareLang["Parece que nos has revocado información2"] ?>';
			<?php }else if($shareData['shareType'] === '3') {?>
				var permissionsMsg = '<?php echo $hotelBookingShareLang["stay: Parece que nos has revocado información"] ?>';
			<?php }else if($shareData['shareType'] === '4') {?>
				var permissionsMsg = '<?php echo $hotelBookingShareLang["Parece que nos has revocado información"].$ofertaShare['nombre_oferta'].$hotelBookingShareLang["Parece que nos has revocado información2"] ?>';
			<?php } ?>
			content.append('<div class="section text-center iframe-thanks-text"><i class="fa fa-exclamation-triangle fa-6x naranja"></i><br><h1><?php echo $hotelBookingShareLang["Upsss..."] ?></h1><p>'+permissionsMsg+'<strong>'+declined.toString()+'</strong></p><button class="btn btn-facebook" onClick="fb_login_re();"><i class="fa fa-facebook-official pr"></i> <?php echo $hotelBookingShareLang["Reintenta el login"] ?></button></div>');

				if(!readCookie('hlfDecPerm')){
					createCookie('hlfDecPerm',true,1);
					sendDeclined(declined.toString(), '<?php echo $hotel ?>');
				}
				return false;
			}else{
				//All permissions set then save user
				userFacebookData(loginResponse, fbAccessToken);
			}
		});
	}

	//Get FB user data
	function userFacebookData (loginResponse, fbAccessToken){
		FB.api('/me?fields=id,name,email,friends,locale,age_range,gender', function(response) {
			localStorage.setItem('fbimage', 'https://graph.facebook.com/'+response.id+'/picture?width=300');
			localStorage.setItem('fbname',response.name);
			localStorage.setItem('fbid',response.id);
			guardarDatosFacebook(response, fbAccessToken);
		});
	}

	//Re intent auth
	function fb_login_re(){
		FB.login(
			function(response) {
				var fbAccessToken = response.authResponse.accessToken;
				checkPermissions(response, fbAccessToken);
			},
			{
				scope: 'public_profile,email,user_friends,publish_actions',
				auth_type: 'rerequest'
			}
		);
	}

	//Share action
	function fb_share(){
		var socialMediaShareText = '<?php echo $socialMediaShareText; ?>';
		if (socialMediaShareText == ''){
			// Default text if no hotel custom text for each share type
			<?php if($shareData['shareType'] === '2'){ //Pre stay share?>
				socialMediaShareText = "<?php echo $hotelBookingShareLang['You can book too, i can give to you a'] ?> <?php echo $ofertaReferralHotel['nombre'] ?> <?php echo $hotelBookingShareLang['at booking by clicking on the image above'] ?>";
			<?php }else if($shareData['shareType'] === '3'){ //Stay share?>
				socialMediaShareText = "<?php echo $hotelBookingShareLang['stay: you can boook too'] ?> <?php echo $ofertaReferralHotel['nombre'] ?> <?php echo $hotelBookingShareLang['stay: at booking by clicking on the image above'] ?>";
			<?php } else if($shareData['shareType'] === '4'){ //Post stay share?>
				socialMediaShareText = "<?php echo $referralShareLang['i´ve stayed and totally recommend it! get a'] ?> <?php echo $ofertaReferralHotel['nombre'] ?> <?php echo $referralShareLang['thanks to me, just by clicking on the image above!'] ?>";
			<?php } ?>
		}
		$('#spinner, .overlayer').fadeIn();
		FB.api(
			'me/<?php echo FACEBOOK_SHARE_TYPE ?>:share_experience_at?fb:explicitly_shared=true',
			'post',
			{'hotel': {
				'og:url': ""+localStorage.getItem('urlShare')+"",
				"og:original_url" : "<?php echo $datosHotel['hotelUrl'] ?>",
				<?php if($shareData['shareType'] === '2'){ //Pre stay share?>
				'og:title': "<?php echo $hotelBookingShareLang['I just booked at'] ?> <?php echo $datosHotel['hotelName'] ?>",
				<?php }else if($shareData['shareType'] === '3'){ //Stay share?>
				'og:title': "<?php echo $hotelBookingShareLang['stay: Im at'] ?><?php echo $datosHotel['place_name'] ?><?php echo $hotelBookingShareLang['stay: Im at 2'] ?> <?php echo $datosHotel['hotelName'] ?>",
				<?php } else if($shareData['shareType'] === '4'){ //Post stay share?>
				'og:title': "<?php echo $hotelBookingShareLang['I just booked at'] ?> <?php echo $datosHotel['hotelName'] ?>",
				<?php } ?>
				'og:description': ""+socialMediaShareText+"",
				'og:type': '<?php echo FACEBOOK_SHARE_TYPE ?>:hotel',
				'og:image': "<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL?><?php echo $hotel ?>/fotoBg/<?php echo $datosHotel['fotoBg'] ?>",
				'og:site_name' : "<?php echo $datosHotel['hotelUrl'] ?>",
				'fb:app_id': '<?php echo FACEBOOK_APP_ID ?>',
				'og:image:width' : '1600',
				'og:image:height' : '627'
			}},
			function(response) {
				if(response['error']){
					$('#spinner, .overlayer').fadeOut();
					var content = $('.bookingShare');
					content.empty().fadeIn();
					<?php if($shareData['shareType'] === '2') {?>
					var shareMsg = '<?php echo $hotelBookingShareLang["Parece que no has compartido en Facebook...."] ?>';
					<?php }else if($shareData['shareType'] === '3') {?>
					var shareMsg = '<?php echo $hotelBookingShareLang["stay: Parece que no has compartido en Facebook...."] ?>';
					<?php }else if($shareData['shareType'] === '4') {?>
					var shareMsg = '<?php echo $hotelBookingShareLang["Parece que no has compartido en Facebook...."] ?>';
					<?php } ?>
					content.append('<div class="section text-center iframe-thanks-text"><i class="fa fa-exclamation-triangle fa-6x naranja"></i><br><h1><?php echo $hotelBookingShareLang["Upsss..."] ?></h1><p>'+shareMsg+'</p><button class="btn btn-facebook" onClick="fb_share();"><i class="fa fa-facebook-official pr"></i> <?php echo $hotelBookingShareLang["Reintenta el share"] ?></button></div>');
				}else{
					$('#facebookInstructions').modal('hide');
					var id_share_fb = response['id']
					var fbid = localStorage.getItem('fbid');
					createCookie('shared',true,1);
					$('#spinner, .overlayer').fadeOut();
					thanks();
					$.ajax({
						url: "/lib/webservices/referral-share-actions-ws.php",
						data: 'sm=fb&smUId='+fbid+'&hId=<?php echo $hotel ?>&shId='+id_share_fb+'&idTSh=<?php echo $shareData['shareType'] ?>&transaction=<?php echo $transaction ?>',
						type: 'POST',
						success : function (response){
							<?php if($shareData['shareType'] === '3'){ //Stay share?>
							var data = JSON.parse(response);
							var url = data.rsG.url.form_url;
							if(url==''){
								//No url wifi
								$.ajax({
									url: "/lib/webservices/msgFeedback.php",
									data: "nError=4058&lang=<?php echo $_SESSION['userLang'] ?>",
									type: 'POST',
									success: function(output) {
										data = $.parseJSON(output);
										showError(data);
									}
								});
							}else{
								var form = $('<form action="' + url + '" method="post">' +
									'<input type="hidden" name="username" value="' + data.rsG.url.username + '" />' +
									'<input type="hidden" name="password" value="' + data.rsG.url.password + '" />' +
									'</form>');
								$('body').append(form);
								form.submit();
							}
							<?php } ?>
						}
					});
					$.ajax({
						url: "/lib/webservices/<?php echo $shareData['webservice'] ?>",
						data: 'event=share&hId=<?php echo $hotel ?>&shareData=<?php echo $shareData['shareType'] ?>',
						type: 'POST'
					});
				}
			}
		);
	}

	//init
	(function() {
		var e = document.createElement('script');
		e.src = document.location.protocol + '//connect.facebook.net/EU/sdk.js';
		e.async = true;
		window.fbAsyncInit = function() {
			FB.init(
				{appId : '<?php echo FACEBOOK_APP_ID ?>', oauth : true, status : false, cookie : true, xfbml : false, version : 'v2.10'}
			);
		};
		document.getElementById('fb-root').appendChild(e);
		//check if shared
		var shared = readCookie('shared');
		//if not shared init FB
		if(shared){
			$('#spinner, .overlayer').fadeOut();
			thanks();
		}
	})();
</script>
