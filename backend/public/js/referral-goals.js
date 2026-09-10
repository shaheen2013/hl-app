// JavaScript Document
function mostrarGoals() {
    $.ajax({
        url: "/lib/webservices/referral-goals-ws.php",
        data: 'showGoals=1',
        type: 'POST',
        async: false,
        success: function (output) {
            $('#goals-div').append(output);
        }
    });
}

function actualizarLinea(id) {
    //Input
    $('#goal-0').attr('name', 'goal-' + id);
    $('#goal-0').attr('onkeyup', 'saveGoal(this, ' + id + ')');
    $('#goal-0').attr('id', 'goal-' + id);
    $('#newOffer-0').attr('href', 'referral-goals/?newOffer=' + id);
    //Select
    $('#goalSelect-0').attr('id', 'goalSelect-' + id);
}

function saveGoal(thisObj, idGoal) {
    var nGoal = $(thisObj).val();
    var currentId = $(thisObj).attr('id');
    var previousValue = $('#' + currentId + '-h').attr('value');
    if (nGoal != previousValue) {
        //Ha cambiado el valor
        if ($.isNumeric(nGoal)) {
            if (nGoal !== '' && nGoal != '0') {
                console.log(nGoal, idGoal);
                return;

                $.ajax({
                    url: "/lib/webservices/referral-goals-ws.php",
                    data: 'saveGoal=1&n=' + nGoal + '&id=' + idGoal,
                    type: 'POST',
                    async: false,
                    success: function (output) {
                        var json = JSON.parse(output);
                        if (json.exists == '1') {
                            //alert ('exists');
                            $(thisObj).css("background-color", "#FF9491");
                        } else {
                            if (json.id != '0') {
                                actualizarLinea(json.id);
                            }
                            $(thisObj).css("background-color", "#FFFFFF");
                            //mostrarGoals();
                            $('#newOffer-0').removeAttr('disabled');
                            //-
                            $('#' + currentId + '-h').val(nGoal);
                        }
                        //Feedback
                        showError(json.code);
                    }
                });
            }
        } else {
            //Not a number
            $(thisObj).css("background-color", "#FF9491");
        }
    }
}

function saveGoalOffer(thisObj) {
    var idOffer = $(thisObj).val();
    var idGoal = $(thisObj).attr('id');
    $.ajax({
        url: "/lib/webservices/referral-goals-ws.php",
        data: 'saveGoalOffer=1&idGoal=' + idGoal + '&idOffer=' + idOffer,
        type: 'POST',
        success: function (output) {
            var json = JSON.parse(output);
            if (json.id != '0') {
                var id = output.replace(/\s/g, "");
                actualizarLinea(id);
            }
            mostrarGoals();
            //Feedback
            showError(json.code);
        }
    });
}

function addBlankLine() {
    $.ajax({
        url: "/lib/webservices/referral-goals-ws.php",
        data: 'addBlankLine=1',
        type: 'POST',
        success: function (output) {
            $('.plusSpan').hide();
            $('#goals-div').append(output);
            $('#newOffer-0').attr('disabled', 'disabled');
        }
    });
}

function removeGoal(goal) {
    if(goal != 0) {
        $.ajax({
            url: "/lib/webservices/referral-goals-ws.php",
            data: 'delGoal=' + goal,
            type: 'POST',
            success: function (output) {
                var json = JSON.parse(output);
                $('#line-' + goal).remove();
                //Feedback
                showError(json.code);
            }
        });
    }
}

function removeOfferByType(type) {

    // if (my.attr('id') == 'select-wifi') {
    //     $('#select-wifi-conditions, #select-wifi-redeem_type').prop('disabled', true);
    // }

    $.ajax({
        url: "/lib/webservices/referral-goals-ws.php",
        data: 'type=' + type + '&action=removeOfferByType',
        type: 'POST',
        success: function (output) {
            var data = JSON.parse(output);
            //Feedback
            if (data.msgError !== "") {
                showError(data.msgError);
            }

            //Update field
            $("#select-" + type).val([0]);
        }
    });

}



