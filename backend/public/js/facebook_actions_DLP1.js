//login methods included for check permissions
var loginActions = {
    //User is logged but not authorized the app
    notAuthorized: function () {
        console.log('hl - not authorized actions');
    },
    //User is not logged at all even in Facebook
    notLogged: function () {
        console.log('hl - not logged actions');

        //put a cookie, not store same action twice
        if (!retrieve_cookie('hlc_l')) {
            //Store statistics and create cookie
            storeStatistics('canceled', 'hlc_l');
            //In case user cancel first login, store this variable for statistics
            permissionsActions.relogin = true;
        }

        //Remove login button, show, CTA to relogin
        $('.text-offer').addClass('animated fadeOutLeft');
        setTimeout(function () {
            $('.text-offer').addClass('hidden').removeClass('animated fadeOutLeft');
            $('.text-user-cancelled').removeClass('hidden').addClass('animated fadeInRight');
        }, 500);

    }
};

//permissions methods to make facebook login works.
var permissionsActions = {
    relogin: false,
    //actions to do when user is logged and accepted all permissions (all is OK)
    accepted: function () {
        console.log('hl - all is OK, next');

        //if relogin is true, store re login statistics
        if (this.relogin === true) {
            if (!retrieve_cookie('hlr_l')) {
                storeStatistics('reintents', 'hlr_l');
            }
        }

        if (!retrieve_cookie('hll_l')) {
            storeStatistics('login', 'hll_l');
        }

        //get and store
        getFbUserData(storeFbUserData);

    },
    //User has declined some permissions
    declined: function (declined) {
        console.log('hl - declined permissions actions');

        //put a cookie
        if (!retrieve_cookie('hldp_l')) {
            //Store statistics and create cookie
            storeStatistics('declined_permissions', 'hldp_l', declined.toString());
        }

        //remove logins and show permissions
        $('.text-offer').addClass('hidden').removeClass('animated fadeOutLeft');
        $('.text-user-cancelled').addClass('hidden').removeClass('animated fadeInRight');
        setTimeout(function () {
            $('.permissions-list').html('<strong>' + declined.toString() + '</strong>');
            $('.text-user-permissions').removeClass('hidden').addClass('animated fadeInRight');
        }, 500);
    }
};

//Email actions
function askForEmail() {
    //PSI1 actions
    //show thanks
    $('.text-offer').addClass('hidden').removeClass('animated fadeOutLeft');
    $('.text-user-cancelled, .text-user-permissions,.text-user-share,.text-user-shared').addClass('hidden').removeClass('animated fadeInRight');
    setTimeout(function () {
        $('.ask-for-email').removeClass('hidden').addClass('animated fadeInRight');
    }, 500);
}

function storeStatistics(event, cookieName, permissions) {

    console.log('hl - store statistics ' + event);

    var permissions = permissions || undefined;

    $.ajax({
        url: pageInfo.statisticsWebservice,
        data: 'event=' + event + '&hId=' + pageInfo.hotelId + '&perm=' + permissions + '&shareData=' + pageInfo.page,
        type: 'POST',
        success: function () {
            create_cookie(cookieName, true, 1);
        }
    });
};

//PSI1 actions
//remove logins and permissions, show share
function actionAfterStore(){
    var params = {
            guid : pageInfo.guid,
            promoCode : pageInfo.promo
        },
        query = EncodeQueryData(params);
    window.location.replace('/digital-loyalty-program-thanks/?' + query);
}

//Errors after store
function errorAfterStore(error){
    //Check cual es el resultado
    switch (error) {
        case '4011':
        case '4012':
        case '4014':
        case '4019':
            console.log('hl - error - ' + error);
            $('.no-error-text').addClass('hidden').removeClass('animated fadeOutLeft fadeInRight');
            setTimeout(function () {
                $('.error-4011').removeClass('hidden').addClass('animated fadeInRight');
            }, 500);

            //put a cookie
            if (!retrieve_cookie('hlerr_l')) {
                //Store statistics and create cookie
                storeStatistics('error', 'hlerr_l');
            }
            break;

        case '4018':
            console.log('hl - error - ' + error);
            $('.no-error-text, .error-fatal, .ask-for-email').addClass('hidden').removeClass('animated fadeOutLeft fadeInRight');
            setTimeout(function () {
                $('.error-4018').removeClass('hidden').addClass('animated fadeInRight');
            }, 500);
            break;

        case '4013':
            console.log('hl - error - ' + error);
            $('.no-error-text, .error-fatal, .ask-for-email').addClass('hidden').removeClass('animated fadeOutLeft fadeInRight');
            setTimeout(function () {
                $('.error-4013').removeClass('hidden').addClass('animated fadeInRight');
            }, 500);
            break;
    };
}