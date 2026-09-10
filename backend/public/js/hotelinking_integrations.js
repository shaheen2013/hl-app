var showIntegrationLoader = () => {
    $("#integrationForm").hide();
    $("#integrationConfigForm").hide();
    $("#integrationEditConfigForm").hide();
    $("#integrationLoader").fadeIn();
}

var showIntegrationConfigForm = () => {
    $("#integrationForm").hide();
    $("#integrationLoader").hide();
    $("#integrationEditConfigForm").hide();
    $("#integrationConfigForm").fadeIn();
}

var showIntegrationEditConfigForm = () => {
    $("#integrationForm").hide();
    $("#integrationConfigForm").hide();
    $("#integrationLoader").hide();
    $("#integrationEditConfigForm").fadeIn();
}

var showIntegrationForm = () => {
    $("#integrationLoader").hide();
    $("#integrationConfigForm").hide();
    $("#integrationEditConfigForm").hide();
    $("#integrationForm").fadeIn();
}

// Function to get the schema from integrations
var getIntegrationConfigSchema = (integration_type) => {
    return (status) => {
        showIntegrationLoader();

        // Declare variables
        let integration_id = $('#' + integration_type + '_integration_id').find(":selected").val();
        let integration_config_id = $('#' + integration_type + '_integration_config_id').val();

        if (status && status == 'new') {
            if (integration_id) {
                $.ajax({
                    url: `${hotelinking_integrations_url}integration/${integration_id}`,
                    headers: {
                        'Authorization': hotelinking_integrations_token,
                        'Content-Type': "application/json",
                    },
                    type: 'GET',
                    success: function (response, status, xhr) {
                        $('#' + integration_type + '_config_form').html('');
                        $('#integration_config_new_button').prop('disabled', false);
                        if (response) {
                            integration_schema = JSON.parse(response.data.config_json_schema);
                            if (integration_schema) {
                                $('#' + integration_type + '_config_form').jsonForm({
                                    schema: integration_schema.schema,
                                    onSubmitValid: function (values) {
                                        // Callback function called upon form submission when values are valid
                                        createIntegrationConfig(integration_type)(values)
                                    }
                                });
                                $('#' + integration_type + '_config_form').children('div').children('div').append('<a class="btn btn-danger pull-left" onclick="showIntegrationForm()">Cancel</a>');
                                $('#' + integration_type + '_config_form').children('div').children('div').children('input[type=submit]').addClass("pull-right");
                            } else {
                                $('#' + integration_type + '_config_form').html(`
                                    <p>Integration with no schema</p>
                                    <br>
                                    <a class="btn btn-danger pull-left" onclick="showIntegrationForm()">Cancel</a>`);
                            }

                            showIntegrationConfigForm();
                        } else {
                            console.error("No response");
                            $('#' + integration_type + '_config_form').html('');
                            integration_schema = null;
                        }
                    },
                    error: function (jqxhr, textStatus, errorThrown) {
                        // alert('Some server error, we are working on it!');
                        console.error(textStatus, errorThrown)
                    }
                });
            }
        } else if (status && status == 'edit') {
            // Get values and print form.
            $.ajax({
                url: `${hotelinking_integrations_url}integration/${integration_id}/config/${integration_config_id}`,
                headers: {
                    'Authorization': hotelinking_integrations_token,
                    'Content-Type': "application/json",
                },
                type: 'GET',
                success: function (response, status, xhr) {
                    $('#' + integration_type + '_config_edit_form').html('');

                    $('#integration_config_edit_button').prop('disabled', false);
                    if (response) {
                        integration_schema = JSON.parse(response.data.json_schema);
                        if (integration_schema) {
                            $('#' + integration_type + '_config_edit_form').jsonForm({
                                schema: integration_schema.schema,
                                value: integration_schema.value,
                                onSubmitValid: function (values) {
                                    // Callback function called upon form submission when values are valid
                                    updateIntegrationConfig(integration_id, integration_config_id, values);
                                }
                            });
                            $('#' + integration_type + '_config_edit_form').children('div').children('div').append('<a class="btn btn-danger pull-left" onclick="showIntegrationForm()">Cancel</a>');
                            $('#' + integration_type + '_config_edit_form').children('div').children('div').children('input[type=submit]').addClass("pull-right");
                        } else {
                            $('#' + integration_type + '_config_edit_form').html(`
                                <p>Integration with no schema</p>
                                <br>
                                <a class="btn btn-danger pull-left" onclick="showIntegrationForm()">Cancel</a>`);
                        }
                        showIntegrationEditConfigForm();
                    } else {
                        console.error("No response");
                        $('#' + integration_type + '_config_edit_form').html('');
                        integration_schema = null;
                    }
                },
                error: function (jqxhr, textStatus, errorThrown) {
                    // alert('Some server error, we are working on it!');
                    console.error(textStatus, errorThrown)
                }
            });

        };
    }
}

