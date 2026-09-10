<script>
    //Guardar datos de FB
    function storeFbUserData(response, pageInfo){
        //Define variables, check friends and location
        var storeResponse = {};
        var friends = response.friends.summary.total_count || 0;
        var locationId = response.location.id || null;
        var locationName = response.location.name || null;

        //Eventually facebook don´t returns an email from user.
        if(!response.email){
            console.log('hl - Email no está dentro del objeto del usuario.');
            storeResponse.error = true;
            storeResponse.message = 'email is undefined';
            storeResponse.code = 4101;
            return storeResponse;
        }

        //Mandatory params to webservice, jQuery params.
        var params = {
                fbid : response.id,
                fbname : response.name,
                fbemail : response.email,
                fbfriends : friends,
                gender : response.gender,
                locale : response.locale,
                locationId : locationId,
                locationName : locationName,
                hlid : pageInfo.hotelId,
                lang : pageInfo.userLang,
                pageType : pageInfo['page'];
            },
            ​query = $.param(params);

        //Call the webservice
        $.ajax({
            url: "/lib/webservices/" + pageInfo['webService'],
            data: query,
            type: 'POST',
            success: function(data) {
                console.log(data);
            }
        });
    }
</script>