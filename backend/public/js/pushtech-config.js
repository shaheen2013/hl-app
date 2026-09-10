//Save mapping on click
$('.save-mapping-button').click(function () {
    performActionOnWebservice(this, 'updateCampaign')
});

//Delete mapping on click
$('.delete-mapping-button').click(function () {
    performActionOnWebservice(this, 'deleteMapping');
});

/**
 * Send action to webservice
 * @param self
 * @param action
 */
function performActionOnWebservice(self, action) {
    //Get mapping id on DDBB
    mapping_id = $(self).data('mapping_id');
    //Get campaign and offer
    campaign = $('#pushtech_campaign_' + mapping_id).val();
    offer = $('#hotelinking_offer_' + mapping_id).val();
    id_hotel = $('.add-new-mapping').data('id_hotel');

    //Querys
    if (action == 'updateCampaign') data = {campaign : campaign, offer :  offer,  id_hotel : id_hotel, action : action};
    if (action == 'deleteMapping') data = {id : mapping_id, action : action};

    //Send ajax
    $.ajax({
        url: "lib/webservices/pushtech_api_webservice.php",
        data: data,
        type: 'POST',
        async: false,
        success: function (output) {
            var data = JSON.parse(output);
            //If Delete refresh the page
            if(data['do'] === 'refresh')
                window.location.href = window.location.href;
        }
    });
}