// Function to create a integration config
var createIntegrationConfig = (integration_type) => {
    return (integration_config_data) => {
        let integration_id = $('#' + integration_type + '_integration_id').find(":selected").val();
        if (integration_id) {
            $.ajax({
                url: `${hotelinking_integrations_url}integration/${integration_id}/config`,
                headers: {
                    'Authorization': hotelinking_integrations_token,
                    'Content-Type': "application/json",
                },
                type: 'POST',
                data: JSON.stringify(integration_config_data),
                success: function (response, status, xhr) {
                    if (response) {
                        if (!response.errors) {
                            let integration_config = response.data;
                            integrationConfigsList[integration_config.integration_name][integration_config.id] = integration_config.name
                            updateIntegrationConfigList(integration_type);
                        } else {
                            console.error("Errors in response", response);
                        }
                    } else {
                        console.error("No response");
                    }
                },
                error: function (jqxhr, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown)
                }
            });
        } else {
            console.error('No integration id to create the config');
        }
    }
}


// Function to edit an integration config
var updateIntegrationConfig = (integration_id, integration_config_id, data) => {
    showIntegrationLoader();

    if (integration_id) {
        $.ajax({
            url: `${hotelinking_integrations_url}integration/${integration_id}/config/${integration_config_id}`,
            headers: {
                'Authorization': hotelinking_integrations_token,
                'Content-Type': "application/json",
            },
            type: 'PUT',
            data: JSON.stringify(data),
            success: function (response, status, xhr) {
                if (response) {
                    if (!response.errors) {
                        // Reload page to update info.
                        location.reload();
                    } else {
                        console.error("Errors in response", response);
                    }
                } else {
                    console.error("No response");
                }
            },
            error: function (jqxhr, textStatus, errorThrown) {
                console.error(textStatus, errorThrown)
            }
        });
    } else {
        console.error('No integration id to create the config');
    }

}

// Update the integration config list in the select
var updateIntegrationConfigList = (integration_type) => {
    showIntegrationLoader();

    var integration_name = $('#' + integration_type + '_integration_id').find(":selected").text();
    var configs = integrationConfigsList[integration_name];

    var html_configs = Object.keys(configs).map(key => {
        return '<option value="' + key + '">' + configs[key] + '</option>';
    }).join(['']);

    $('#' + integration_type + '_integration_config_id').html(html_configs);

    showIntegrationForm();
}

// Validate form integrations
var validateIntegrationForm = (integration_type) => {
    return (event) => {
        if ($('#' + integration_type + '_integration_id').val() && $('#' + integration_type + '_integration_config_id').val() && $("#integration_connection_hotel_code").val()) {
            return true;
        } else {
            alert("Its necessary all values");
            event.preventDefault();
            return false;
        }
    }
}

