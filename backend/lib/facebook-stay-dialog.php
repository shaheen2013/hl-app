<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>
    var permissions;

    function isInArray(value, array) {
        return array.indexOf(value) > -1;
    }
    
    function showLikeButton() {
        $('.ripple, .connection-text').removeClass('bounceIn').addClass('bounceOut');
        $('.likeButton-content').removeClass('fadeOutDown').addClass('dblock animated fadeInUp');
    }

    //Guardar datos de FB
    function guardarDatosFacebook(response, fbAccessToken) {
        if (typeof response.friends === "undefined") {
            var friends = 0;
        } else {
            var friends = response.friends.summary.total_count;
        }
        if (typeof response.location === "undefined") {
            var locationId = null;
            var locationName = null;
        } else {
            var locationId = response.location.id;
            var locationName = response.location.name;
        }

        <?php empty($shareType) ? $shareType = 'pre' : $shareType = $shareType; ?>

        $.ajax({
            url: "/lib/webservices/sendAdditionalShareEmail.php",
            data: 'fbid=' + response.id + '&fbname=' + response.name + '&fbemail=' + response.email + '&fbfriends=' + friends + '&gender=' + response.gender + '&locale=' + response.locale + '&ageMin=' + response.age_range.min + '&ageMax=' + response.age_range.max + '&locationId=' + locationId + '&locationName=' + locationName + '&hotelEmail=<?php echo $datosHotel["email_hotel"] ?>&hotelName=<?php echo $datosHotel['hotelName'] ?>&logo=<?php echo $datosHotel['logo'] ?>&fotoBg=<?php echo $datosHotel['fotoBg'] ?>&hotelUrl=<?php echo $datosHotel['hotelUrl'] ?>&lang=<?php echo $_SESSION["userNavLang"] ?>&hlid=<?php echo $hotel ?>&fbt=' + fbAccessToken + '&shTy=<?php echo $shareType ?>&shSm=FB',
            type: 'POST',
            async: false,
            success: function (output) {
                //console.log(output);
                response = $.parseJSON(output);
                if (response['error'] == '200') {
                    localStorage.setItem('urlShare', response.urlShare.url);
                    var urlShare = localStorage.getItem('urlShare');
                    fb_share();
                } else {
                    $.ajax({
                        url: "/lib/webservices/msgFeedback.php",
                        data: "nError=4062&lang=<?php echo $_SESSION['userLang'] ?>",
                        type: 'POST',
                        success: function (output) {
                            data = $.parseJSON(output);
                            showError(data);
                        }
                    });
                }
            }
        });
    }

    //Login
    function fb_login() {
        FB.login(function (response) {
            if (response.authResponse) {
                var fbAccessToken = response.authResponse.accessToken;
                checkPermissions(response, fbAccessToken);
            } else {
                $('.ripple, .connection-text').removeClass('bounceIn').addClass('bounceOut');
                $('.login-error').removeClass('fadeOutDown').addClass('dblock animated fadeInUp');
            }
        }, {
            scope: 'public_profile,email,user_friends,publish_actions'
        });
    }

    //Check permissions
    function checkPermissions(loginResponse, fbAccessToken) {
        var declined = [];
        $('.ripple, .connection-text').removeClass('bounceOut').addClass('bounceIn');
        $('.permissions-error').removeClass('fadeInUp').addClass('fadeOutDown');
        FB.api('/me/permissions', function (response) {
            for (i = 0; i < response.data.length; i++) {
                if (response.data[i].status == 'declined') {
                    declined.push(response.data[i].permission)
                }
            }
            if (declined.length > 0) {
                permissions = declined.toString();
                $('.declinedPermissions').empty().text(permissions);
                $('.ripple, .connection-text').removeClass('bounceIn').addClass('bounceOut');
                $('.permissions-error').removeClass('fadeOutDown').addClass('dblock animated fadeInUp');
                return;
            } else {
                userFacebookData(loginResponse, fbAccessToken);
            }
        });
    }

    //Get FB user data
    function userFacebookData(loginResponse, fbAccessToken) {
        $('.ripple, .connection-text').removeClass('bounceOut').addClass('bounceIn');
        $('.permissions-error').removeClass('fadeInUp').addClass('fadeOutDown');
        FB.api('/me?fields=id,name,email,friends,locale,age_range,gender', function (response) {
            localStorage.setItem('fbimage', 'https://graph.facebook.com/' + response.id + '/picture?width=300');
            localStorage.setItem('fbname', response.name);
            localStorage.setItem('fbid', response.id);
            guardarDatosFacebook(response, fbAccessToken);
        });
    }

    //Re intent auth
    function fb_login_re() {
        $('.ripple, .connection-text').removeClass('bounceOut').addClass('bounceIn');
        $('.permissions-error').removeClass('fadeInUp').addClass('fadeOutDown');
        FB.login(
            function (response) {
                if (response) {
                    var fbAccessToken = response.authResponse.accessToken;
                    checkPermissions(response, fbAccessToken);
                } else {
                    $('.ripple, .connection-text').removeClass('bounceIn').addClass('bounceOut');
                    $('.login-error').removeClass('fadeOutDown').addClass('dblock animated fadeInUp');
                }
            },
            {
                scope: 'public_profile,email,user_friends,publish_actions',
                auth_type: 'rerequest'
            }
        );
    }

    //Share action
    function fb_share() {
        FB.api(
            'me/<?php echo FACEBOOK_SHARE_TYPE ?>:share_experience_at?fb:explicitly_shared=true',
            'post',
            {
                'hotel': {
                    'og:url': "" + localStorage.getItem('urlShare') + "",
                    "og:original_url": "<?php echo $datosHotel['hotelUrl'] ?>",
                    'og:title': "<?php echo $stayShareLang['stay: Im at'] ?><?php echo $datosHotel['place_name'] ?><?php echo $stayShareLang['stay: Im at 2'] ?> <?php echo $datosHotel['hotelName'] ?>",
                    'og:description': "<?php echo $stayShareLang['stay: you can book too'] ?> <?php echo $ofertaReferralHotel['nombre'] ?> <?php echo $stayShareLang['stay: at booking by clicking on the image above'] ?>",
                    'og:type': '<?php echo FACEBOOK_SHARE_TYPE ?>:hotel',
                    'og:image': "<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL?><?php echo $hotel ?>/fotoBg/<?php echo $datosHotel['fotoBg'] ?>",
                    'og:site_name': "<?php echo $datosHotel['hotelUrl'] ?>",
                    'fb:app_id': '<?php echo FACEBOOK_APP_ID ?>',
                    'og:image:width': '1600',
                    'og:image:height': '627'
                }
            },
            function (response) {
                if (response['error']) {

                    //error de share

                } else {

                    var id_share_fb = response['id']
                    var fbid = localStorage.getItem('fbid');


                    $.ajax({
                        url: "/lib/webservices/referral-share-actions-ws.php",
                        data: 'sm=fb&smUId=' + fbid + '&hId=<?php echo $hotel ?>&shId=' + id_share_fb + '&idTSh=<?php echo $shareData['shareType'] ?>&transaction=<?php echo $transaction ?>',
                        type: 'POST',
                        success: function (response) {
                            var data = JSON.parse(response);
                            //var url = data.rsG.url.form_url;
							if ( data.rsG.url==null || data.rsG.url.form_url=='' ) {
                                //No url wifi
                                $.ajax({
                                    url: "/lib/webservices/msgFeedback.php",
                                    data: "nError=4058&lang=<?php echo $_SESSION['userLang'] ?>",
                                    type: 'POST',
                                    success: function (output) {
                                        data = $.parseJSON(output);
                                        showError(data);
                                    }
                                });
                            } else {
                                showLikeButton();
                            }
                        }
                    });
                }
            }
        );
    }

    $(document).ready(function () {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/EU/sdk.js';
        e.async = true;
        window.fbAsyncInit = function () {
            FB.init(
                {
                    appId: '<?php echo FACEBOOK_APP_ID ?>',
                    oauth: true,
                    status: false,
                    cookie: true,
                    xfbml: false,
                    version: 'v2.10'
                }
            );
        };
        document.getElementById('fb-root').appendChild(e);
    });

</script>