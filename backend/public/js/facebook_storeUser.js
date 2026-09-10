//Guardar datos de FB
function storeFbUserData(response, testEmailUndefined) {

    console.log('hl - guardar usuario');

    //Define variables
    var friends = response.friends.summary.total_count || 0;

    //Mandatory params to webservice, jQuery params.
    var params = {
            fbid: response.id,
            fbname: response.name,
            fbemail: response.email,
            fbfriends: friends,
            gender: response.gender,
            locale: response.locale,
            hlid: pageInfo.hotelId,
            lang: pageInfo.userLang,
            birthday : response.birthday,
            generateUrl : pageInfo.generateUrl, //expect URL from webservice,
            cid : pageInfo.cadenaId,
            token : pageInfo.token,
            arrayDatosHotel : pageInfo.datosHotel,
            ofertaReferral : pageInfo.ofertaReferral,
            cookie : pageInfo.cookie,
            guid : pageInfo.guid,
            referrer_id : pageInfo.referrerId,
            facebook_location_name: (response.location && response.location.name) ? response.location.name : null,
            facebook_location_id: (response.location && response.location.id) ? response.location.id : null
        },
        query = EncodeQueryData(params);
    
    //TEST
    if (testEmailUndefined){
        params.fbemail = "undefined";
        console.log('hl - CUIDADO, el email se ha forzado a undefined para test, check getFbUserData');
    }
    //TEST

    //Algunos usuarios de Facebook vienen con email undefined
    if (params.fbemail == "undefined" || !params.fbemail){

        console.log('hl - email es undefined');

        //Check if local storage está activo
        if(isLocalStorageActive()){
            //Store user in localstorage
            localStorage.setItem('hlparams', JSON.stringify(response));

        }else{
            //store user in pageInfo
            pageInfo.user = response;

        }
    }


    //Call the webservice
    $.ajax({
        url: pageInfo.webService,
        data: query,
        type: 'POST',
        success: function(data) {
            try {
            data = JSON.parse(data);
            //Respuesta del webservice
            if(data.code === '200'){

                console.log('hl - usuario guardado');

                //Check cual es el resultado
                switch (data.subcodes.code) {
                    case '200':
                        console.log('hl - 200 - ' + data.subcodes.message);
                        break;
                }; 
                

                //Si es la landing hay que guardar una cookie
                if(pageInfo.page === 'landing'){
                    //Creamos la cookie con los valores devueltos del servidor.
                    var cookieValue = {
                        "hluid" : data.subcodes.hluid,
                        "hlh"   : data.subcodes.hlh,
                        "hlt"   : data.subcodes.hlt,
                        "hlcid" : data.subcodes.hlcid
                    }
                    create_cookie('hltc_' + pageInfo.hotelId + '_' + pageInfo.cadenaId, JSON.stringify(cookieValue), 365 );
                }

                //store promocode if there is one.
                pageInfo.promo = data.promo || null;

                //siguiente paso
                if(typeof actionAfterStore != 'undefined'){
                    console.log('hl - do action after store');
                    actionAfterStore();
                }


                if(data['referrer_token'] && data['user_id']){
                    window.shareObject.link += "&hltr=facebook&hlre="+ data['user_id']  +"&hlho=" + params.hlid + "&hlch="+ params.cid + "&hltoken=" + data['referrer_token'];
                }

            }


            //some variable is not present
            if(data.code === '401'){
                // No hay los datos mínimos
                console.log('hl - 401 - Error de datos ' + data.message);

                for(var i = 0; i < data.subcodes.length; i++ ) {
                    switch (data.subcodes[i].code) {
                        case '4015':
                            console.log('hl - 4015 - ' + data.subcodes[i].message);
                            askForEmail(response);
                            break;
                        case '4016':
                            console.log('hl - 4016 - ' + data.subcodes[i].message);
                            askForEmail(response);
                            break;
                        case '4017':
                            console.log('hl - 4017 - ' + data.subcodes[i].message);
                            askForEmail(response);
                            break;
                        default:
                            console.log('hl - ' + data.subcodes[i].code + ' - ' + data.subcodes[i].message);
                            //siguiente paso
                            if(typeof errorAfterStore != 'undefined'){
                                errorAfterStore(data.subcodes[i].code);
                            }

                            break;
                    }
                }
            }
            }catch (err){

                console.log(err);
            }
        }
    });
}

//create query string for the webservice
function EncodeQueryData(data)
{
   var ret = [];
   for (var d in data)
      ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(data[d]));
   return ret.join("&");
}

//Check local storage
function isLocalStorageActive(){
    var test = 'test';
    try {
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
    } catch(e) {
        return false;
    }
};

//when facebook email is undefined we need to ask for a new email
//this form do this, and store the email in the user object.
//it gets the user from localstorage or pageInfo object
//#fb-email and .fb-email-btn are required in DOM
$('.fb-email-btn').click(function(){

    if(isLocalStorageActive()){

        //get item from storage
        var user = localStorage.getItem('hlparams');

        //convert to object
        var user = JSON.parse(user);

    }else{

        var user = pageInfo.user;

    }

    //assign new email
    user.email = $('#fb-email').val();

    //try to store user again
    storeFbUserData(user);

});