// Render form for the active tab.
var renderConfigForm = (config, integration_type, product_id, brand_id) => {
    var integration_config_id = null;
    var integrationsData = datamatch_integrations_configs;

    // Fetch the integration id
    var integration_name = integrations[config.integration_config_id];
    var integration_list_options = "";

    var integrationList = [];
    Object.keys(integrationsData).forEach(type => {
        if (type != 'brands') {
            integrationList.push(integrationsData[type]?.list);
        }
    })

    integrationList.forEach(type => {
        Object.keys(type).forEach(key => {
            if (type[key].toLowerCase() === integration_name.toLowerCase()) {
                $('#' + integration_type + '_integration_id').val(key);
                integration_config_id = key;
            }
            // Mount list options
            if (key == integration_config_id) {
                integration_list_options += ('<option value="' + key + '" selected>' + type[key] + '</option>');
            } else {
                integration_list_options += ('<option value="' + key + '">' + type[key] + '</option>');
            }
        })
    });

    // Clean form content
    $("#tab-content-" + integration_type).html('<div class="row"><div class="col-sm-12 text-center"><div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div></div></div>');

    // Load form
    $("#tab-content-" + integration_type).html(
        '<div id="config' + config.id + '" class="tab-pane fade in active">' +
        '<h3>Integration Config ' + config.id + ' - ' + integration_name + '</h3>' +
        '</div>' +
        '<div class="row" id="integrationLoader">' +
        '<div class="col-sm-12 text-center">' +
        '<div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div>' +
        '</div>' +
        '</div>' +
        '<div class="row" id="integrationConfigForm">' +
        '<div class="col-sm-12">' +
        '<form id="' + integration_type + '_config_form"></form>' +
        '</div>' +
        '</div>' +
        '<div class="row" id="integrationEditConfigForm">' +
        '<div class="col-sm-12">' +
        '<form id="' + integration_type + '_config_edit_form"></form>' +
        '</div>' +
        '</div>' +
        '<div class="row" id="integrationForm">' +
        '<div class="col-sm-12">' +
        '<form method="POST" onsubmit="validateIntegrationForm(\'' + integration_type + '\')(event)">' +
        '<div class="row">' +
        '<div class="col-sm-12 ">' +
        '<label for="integration_id">Integration name:</label>' +
        '<select class="form-control" id="' + integration_type + '_integration_id" name="integration_id" onchange="updateIntegrationConfigList(\'' + integration_type + '\')">' +
        integration_list_options +
        '</select>' +
        '</div>' +
        '<div class="col-sm-9">' +
        '<label for="integration_config_id">Config name:</label>' +
        '<select class="form-control" id="' + integration_type + '_integration_config_id" name="integration_config_id">' +
        '</select>' +
        '</div>' +
        '<div class="col-sm-1">' +
        '<button id="integration_config_new_button" class="btn btn-success" type="button" style="margin-top:20px" onclick="getIntegrationConfigSchema(\'' + integration_type + '\')(\'new\');">New</button>' +
        '</div>' +
        '<div class="col-sm-1">' +
        '<button id="integration_config_edit_button" class="btn btn-warning" type="button" style="margin-top:20px" onclick="getIntegrationConfigSchema(\'' + integration_type + '\')(\'edit\');">Edit</button>' +
        '</div>' +
        '<div class="col-sm-12" id="integration_key_div">' +
        '<label for="integration_key">Key Value:</label>' +
        '<input type="text" class="form-control" id="integration_key" name="integration_key" >' +
        '</div>' +
        '<div class="col-sm-12" id="integration_secret_div">' +
        '<label for="integration_secret">Secret value:</label>' +
        '<input type="text" class="form-control" id="integration_secret" name="integration_secret" >' +
        '</div>' +
        '<div class="col-sm-12" id="integration_parser_hotel_code_div">' +
        '<label for="integration_parser_hotel_code">Hotel name in response:</label>' +
        '<input type="text" class="form-control" id="integration_parser_hotel_code" name="integration_parser_hotel_code" >' +
        '</div>' +
        '<div class="col-sm-12" id="integration_connection_hotel_code_div">' +
        '<label for="integration_connection_hotel_code">Hotel code to connection:</label>' +
        '<input type="text" class="form-control" id="integration_connection_hotel_code" name="integration_connection_hotel_code" >' +
        '</div>' +
        '<div class="col-sm-12 hidden" id="integration_hotel_codes_div">' +
        '<label for="integration_hotel_codes">Hotel codes (Connection & Parser):</label>' +
        '<input type="text" class="form-control" id="integration_hotel_codes" name="integration_hotel_codes" oninput="updateParserConnectionCode()">' +
        '</div>' +
        '<div class="col-sm-12">' +
        '<div class="form-group">' +
        '<input type="checkbox"' +
        'id="integration_activation_checkbox"' +
        'name="integration_activation_checkbox"' +
        'onclick="$(\'#integration_activated\').val($(this).is(\':checked\')?\'1\':\'0\');"' +
        '>' +
        '<label for="integration_activation_checkbox" style="margin-left:2px;">Activated</label>' +
        '<input type="checkbox"' +
        'id="integration_export_checkbox"' +
        'name="integration_export_checkbox"' +
        'onclick="$(\'#integration_export\').val($(this).is(\':checked\')?\'1\':\'0\');"' +
        'style="margin-left:10px;"' +
        '>' +
        '<label for="integration_export_checkbox" style="margin-left:2px;">Export</label>' +
        '<input type="checkbox"' +
        'id="integration_import_checkbox"' +
        'name="integration_import_checkbox"' +
        'onclick="$(\'#integration_import\').val($(this).is(\':checked\')?\'1\':\'0\');"' +
        'style="margin-left:10px;"' +
        '>' +
        '<label for="integration_import_checkbox" style="margin-left:2px;">Import</label>' +
        '</div>' +
        '</div>' +
        '<input type="hidden" name="integration_type" value="' + integration_type + '">' +
        '<input type="hidden" id="integration_brand_integration_id" name="integration_brand_integration_id">' +
        '<input type="hidden" id="integration_brand_id" name="integration_brand_id">' +
        '<input type="hidden" id="integration_activated" name="integration_activated">' +
        '<input type="hidden" id="integration_export" name="integration_export">' +
        '<input type="hidden" id="integration_import" name="integration_import">' +
        (integration_type == 'redirect' && product_id ? '<input type="hidden" id="portalRedirect_product_id" name="portalRedirect_product_id" value="' + product_id + '">' : '') +
        (integration_type == 'redirect'  && product_id ? '<input type="hidden" id="portalRedirect_brand_id" name="portalRedirect_brand_id" value="' + brand_id + '">' : '') +
        '</div>' +
        '<button id="integration_delete_button" type="submit" class="btn btn-warning pull-right" name="action" value="delete" onclick="return confirm(\'Are you sure you want to remove this integration? (this action is irreversible, and you will delete this configuration from every brand that is using it)\')">Delete this configuration</button>' +
        '<button id="integration_submit_button" type="submit" class="btn btn-success pull-right" name="action" value="update">Save this configuration</button>' +
        '</form>' +
        '</div>' +
        '</div>'
    );

    $('#integration_brand_integration_id').val(config.id);
    $('#integration_brand_id').val(config.brand_id);
    $('#' + integration_type + '_integration_config_id').val(config.integration_config_id);
    $('#integration_key').val(config.key);
    $('#integration_secret').val(config.secret);
    $('#integration_parser_hotel_code').val(config.parser_hotel_code);
    $('#integration_connection_hotel_code').val(config.connection_hotel_code);
    $('#integration_activated').val(config.activated);
    $('#integration_activation_checkbox').prop("checked", config.activated == '1' ? true : false);
    $('#integration_export').val(config.export);
    $('#integration_export_checkbox').prop("checked", config.export == '1' ? true : false);
    $('#integration_import').val(config.import);
    $('#integration_import_checkbox').prop("checked", config.import == '1' ? true : false);

    // Update integrations config list
    updateIntegrationConfigList(integration_type);
    // Fetch the integration id
    var integration_name = integrations[config.integration_config_id];

    // Set config id checked.
    $('#' + integration_type + '_integration_config_id').val(config.integration_config_id);
};

