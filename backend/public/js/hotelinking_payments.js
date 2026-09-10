var platforms = null;
var usedMethods = null;
var paymentsIntegrationsConfig = null
var brandId = null

var showPaymentsLoader = () => {
    $("#paymentsForm").hide();
    $("#paymentsLoader").fadeIn();
}

var showPaymentsForm = () => {
    $("#paymentsLoader").hide();
    $("#paymentsForm").fadeIn();
}

var listPaymentConfigForBrand = (brand_id) => {

    showPaymentsLoader();
    brandId = brand_id;

    $.ajax({
        url: '/lib/webservices/payments-ws.php',
        data: {
            'brand_id': brand_id
        },
        type: 'GET',
        success: function (response) {
            paymentsIntegrationsConfig = response.data;

            usedMethods = paymentsIntegrationsConfig.map(function (value) {
                return value.method.id;
            });

            if (paymentsIntegrationsConfig) {
                // Clean tabs html.
                $("#nav-tabs-payments").html("");

                if (paymentsIntegrationsConfig.length >= 1) {
                    Object.keys(paymentsIntegrationsConfig).forEach(key => {
                        var active = key == 0 ? "active" : "";
                        $("#nav-tabs-payments").append(
                            '<li class="' + active + '"><a data-toggle="tab" onclick="renderFormFromTab(' + key + ') "href="#">Config ' + paymentsIntegrationsConfig[key].method.name + '</a></li>');
                    });

                    // Tab to add extra data.
                    $("#nav-tabs-payments").append(
                        '<li><a data-toggle="tab" href="#newconfig" onclick="getPlatformsAndRender()">New Config <i class="fa fa-plus-circle"></i></a></li>'
                    );

                    renderPaymentsForm(paymentsIntegrationsConfig[0]);
                } else {
                    // Tab to add extra data.
                    $("#nav-tabs-payments").append(
                        "<li><a data-toggle='tab'>New Config <i class='fa fa-plus-circle'></i></a></li>"
                    );

                    getPlatformsAndRender();
                }
            }
            showPaymentsForm();
        }
    });        
};

var getPlatformsAndRender = () => {
    showPaymentsLoader();

    if (!platforms) {
        $.ajax({
            url: '/lib/webservices/payments-ws.php',
            data: null,
            type: 'GET',
            success: function (response) {
                platforms = response;
                renderNewPaymentsForm(platforms, 0);
                showPaymentsForm();
            }
        });
    } else {
        renderNewPaymentsForm(platforms, 0);
    } 
}

var renderFormFromTab = (key) => {
    renderPaymentsForm(paymentsIntegrationsConfig[key]);
    showPaymentsForm();
}

// Render form for the active tab.
var renderPaymentsForm = (config) => {
    $("#tab-content-payments").html('<div id="config' + config.id + '" class="tab-pane fade in active">' +
            '<h3>Config ' + config.method.name + '</h3>' +
        '</div>' +
        '<div class="row" id="paymentsLoader">' +
            '<div class="col-lg-12 text-center">' +
                '<div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div>' +
            '</div>' +
        '</div>' +
        '<div class="row" id="paymentsForm">' +
            '<div class="col-lg-12">' +
                '<form method="POST" action="/lib/webservices/payments-ws.php">' +
                    '<div class="row form-group">' +
                        '<div class="col-lg-12 ">' +
                            '<label for="platform_id">Payment platform:</label>' +
                            '<select class="form-control" id="' + config.platform.id + '_platform_id" name="platform_id" disabled>' +
                                '<option>' + config.platform.name + '</option>' +
                            '</select>' +
                        '</div>' +
                        '<div class="col-lg-10">' +
                            '<label for="method_id">Payment method:</label>' +
                            '<select class="form-control" id="' + config.method.id + '_method_id" name="method_id" disabled>' +
                                '<option>' + config.method.name + '</option>' +
                            '</select>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row form-group">' +
                        '<div class="col-lg-12 mt-2">' +
                            "For security reasons we cannot show the integration configuration as it contains sensitive information." +
                        '</div>' +
                    '</div>' +
                    '<input type="hidden" name="brand_id" value="' + brandId + '">' +
                    '<input type="hidden" name="integration_id" value="' + config.id + '">' +
                    '<input type="hidden" name="action" value="delete">' +
                    '<button type="submit" class="btn btn-warning pull-left">Delete</button>' +
                    '<button type="submit" class="btn btn-success pull-right" disabled>Save this configuration</button>' +
                '</form>' +
            '</div>' +
        '</div>');
}