//On select an option in dropdown, delete or update it
$('#select-prestay, #select-wifi, #select-wifi-conditions, #select-wifi-redeem_type').on('change', function () {

    var my = $(this);

    value = my[0].value;
    type = my.data('type');

    if (value == 0) {
        removeOfferByType(my, type);
        return;
    }

    if (my.attr('id') == 'select-wifi') {
        $('#select-wifi-conditions,#select-wifi-redeem_type').prop('disabled', false);
    }

    if (my.attr('id') == 'select-loyalty') {
        $('#select-loyalty-conditions,#select-loyalty-redeem_type').prop('disabled', false);
    }

    switch (type) {
        case 'wifi':
        case 'prestay':
            action = 'updateOfferByType';
            break;
        case 'condicion':
        case 'tipo_oferta':
            action = 'updateWifiOfferOptions';
            break;
    }

    $.ajax({
        url: "/lib/webservices/referral-goals-ws.php",
        data: 'value=' + value + '&type=' + type + '&action=' + action,
        type: 'POST',
        success: function (output) {
            var json = JSON.parse(output);
            //Feedback
            showError(json.code);
        }
    });
});

$('#input-offer-wifi-days').keyup(function () {
    var my = $(this);
    value = my[0].value;
    type = my.data('type');
    action = 'updateWifiOfferOptions';

    $.ajax({
        url: "/lib/webservices/referral-goals-ws.php",
        data: 'value=' + value + '&type=' + type + '&action=' + action,
        type: 'POST',
        success: function (output) {
            var json = JSON.parse(output);
            //Feedback
            showError(json.code);
        }
    });
});

$('#wifiIntegrationForm').on('submit', function (e) {
    var selectStay = $('#selectStay').val();
    if (selectStay && selectStay.indexOf("http://") < 0 && selectStay.indexOf("https://") < 0 && selectStay != '') {
        selectStay = 'http://' + selectStay;
        // $('#selectStay').val(selectStay);
    }

    //store variables in object
    variables = {
        action: 'stay',
        stay: selectStay || 'Set url...',
        loginUrl: $('#stay-login-form').val(),
        username: $('#stay-username').val(),
        password: $('#stay-password').val(),
        guest_enabled: !!$('#stay-guest-enabled').prop('checked'),
        secret: $('#stay-secret').val(),
        time: $('#stay-time').val(),
        siteID: $('#stay-siteID').val(),
        userLang: $('#userLang').val()
    };


    //Remove nulls and undefineds from object
    for (var key in variables) {
        if (variables[key] == undefined || variables[key] == null)
            delete variables[key];
    }

    $.ajax({
        url: "/lib/webservices/referral-goals-ws.php",
        data: variables,
        type: 'POST',
        success: function (output) {
            var json = JSON.parse(output);
            //Feedback
            showError(json.code);
        }
    });
});

$('#select-poststay').on('change', function () {
    if (this.value == '0') {
        removeStay('poststay');
    } else {
        $.ajax({
            url: "/lib/webservices/referral-goals-ws.php",
            data: 'poststay=' + this.value + '&action=poststay',
            type: 'POST',
            success: function (output) {

                var json = JSON.parse(output);
                //Feedback
                showError(json.code);
            }
        });
    }
});






//NEW OFFERS


//when a form is submitted
$("form[id*=form-]").on("submit", function (e) {
    e.preventDefault();

    //if form is valid
    if ($(this).valid()) {

        //call the offers webservice passing the form data
        return $.ajax({
            url: "/lib/webservices/offers-ws.php",
            data: $(this).serialize(),
            type: 'POST',
            success: function (output) {
                if (output && output.code) {
                    showError(output.code);
                }
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.code) {
                    showError(error.responseJSON.code)
                }
            }
        });

    }
})