// Render form for the new tab.
var renderNewConfigForm = (brand_id, integration_type, product_id) => {
    var integrationsData = datamatch_integrations_configs;
    var integration_list_options = "";
    var integrationList = [];
    Object.keys(integrationsData).forEach(type => {
        if (type != 'brands') {
            integrationList.push(integrationsData[type].list);
        }
    })

    integrationList.forEach(type => {
        Object.keys(type).forEach(key => {
            integration_list_options += ('<option value="' + key + '">' + type[key] + '</option>');
        })
    });

    // Clean form content
    $("#tab-content-pms").html('<div class="row"><div class="col-sm-12 text-center"><div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div></div></div>');
    $("#tab-content-redirect").html('<div class="row"><div class="col-sm-12 text-center"><div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div></div></div>');
    $("#tab-content-" + integration_type).html("");

    // Create new form
    $("#tab-content-" + integration_type).html(
        '<div id="newconfig" class="tab-pane fade in active">' +
        '<h3>New Integration Config</h3>' +
        '</div>' +
        '<div class="row" id="integrationLoader">' +
        '<div class="col-sm-12 text-center">' +
        '<div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div>' +
        '</div>' +
        '</div>' +
        '<div class="row" id="integrationConfigForm">' +
        '<div class="col-sm-12">' +
        '<form id="' + integration_type + '_config_form"></form>' +
        '</div>' +
        '</div>' +
        '<div class="row" id="integrationEditConfigForm">' +
        '<div class="col-sm-12">' +
        '<form id="' + integration_type + '_config_edit_form"></form>' +
        '</div>' +
        '</div>' +
        '<div class="row" id="integrationForm">' +
        '<div class="col-sm-12">' +
        '<form method="POST" onsubmit="validateIntegrationForm(\'' + integration_type + '\')(event)">' +
        '<div class="row">' +
        '<div class="col-sm-12 ">' +
        '<label for="integration_id">Integration name:</label>' +
        '<select class="form-control" id="' + integration_type + '_integration_id" name="integration_id" onchange="updateIntegrationConfigList(\'' + integration_type + '\')">' +
        integration_list_options +
        '</select>' +
        '</div>' +
        '<div class="col-sm-10">' +
        '<label for="integration_config_id">Config name:</label>' +
        '<select class="form-control" id="' + integration_type + '_integration_config_id" name="integration_config_id">' +
        '</select>' +
        '</div>' +
        '<div class="col-sm-2">' +
        '<button id="integration_config_new_button" class="btn btn-success" type="button" style="margin-top:20px" onclick="getIntegrationConfigSchema(\'' + integration_type + '\')(\'new\');">New</button>' +
        '</div>' +
        '<div class="col-sm-12">' +
        '<label for="integration_parser_hotel_code">Hotel name in response:</label>' +
        '<input type="text" class="form-control" id="integration_parser_hotel_code" name="integration_parser_hotel_code" >' +
        '</div>' +
        '<div class="col-sm-12">' +
        '<label for="integration_connection_hotel_code">Hotel code to connection:</label>' +
        '<input type="text" class="form-control" id="integration_connection_hotel_code" name="integration_connection_hotel_code" >' +
        '</div>' +
        '<div class="col-sm-12">' +
        '<div class="form-group">' +
        '<input type="checkbox"' +
        'id="integration_activation_checkbox"' +
        'name="integration_activation_checkbox"' +
        'onclick="$(\'#integration_activated\').val($(this).is(\':checked\')?\'1\':\'0\');"' +
        '>' +
        '<label for="integration_activation_checkbox" style="margin-left:2px;">Activated</label>' +
        '<input type="checkbox"' +
        'id="integration_export_checkbox"' +
        'name="integration_export_checkbox"' +
        'onclick="$(\'#integration_export\').val($(this).is(\':checked\')?\'1\':\'0\');"' +
        'style="margin-left:10px;"' +
        '>' +
        '<label for="integration_export_checkbox" style="margin-left:2px;">Export</label>' +
        '<input type="checkbox"' +
        'id="integration_import_checkbox"' +
        'name="integration_import_checkbox"' +
        'onclick="$(\'#integration_import\').val($(this).is(\':checked\')?\'1\':\'0\');"' +
        'style="margin-left:10px;"' +
        '>' +
        '<label for="integration_import_checkbox" style="margin-left:2px;">Import</label>' +
        '</div>' +
        '</div>' +
        '<input type="hidden" name="integration_type" value="' + integration_type + '">' +
        '<input type="hidden" id="integration_brand_integration_id" name="integration_brand_integration_id">' +
        '<input type="hidden" id="integration_brand_id" name="integration_brand_id">' +
        '<input type="hidden" id="integration_activated" name="integration_activated" value="0">' +
        '<input type="hidden" id="integration_export" name="integration_export">' +
        '<input type="hidden" id="integration_import" name="integration_import">' +
        (integration_type == 'redirect' && product_id ? '<input type="hidden" id="portalRedirect_product_id" name="portalRedirect_product_id" value="' + product_id + '">' : '') +
        (integration_type == 'redirect'  && product_id ? '<input type="hidden" id="portalRedirect_brand_id" name="portalRedirect_brand_id" value="' + brand_id + '">' : '') +
        '</div>' +
        '<button id="integration_submit_button" type="submit" class="btn btn-success pull-right">Save this configuration</button>' +
        '</form>' +
        '</div>' +
        '</div>'
    );

    $('#integration_brand_id').val(brand_id);

    // Update integrations config list
    updateIntegrationConfigList(integration_type);
};

