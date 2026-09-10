//Do login button or action
function fb_login(rerequest, redirect) {
    console.log("hl - login attempt");

    var actualScope;

    //scope object
    if(typeof pageInfo.scope != 'undefined'){
        actualScope = pageInfo.scope;
    }else{
        actualScope ="email, public_profile, user_friends, publish_actions, user_location, user_birthday"
    }

    var object = {
        scope: actualScope,
        return_scopes: true
    };

    //if RE request then add rerequest to objects
    if (rerequest) {
        object.auth_type = "rerequest";
    }

    //in some cases is better to user a redirect method instead a popup
    if(redirect){
        window.top.location.href = "https://www.facebook.com/dialog/oauth?app_id=" + pageInfo.appId + "&scope=" + object.scope + "&response_type=token&redirect_uri=" + pageInfo.redirectUri;
    }else{
        //the real facebook login function
        FB.login(function(response) {
            // The response object is returned with a status field that lets the
            // app know the current login status of the person.
            checkLogin(response, loginActions);
            // console.log(response);
        }, object);

    }
}

//Check is user as granted all permissions, accepts a callback array of methods to handle permission statuses
function checkPermissions(object) {
    //Must be an object
    if (typeof object !== "object") {
        console.log("hl checkPermissions - you need to pass an object permissionsActions to this function");
        return;
    }
    //must be methods accept and decline inside this object
    //one method handles all is OK and the other if the user has declined something;
    if (typeof object.accepted !== "function" || typeof object.declined !== "function") {
        console.log("hl - permissionsActions object needs two have methods, accept and decline");
        return;
    }
    console.log("hl - check permissions");
    FB.api("/me/permissions", function(response) {
        var declined = [];
        //Check if permissions declined are more than 1
        for (i = 0; i < response.data.length; i++) {
            if (response.data[i].status === "declined") {
                declined.push(response.data[i].permission);
            }
        }
        if (declined.length === 0) {
            //all ok exec callback
            console.log("hl - all is OK, do callback action");
            object.accepted();
            return;
        }
        //Not OK re login
        console.log("hl - declined permissions list: " + declined);
        object.declined(declined);
    });
}

//Check login status, accepts a callback array of methods to handle statuses
function checkLogin(response, object) {
    //Must be an object
    if (typeof object !== "object") {
        console.log("hl checkLogin - you need to pass an object loginActions to this function");
        return;
    }
    //must be methods accept and decline inside this object
    //one method handles not authorized and the other not logged
    if (typeof object.notAuthorized !== "function" || typeof object.notLogged !== "function") {
        console.log("hl - loginActions object needs to have two methods, notAuthorized and notLogged");
        return;
    }
    if (response.status === "connected") {
        // ok, check for permissions
        checkPermissions(permissionsActions);
    } else if (response.status === "not_authorized") {
        // The person is logged into Facebook, but not your app.
        console.log("hl - not authorized the app");
        object.notAuthorized();
    } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        console.log("hl - not logged at all");
        object.notLogged();
    }
}

//Get user data
function getFbUserData(callback) {
    console.log("hl - get Facebook User Data");
    FB.api("/me?fields=id,name,email,friends,locale,location,gender,birthday", function(response) {
        console.log('hl - recoge los datos del usuario');
        console.log(response);
        //store user ID in shareObject
            if(typeof  pageInfo != 'undefined')
                pageInfo.userId = response.id;

        callback(response);
    });
}