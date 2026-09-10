function fb_share(object, shareObject) {
    console.log('sharing');
    //Must be an object
    if (typeof object !== 'object') {
        console.log('hl shareActions - you need to pass an object shareActions to this function');
        return;
    }

    //Must be a share object
    if (typeof shareObject !== 'object') {
        console.log('hl shareObject - must be defined, it defines the share method and properties');
        return;
    }

    //must be methods shared and notShared inside this object
    //one method handles share action and the other cancel action
    if ((typeof object.shared !== 'function') || (typeof object.notShared !== 'function')) {
        console.log('hl - shareActions object needs to have two methods, shared and notShared');
        return;
    }


    FB.ui(
        shareObject,
        function(response) {
            // console.log(response);
            // if (response && response.post_id) {
                if (response ) {

                //Do actions when user shared
                object.shared();

                var userID = pageInfo.userId || shareObject.userId;

                var postId = response.post_id ? response.post_id : null;
                
                //store share in DDBB via WS
                $.ajax({
                    url: pageInfo.shareWebservice,
                    data:   'smUId=' + userID +
                            '&hId=' + pageInfo.hotelId + 
                            '&shId=' + postId +
                            '&idTSh=' + pageInfo.shareType + 
                            '&transaction=' + pageInfo.transaction,
                    type: 'POST',
                    success : function (response){
                        console.log('hl - share stored in DDBB ' + response);
                    }
                });

            } else {
                object.notShared();
            }

        });
}