// Check datamatch config before activate the product
var onClickDatamatch = (value, target, hotel_id, brand_id, activated, product_id) => {
    if (value == 1) {
        sendProductActivationRequest(hotel_id, product_id, brand_id, "datamatch", value)
    } else {
        $.ajax({
            url: '/lib/webservices/datamatch-ws.php',
            data: {
                'action': 'datamatch_config',
                'brand_id': brand_id
            },
            type: 'POST',
            success: function (response) {
                var response_array = JSON.parse(response);
                if (response_array != null) {
                    sendProductActivationRequest(hotel_id, product_id, brand_id, "datamatch", value)
                } else {
                    target.prop("checked", false);
                    alert('Its necessary create a integration before activate the datamatch!');
                }
            }
        });
    }
}

// Update integration by integration_type
var onClickIntegrationByType = (integration_type) => {
    // Update integrationBrand with activate if is posible
    return (target, hotel_id, brand_id, integration_brand_id, activated, hasPortalPro) => {
        // Check if has a integration
        if (integration_brand_id) {
            let activatedData = {
                "activated": !activated
            };

            // If portal pro is enabled, prevent disable integration
            if (activated) {
                if (activated && hasPortalPro) {
                    alert("Portal Pro is active, please disable it to continue");
                    return false;
                }
                alert("Please disable all integrations to continue");
                return false;
            }

            // Send request to update the integration
            $.ajax({
                url: `${hotelinking_integrations_url}brand/${brand_id}/integration/${integration_type}/${integration_brand_id}`,
                headers: {
                    'Authorization': hotelinking_integrations_token,
                    'Content-Type': "application/json",
                },
                type: 'PUT',
                data: JSON.stringify(activatedData),
                success: function (response, status, xhr) {
                    if (response) {
                        if (!response.errors) {
                            window.location.assign("private/private-invitar-hotel/?search=" + searchText);
                        } else {
                            target.prop("checked", activated);
                            alert(response.data);
                        }
                    } else {
                        target.prop("checked", activated);
                        alert("No response from integrations");
                    }
                },
                error: function (jqxhr, textStatus, errorThrown) {
                    // alert('Some server error, we are working on it!');
                    target.prop("checked", activated);
                    console.log(textStatus, errorThrown)
                    alert("Error, please report it");
                }
            });
        } else {
            target.prop("checked", activated);
            alert('Create a integration for this hotel before active it!');
        }
    }
}

