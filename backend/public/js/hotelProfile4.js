$(document).ready(function() {

    $(".langLia").click(function(e) {
        e.preventDefault();
    });
    
    $('.validation-form').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-exclamation',
            validating: 'fa fa-refresh'
        },
        live: 'enabled',
        fields: {
            tagline: {
                validators: {
                    noEmpty: {
                        message: 'The tagline can´t be empty'
                    }
                }
            },
            landingBg: {
                validators: {
                    noEmpty: {
                        message: 'Please send a background'
                    },
                    file: {
                        extension: 'jpeg,png,gif,jpg',
                        type: 'image/jpeg,image/png, image/gif, image/jpg',
                        message: 'The selected file is not valid'
                    }
                }
            }
        }
    });
    $('.validation-form').bootstrapValidator('validate');
    checkTaglines();
});

$('#tagline').keyup(function() { // ----tagline
    var tagline = $("#tagline").val();
    $.ajax({
        url: "/lib/webservices/hotel-profile-datos-landing-ws.php",
        data: 'tagline=' + tagline + '',
        type: 'POST',
        success: function() {
            checkTagline();
        }
    });
});

function changeLang(lang) {
    $.ajax({
        url: "/lib/webservices/hotel-profile-datos-landing-ws.php",
        data: 'lang=' + lang + '',
        type: 'POST',
        success: function(output) {
            var obj = jQuery.parseJSON(output);
            //Lang
            var langDiv = obj['lang'];
            $('#langSected').empty();
            $('#langSected').append(langDiv);
            //Name
            var tagline = obj['tagline'];
            $('#tagline').val(tagline);
            if (tagline.length == 0) { //Está vacio
                //Poner exclamation en el tagline
            }
            //Lang hidden
            $('#actualLang').val(lang);
        }
    });
};

function checkTagline() {
    var actualLang = $("#actualLang").val();
    $.ajax({
        url: "/lib/webservices/hotel-profile-datos-landing-ws.php",
        data: 'checkTagline=1&langToCheck=' + actualLang + '',
        type: 'POST',
        success: function(output) {
            var obj = jQuery.parseJSON(output);
            //alert('checkLang'+obj['code']);
            if (obj['code'] == '200') {
                $('#circle-' + actualLang).empty();
                $('#circle-' + actualLang).append('<i class="fa fa-check-circle-o verde lang-ok">');
            } else {
                $('#circle-' + actualLang).empty();
            }
        }
    });
};

function checkTaglines() {
    $.ajax({
        url: "/lib/webservices/hotel-profile-datos-landing-ws.php",
        data: 'checkTaglines=1',
        type: 'POST',
        success: function(output) {
            var obj = jQuery.parseJSON(output);
            obj.forEach(function(data) {
                if (data['code'] == '200') {
                    $('#circle-' + data['lang']).empty();
                    $('#circle-' + data['lang']).append('<i class="fa fa-check-circle-o verde lang-ok">');
                } else {
                    $('#circle-' + data['lang']).empty();
                }
            });
        }
    });
};
