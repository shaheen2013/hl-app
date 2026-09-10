//login methods included for check permissions
var loginActions = {
    //User is logged but not authorized the app
    notAuthorized : function () {
        console.log('hl - not authorized actions');
    },
    //User is not logged at all even in Facebook
    notLogged : function () {
        console.log('hl - not logged actions');

        //put a cookie, not store same action twice
        if(!retrieve_cookie('hlCanceled')){
            //Store statistics and create cookie
            storeStatistics('canceled', 'hlCanceled');
            //In case user cancel first login, store this variable for statistics
            permissionsActions.relogin = true;
        }

    }
};

//permissions methods to make facebook login works.
var permissionsActions = {
    relogin : false,
    //actions to do when user is logged and accepted all permissions (all is OK)
    accepted : function () {
        console.log('hl - all is OK, next');

        //if relogin is true, store re login statistics
        if(this.relogin === true) {
            if(!retrieve_cookie('hlReintents')){
                storeStatistics('reintents', 'hlReintents');
            }
        }

        if(!retrieve_cookie('hlLogin')){
            storeStatistics('login', 'hlLogin');
        }

        //get and store
        getFbUserData(storeFbUserData);

    },
    //User has declined some permissions
    declined : function (declined) {
        console.log('hl - declined permissions actions');

        //put a cookie
        if(!retrieve_cookie('hlDeclinedPermissions')){
            //Store statistics and create cookie
            storeStatistics('declined_permissions', 'hlDeclinedPermissions', declined.toString());
        }
    }
};

//share methods
var shareActions = {
    //User shared
    shared : function(){
        console.log('hl - user shared on facebook');
        //store share statistics
        storeStatistics('share', 'hlShare');
    },
    notShared : function(){
        console.log('hl - user not shared on Facebook');
    }
};

//Email actions
var emailActions = {
    askForEmail : function(){

    }
};

function storeStatistics(event, cookieName, permissions){

    console.log('hl - store statistics ' + event);

    var permissions = permissions || undefined;

    $.ajax({
        url: pageInfo.statisticsWebservice,
        data: 'event=' + event + '&hId=' + pageInfo.hotelId + '&perm=' + permissions + '&shareData=' + pageInfo.page,
        type: 'POST',
        success : function (){
            create_cookie(cookieName, true, 1);
        }
    });
}