var checkIntegrationForBrand = (target) => {
    $.ajax({
        url: '/lib/webservices/datamatch-ws.php',
        data: {
            'action': 'datamatch_config',
            'brand_id': target.data('datamatch-brand-id')
        },
        type: 'POST',
        success: function (response) {
            var response_array = JSON.parse(response);
            if (response_array != null) {
                $("#datamatch_frequency_select").fadeIn();
                $('#datamatch_frequency').val(response_array['frequency_id']);
                $('#datamatch_integration_config').html("<p>Integrated with pms: <strong>" + integrations[response_array['integration_config_id']] + "</strong></p>");
                $('#datamatch_submit_button').prop('disabled', false);
            } else {
                $("#datamatch_frequency_select").hide();
                $('#datamatch_frequency').val(null);
                $('#datamatch_integration_config').html("<strong>Don't have any integration yet</strong>");
                $('#datamatch_submit_button').prop('disabled', true);
            }
            $("#datamatchLoader").hide();
            $("#datamatchForm").fadeIn();
        }
    });
}

var checkIntegrationConfigActiveForBrand = (target) => {
    $.ajax({
        url: '/lib/webservices/datamatch-ws.php',
        data: {
            'action': 'list_integrations_config',
            'brand_id': target.data('brand-id'),
            'integration_type': 'pms'
        },
        type: 'POST',
        success: function (response) {
            var listIntegrationConfigs = JSON.parse(response);
            if (listIntegrationConfigs != null && listIntegrationConfigs.length >= 1) {
                if (listIntegrationConfigs[0].activated == 1) {
                    // Enable Portal Pro
                    $('#portalPro_submit_button').prop('disabled', false);
                } else {
                    // PortalPro restrictions
                    $('#portalPro_submit_button').prop('disabled', true);
                    $('#portalProForm').children().append('<p class="text-warning" id="portalpro-error-config">Warning: Enable Integration Config to continue.</p>');
                }
            } else {
                // PortalPro restrictions
                $('#portalPro_submit_button').prop('disabled', true);
                $('#portalProForm').children().append('<p class="text-danger" id="portalpro-error-config">Error. Integration Config needed to enable Portal Pro</p>');
            }
        }
    });
}