// Render form for the new tab.
var renderNewPaymentsForm = (platforms, index) => {
    showPaymentsLoader();

    newConfigContent = '<div class="tab-pane fade in active">' +
            '<h3>New Integration Config</h3>' +
        '</div>' +
        '<div class="row" id="paymentsLoader">' +
            '<div class="col-lg-12 text-center">' +
                '<div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div>' +
            '</div>' +
        '</div>' +
        '<div class="row" id="paymentsForm">' +
            '<div class="col-lg-12">' +
                '<form method="POST" action="/lib/webservices/payments-ws.php">' +
                    '<div class="row form-group">' +
                        '<div class="col-lg-12 ">' +
                            '<label for="platform_id">Payment platform:</label>' +
                            '<select id="platform-selector" class="form-control" name="platform_id" required>';

    Object.entries(platforms.data).forEach(([key, value]) => {
        var selected = key == index ? "selected=selected" : "";
        newConfigContent +=     '<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>'
    });
        
    newConfigContent +=    '</select>' +
                        '</div>' +
                        '<div class="col-lg-10">' +
                            '<label for="method_id">Payment method:</label>' +
                            '<select class="form-control" name="method_id" required>';

    var formDisabled = "disabled";
    Object.entries(platforms.data[index].available_methods).forEach(([key, value]) => {
        var disabled = usedMethods.includes(value.id) ? "disabled=disabled" : ""
        var selected = !disabled ? "selected=selected" : ""
        formDisabled = selected ? "" : formDisabled;
        newConfigContent += '   <option ' + disabled + ' ' + selected + ' value="' + value.id + '">' + value.name + '</option>'
    });
    newConfigContent +=     '</select>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row form-group">' +
                        '<div class="col-lg-12 mt-2">';
    
    Object.entries(JSON.parse(platforms.data[index].config_schema).properties ?? []).forEach(([key, value]) => {
        if (value.type === "string") {
            newConfigContent += '<label for="' + key + '">' + value.description + '</label>' +
                '<input type="text" class="form-control" autocomplete=off name="config_' + key + '" required>';
        }
        
        if (value.type === "boolean") {
            newConfigContent += '<input type="checkbox" id="config_' + key + '">' +
            '<label for="config_' + key + '" style="margin-left:2px;">' + value.description + '</label>' +
            '<input type="hidden" name="config_' + key + '" value="false">';
        }
    });
    
    newConfigContent += '</div>' +
                    '</div>' +
                    '<input type="hidden" name="brand_id" value="' + brandId + '">' +
                    '<input type="hidden" name="action" value="create">' +
                    '<button type="submit" class="btn btn-warning pull-left" disabled>Delete</button>' +
                    '<button type="submit" class="btn btn-success pull-right" ' + formDisabled + '>Save this configuration</button>' +
                '</form>' +
            '</div>' +
        '</div>';

    $("#tab-content-payments").html(newConfigContent);
    showPaymentsForm();
};

$(document).on("change", '#platform-selector', function () {
    $("#platform").prop("selectedIndex", this.selectedIndex);
    renderNewPaymentsForm(platforms, this.selectedIndex)
});

$(document).on("change", '[id^=config_]', function (element) {
    var value = $(this).is(':checked') ? 'true' : 'false';
    $('[name="' + element.target.id + '"]').val(value);

});