//when a select or input changes
//then try and submitting the form it is attached to
$('[form*=form-], [id*=offer-]').on("change", function (e) {
    var form = e.currentTarget.form;
    if (!form) {
        return;
    }

    //enable the elements of the form that are disabled
    $(`[form*=${form.id}]`).prop("disabled", false);
    $(form).children('input').prop("disabled", false);

    // submit
    return $(form).submit()
});

$(document).ready(function () {
    var submit = true;
    var defaultOfferWifiRow;
    var defaultOfferWifi = $('.first-row');
    var offerIndex = $('.row-offer-wifi').length;

    if (offerIndex) {
        defaultOfferWifiRow = defaultOfferWifi.clone();
        defaultOfferWifiRow.removeClass('first-row');
        defaultOfferWifiRow.find('option').prop('selected', false);
        defaultOfferWifiRow.find('input.offer-wifi-date').val('');
        defaultOfferWifiRow.find('input[type="hidden"]').val('');
        defaultOfferWifiRow.find('input.offer-wifi-period').val(30);
        defaultOfferWifiRow.find('input.offer-wifi-index').val('');
        defaultOfferWifiRow.find('button.remove-offer-wifi').attr('data-id', '');
    }
    
    $(document.body).on('focus', '.offer-wifi-date', function () {
        $(this).datepicker({
            dateFormat: "dd/mm/yy"
        });
    });

    accommodatedDefault = null;
    nonAccomodatedDefault = null;

    $(".default:checked").each(function() {
        if ($(this).closest('.row-offer-wifi').find('.accommodated:checked').length > 0) {
            accommodatedDefault = $(this).closest('.row-offer-wifi').find(`input[name^='id[]']`).val();
        }

        if ($(this).closest('.row-offer-wifi').find('.non_accommodated:checked').length > 0) {
            nonAccomodatedDefault = $(this).closest('.row-offer-wifi').find(`input[name^='id[]']`).val();
        }
    });

    $(document.body).on('click', 'button.remove-offer-wifi', function (e) {
        e.preventDefault();
        var id = parseInt($(this).data('id'));
        var parent = $(this).closest('.row-offer-wifi');
        var action = $('#brand-offer-wifi').prop('action');
        var deleteOfferWifi = function () {
            parent.fadeOut(200, function () {
                $(this).remove();
            });
        };
        if (id) {
            $('#delete-offer-wifi').modal('show').on('shown.bs.modal', function () {
                $(this).find('button.btn-primary').click(function (e) {
                    $.post(action, {
                        'offer_wifi_action': 'delete',
                        'id': id,
                    }).done(function (response) {
                        if (response.deleted) {
                            if (accommodatedDefault == id) {
                                accommodatedDefault = null;
                            }
                            
                            if (nonAccomodatedDefault == id) {
                                nonAccomodatedDefault = null;
                            }

                            deleteOfferWifi();
                        }
                    });
                });
            });
        } else {
            deleteOfferWifi();
        }
    });
    
    // Delete the previous default offer and assign the new one when the modal button is clicked
    $(document.body).on('click', 'button#delte-default-offer-button', function (e) {
        e.preventDefault();       
        var actualOffer =  $(`.offer-wifi-index[value='` + $('#default-selected').val() +`']`).closest('.row-offer-wifi');
        var actualOfferIsAccommodated = actualOffer.find('.accommodated:checked').length > 0;
        var actualOfferIsNonAccommodated = actualOffer.find('.non_accommodated:checked').length > 0;

        offersToDelete = [];

        if (actualOfferIsAccommodated && accommodatedDefault) {
            offersToDelete.push(accommodatedDefault);
        }
        
        if (actualOfferIsNonAccommodated && nonAccomodatedDefault) {
            offersToDelete.push(nonAccomodatedDefault);
        }
          
        var action = $('#brand-offer-wifi').prop('action');
        
        var process = 0;

        offersToDelete.forEach(function(value, index, array) {
            var deleteOfferWifi = function (value) {
                $(`input[name^='id[]'][value='` + value + `']`).closest('.row-offer-wifi').remove();
            }; 

            $.post(action, {
                'offer_wifi_action': 'delete',
                'id': value
            }).done(function (response) {
                if (response.deleted) {
                    deleteOfferWifi(value);
                    
                    process++;
                    if (process == array.length) {
                        actualOffer.find(`input[name^='valid_from[]']`).val('')
                        actualOffer.find(`input[name^='valid_to[]']`).val('')
                        actualOffer.find('.default').prop('checked', true);
                        actualOffer.find('.defaultHidden').val('1');
                        $("button[name='offer_wifi_action']").click();
                    }
                }
            });
        });
    });

    // Show modal when an offer is marked as default and there was already one marked
    $('#offer-wifi-body').on('mousedown', '.default', function(e) {
        var offerID  = $(this).closest('.row-offer-wifi').find(`input[name^='id[]']`).val();
        var isAccommodated  = $(this).closest('.row-offer-wifi').find('.accommodated:checked').length > 0;
        var isNonAccommodated  = $(this).closest('.row-offer-wifi').find('.non_accommodated:checked').length > 0;

        if (isNonAccommodated && nonAccomodatedDefault && nonAccomodatedDefault == offerID) {
            nonAccomodatedDefault = null;
        }
        
        if (isAccommodated && accommodatedDefault && accommodatedDefault == offerID) {
            accommodatedDefault = null;
        }

        if ((isAccommodated && accommodatedDefault && accommodatedDefault != offerID) || (isNonAccommodated && nonAccomodatedDefault && nonAccomodatedDefault != offerID)) {      
            $('#change-default-modal').modal('show');
            $('#default-selected').val($(this).closest('.row-offer-wifi').find(`input[name^='index[]']`).val());

        }
    });

    // Change value of any checkbox to mark as checked or unchecked
    $(document).on("change", 'input[type=checkbox]', function (e) {
        var target = $(this).parent().find('input[name^=' + e.target.className + ']').val();
        target = target == 0 ? 1 : 0;
        
        $(this).parent().find('input[name^=' + e.target.className + ']').val(target);
    });

    // Empty date field when placing default offer
    $(document.body).on('click', '.default', function (e) {
        var parent = $(this).closest('.row-offer-wifi');
        parent.find('.offer-wifi-date').val('');
    });

    // Uncheck default offer when filling in some date
    $(document.body).on('change', '#brand-offer-wifi input.offer-wifi-date', function (e) {
        var bigParent = $(this).closest('.row-offer-wifi');
        var daddy = $(this).closest('.offer-wifi-dates');
        var value = this.value;
        var pattern = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;

        bigParent.find('.default').prop('checked', false);

        if (value && !pattern.test(value)) {
            submit = false;
            daddy.find('span').show();
        } else {
            submit = true;
            daddy.find('span').hide();
        }

    });

    $('#add-offer-wifi').on('click', function (e) {
        e.preventDefault();

        if (defaultOfferWifiRow.length) {
            var newOfferWifiRow = defaultOfferWifiRow.clone().hide();
            newOfferWifiRow.find('input.offer-wifi-index').val(offerIndex);
            newOfferWifiRow.find('.accommodated').prop("checked", false);
            newOfferWifiRow.find('.non_accommodated').prop("checked", false);
            newOfferWifiRow.find('.default').prop("checked", false);
            newOfferWifiRow.find('.defaultHidden').prop("value", 0);
            newOfferWifiRow.find('.accommodatedHidden').prop("value", 0);
            newOfferWifiRow.find('.nonAccommodatedHidden').prop("value", 0);
            offerIndex ++;
            $("#offer-wifi-body tbody").append(newOfferWifiRow);
            newOfferWifiRow.fadeIn();
            
        }
    });

    $('#brand-offer-wifi').on('submit', function (e) {
        if (!submit) {
            e.preventDefault();
        }
    });
});