var listIntegrationConfigForBrandByType = (integration_type) => {
    // Load integration config list for a integration type
    return (brand_id, product_id, config_index = 0) => {
        // Show loader
        showIntegrationLoader();
        // Call integration to list configs
        $.ajax({
            url: '/lib/webservices/datamatch-ws.php',
            data: {
                'action': 'list_integrations_config',
                'brand_id': brand_id
            },
            type: 'POST',
            success: function (response) {
                var listIntegrationConfigs = JSON.parse(response);
                if (listIntegrationConfigs != null) {
                    // Clean tabs html.
                    $("#nav-tabs-pms").html("");
                    $("#nav-tabs-redirect").html("");
                    if (listIntegrationConfigs.length >= 1) {
                        Object.keys(listIntegrationConfigs).forEach(key => {
                            $("#nav-tabs-" + integration_type).append(
                                '<li class="' + (config_index == key ? 'active' : '') + '"><a data-toggle="tab" href="#config' + listIntegrationConfigs[key].id + '" onclick="listIntegrationConfigForBrandByType(\'' + integration_type + '\')(' + brand_id + ', ' + product_id + ', ' + key + ')">' + listIntegrationConfigs[key].name + '</a></li>');
                        });

                        // Tab to add extra data.
                        $("#nav-tabs-" + integration_type).append(
                            '<li><a data-toggle="tab" href="#newconfig" onclick="renderNewConfigForm(' + brand_id + ', \'' + integration_type + '\', \'' + product_id + '\')">New Config <i class="fa fa-plus-circle"></i></a></li>'
                        );

                        renderConfigForm(listIntegrationConfigs[config_index], integration_type, product_id, brand_id);
                    } else {
                        // Tab to add extra data.
                        $("#nav-tabs-" + integration_type).append(
                            "<li><a data-toggle='tab'>New Config <i class='fa fa-plus-circle'></i></a></li>"
                        );
                   
                        renderNewConfigForm(brand_id, integration_type, product_id);
                    }
                }
                showIntegrationForm();
            }
        });
    }
}

// Each key pushed in HotelCodes input will be written on connection and parser inputs.
var updateParserConnectionCode = () => {
    // Get the value be writed on.
    var hotelcode = $('#integration_hotel_codes').val();

    // Set the value in the inputs.
    $('#integration_parser_hotel_code').val(hotelcode);
    $('#integration_connection_hotel_code').val(hotelcode);
}
