<script>
	//spinner
	$(function(){$('#spinner').hide(); var opts = {lines: 13,length: 28,width: 14,radius: 42,scale: 0.25,corners: 1,color: '#FFF',opacity: 0.25,rotate: 0,direction: 1,speed: 0.5,trail: 15,fps: 20,zIndex: 2e9,className: 'spinner',top: '50%',left: '50%',shadow: false,hwaccel: false,position: 'absolute'}; var target = document.getElementById('spinner'); var spinner = new Spinner(opts).spin(target); })
	function createCookie(name,value,days) {if (days) {var date = new Date(); date.setTime(date.getTime()+(days*24*60*60*1000)); var expires = "; expires="+date.toGMTString(); } else var expires = ""; document.cookie = name+"="+value+expires+"; path=/";};
	function readCookie(name) {var nameEQ = name + "="; var ca = document.cookie.split(';'); for(var i=0;i < ca.length;i++) {var c = ca[i]; while (c.charAt(0)==' ') c = c.substring(1,c.length); if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length); } return null;};
	function eraseCookie(name) {createCookie(name,"",-1); };
	function isInArray(value, array) {return array.indexOf(value) > -1; };
	function sendDeclined (permissions, hid){$.ajax({url: "/lib/webservices/<?php echo $shareData['webservice'] ?>", data: 'event=declined_permissions&hId='+hid+'&perm='+permissions+'&shareData=<?php echo $shareData['shareType'] ?>', type: 'POST', success : function (){createCookie('hlfDecPerm_l', true, 1); } }); };
	function fbReintent (){if(!readCookie('hlhr_l')){$.ajax({url: "/lib/webservices/<?php echo $shareData['webservice'] ?>", data: 'event=hitReintent&hId=<?php echo $id_hotel ?>&shareData=<?php echo $shareData['shareType'] ?>', type: 'POST', success : function (){createCookie('hlhr_l', true, 1); } }); } fb_login_re(); };

	//Save User
	function saveFacebookUserData(data){
		//console.log(data);
		var id = data.id;
		var email = data.email;
		var name = data.name;

		if (typeof data.friends === "undefined") {
			var nFriends = 0;
		}else{
			var nFriends = data.friends.summary.total_count;
		}

		var FBImg = 'https://graph.facebook.com/'+data['id']+'/picture?width=300';

		$('#FBid').val(id);
		$('#userEmail').val(email);
		$('#userName').val(name);
		$('#FBNFriends').val(nFriends);
		$('#FBUserImg').val(FBImg);
		$('#FBData').val('1');

		if(!validateEmail(email)){
			$.ajax({
				url: "/lib/webservices/msgFeedback.php",
				data: "nError=4062&lang=<?php echo $_SESSION['userLang'] ?>",
				type: 'POST',
				success: function(output) {
					data = $.parseJSON(output);
					showError(data);
				}
			});
		}else{
			$('#newAccountForm').submit();
		}
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
				var loginMsg = '<?php echo $iframeLanding["Parece que no nos has dado permisos"] ?>';
				content.append('<div class="section text-center iframe-thanks-text"><i class="fa fa-exclamation-triangle fa-6x naranja"></i></i><br><h1><?php echo $iframeLanding["Upsss..."] ?></h1><p>'+loginMsg+'</p><button class="btn btn-facebook" onCLick="fbReintent()"><i class="fa fa-facebook-official pr"></i> <?php echo $iframeLanding["Reintenta el login"] ?></button></div>');
				if(!readCookie('hlc_l')){
					$.ajax({
						url: "/lib/webservices/<?php echo $shareData['webservice'] ?>",
						data: 'event=canceled&hId=<?php echo $id_hotel ?>&shareData=<?php echo $shareData['shareType'] ?>',
						type: 'POST',
						success : function (){
							createCookie('hlc_l', true, 1);
						}
					});
				}
			}
		}, {
            scope: "email, public_profile, user_friends, publish_actions, user_location, user_birthday"
		});
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
				content.fadeIn();
				var permissionsMsg = '<?php echo $iframeLanding["Parece que nos has revocado información"] ?>';
				content.append('<div class="section text-center iframe-thanks-text"><i class="fa fa-exclamation-triangle fa-6x naranja"></i></i><br><h1><?php echo $iframeLanding["Upsss..."] ?></h1><p>'+permissionsMsg+'<strong>'+declined.toString()+'</strong></p><button class="btn btn-facebook" onClick="fb_login_re();"><i class="fa fa-facebook-official pr"></i> <?php echo $iframeLanding["Reintenta el login"] ?></button></div>');

				if(!readCookie('hlfDecPerm_l')){
					createCookie('hlfDecPerm_l',true,1);
					sendDeclined(declined.toString(), '<?php echo $id_hotel ?>');
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
			saveFacebookUserData(response);
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
				scope: 'public_profile,email,user_friends',
				auth_type: 'rerequest'
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
	})();
</script>
