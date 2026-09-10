<?php include_once LANG . $_SESSION['userLang'] . '/feedback.php'; ?>

<style>
    .integration-validator-user-list {
        width: 100%;
        text-align: center;
        color: black;
        font-weight: bold;
        padding: 1rem;
        border-radius: 2px;
        opacity:1;
        display: inline-block;
        margin-top: 1rem;
        list-style-type: none;
        border: 0.5px solid grey;
    }

    .integration-validator-user-list:hover {
        color: #65c3df;
        box-shadow: 2px 2px 5px grey;
        transition: box-shadow 0.1s ease-in-out;
    }

    .integration-validator-error {
        width: 100%;
        text-align: center;
        background: #ff6b6b;
        color: white;
        font-weight: bold;
        padding: 1rem;
        border-radius: 4px;
        margin-top: 4rem;
        opacity:1;
        display: inline-block;
    }

    .tabs-container {
        border-radius: 6px 4px 0 0;
        border: 1px solid #ddd;
        padding: 0 0 2em 0;
        margin-top: 2em;
    }

    @media screen and ( max-height: 760px ) { 
        .tabs-container {
            margin-top: 0.5em;
        }

        .gdpr_title {
            font-size: 1.3em;
        }

        #gdprModal .gdpr_title {
            padding-top: 7rem;
        }

        #gdprModal .hotel-stay-share-logo {
            width: 70px;
            height: 70px;
            margin-left: -35px;
        }

        .gdpr-intro {
            line-height: 60%;
        }
    }     
</style>

<?php if(!array_get($_SESSION, 'showingEprivacy')) { ?>

<style class="custom-portal-pro">

    #gdprModal .modal-content {
        background: url(<?php echo imageSize('large', $hotelInfo['fotoBg']) ?>);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border: 0px solid black !important;
    }

    #gdprModal .modal-footer {
        background-color: transparent;
        z-index: 999999;
        border-top: 0px;
    }

    strong, p, label {
        color: white;
        z-index: 999999;
    }

    #hotelClientBtn-PMS {
        background-color: #45b7af;
        border-color: #3ea49d;
        color: white;
    }

    .modal-body {
        overflow-y: scroll;
        position: fixed; 
        width: 100%; 
        height: 100%; 
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
    }

    .nav-tabs > li > a {
        color: white;
    }

    .nav-tabs.nav-justified > li {
        display: table-cell;
        width: 1%;
        border-bottom: 1px solid #ddd;
    }

    .nav-tabs.nav-justified > .active{
        background-color: #FFF;
        color: #555555;
        border-radius: 4px;
        border: 1px solid transparent;
    }

    .nav-tabs.nav-justified > li > a {
        border: 0;
        margin: 0
    }

    .nav-tabs.nav-justified > .active > a, 
    .nav-tabs.nav-justified > .active > a:hover, 
    .nav-tabs.nav-justified > .active > a:focus {
        border: 0;
    }

    .nav > li > a {
        padding: 10px 0;
    }

    .nav-tabs > li > a:hover {
        color: #555;
        border-top: 1px solid #ddd;
    }
</style>
<?php } ?>

<div class="integration-user-validator-loader text-center row">
    <br><br>
    <div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div>
</div>
<?php
if (array_get($_SESSION, 'userIsReturning', false)) {
    ?>
<div class="text-center row">
    <p><?php echo $stayWifiRedirect['Welcome!']; ?><strong><?php echo $GLOBALS["welcomeBackMessage"]; ?></strong></p>
</div>
<?php } ?>

<?php
    // Check if exists ssid_type param, then check the type and modify the front
    $ssidType = null;
    if(isset($_GET['ssid_type'])) {
        if($_GET['ssid_type'] == "premium") {
            // Is a PREMIUM network
            $_SESSION['portalPro']['isPmsValidation'] = false;
            $_SESSION['portalPro']['isAccessCodeValidation'] = false;
            
        }
        if($_GET['ssid_type'] == "free") {
            // Is a FREE network
            $_SESSION['portalPro']['isRadiusTicketValidation'] = false;
        }

        $ssidType = $_GET['ssid_type'];
    }

    $hideCodeTab = data_get($portalProductConfig, 'only_hosted_guests') && !array_get($portalProConfig, 'premium_ticket') && !array_get($portalProConfig, 'premium_code')
?>
<div class="integration-user-validator-form row">
    <p class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center" ><?php echo $gdprLang['PMS validator require msg'] ?></p>
    <div class="<?php echo ($_SESSION['portalPro']['isPmsValidation'] ?? false) && !$hideCodeTab ? 'tabs-container' : '' ?> col-xs-12 text-center">
        <!-- Nav tabs -->
        <?php if (($_SESSION['portalPro']['isPmsValidation'] ?? false) && !$hideCodeTab) { ?>
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#normal" aria-controls="normal" role="tab" data-toggle="tab"><?php echo $gdprLang['accommodated tab'] ?></a></li>
                <li role="presentation"><a href="#access_codes" aria-controls="access_codes" role="tab" data-toggle="tab"><?php echo $gdprLang['code tab'] ?></a></li>
            </ul>
        <?php } ?>
        <!-- Tab panes -->
        <div class="tab-content">
            <?php if ($_SESSION['portalPro']['isPmsValidation'] ?? false) { ?>
                <div role="tabpanel" class="tab-pane active" id="normal">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center mt" <?php echo array_get($portalProConfig, 'first_name') || array_get($portalProConfig, 'last_name') ? '' : 'style=display:none'; ?>>
                        <label for="client-first-name" class="mt"><?php echo $gdprLang['PMS validator require first name'] ?></label><br/>
                        <input id="client-first-name" class="form-control">
                    </div>
                    <br <?php echo array_get($portalProConfig, 'first_name') || array_get($portalProConfig, 'last_name') ? '' : 'style=display:none'; ?>>
                    <!-- Show last name -->
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center mt" <?php echo array_get($portalProConfig, 'first_name') || array_get($portalProConfig, 'last_name') ? '' : 'style=display:none'; ?>>
                        <label for="client-last-name" class="mt"><?php echo $gdprLang['PMS validator require surname'] ?></label><br/>
                        <input id="client-last-name" class="form-control">
                    </div>
                    <br <?php echo array_get($portalProConfig, 'first_name') || array_get($portalProConfig, 'last_name') ? '' : 'style=display:none'; ?>>
                    <!-- Show document id -->
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center mt" <?php echo array_get($portalProConfig, 'document_id') ? '' : 'style=display:none'; ?>>
                        <label for="client-document-id" class="mt"><?php echo $gdprLang['PMS validator require document id'] ?></label><br/>
                        <input id="client-document-id" class="form-control" <?php if (isset($_SESSION['user']['card_id'])) { ?> value="<?php echo $_SESSION['user']['card_id']; ?>"  <?php } ?>>
                    </div>
                    <br <?php echo array_get($portalProConfig, 'document_id') ? '' : 'style=display:none'; ?>>
                    <!-- Show room number -->
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center mt" >
                        <label for="room-number" class="mt"><?php echo $gdprLang['PMS validator require room'] ?></label><br/>
                        <input id="room-number" class="form-control">
                    </div>
                    <!-- Show radius ticket -->
                    <?php if(data_get($portalProductConfig, 'show_radius_ticket_to_hosted') && array_get($_SESSION, 'portalPro.isRadiusTicketValidation')): ?>
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center mt" >
                            <label for="radius_ticket" class="mt"><?php echo $gdprLang['PMS validator radius ticket'] ?></label><br/>
                            <input id="radius_ticket" class="form-control">
                        </div>
                        <p class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center mt" ><?php echo $gdprLang['PMS validator radiusTicketMsg'] ?></p>
                    <?php endif; ?>
                </div>
            <?php } ?>
            <div role="tabpanel" class="tab-pane" id="access_codes">
                <!-- Show an unique input for access code/premium code and radius tickets -->
                <div id="access_code_div" class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center mt" >
                    <label for="access_code" class="mt"><?php echo $gdprLang['PMS validator access code']; ?></label><br/>
                    <input id="access_code" class="form-control">
                </div>
            </div>

            <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 mt30">
                <button 
                    id="hotelClientBtn-PMS" 
                    onclick="callToPortalProValidator()"
                    class="btn btn-success btn-block"
                >
                    <?php echo $gdprLang['confirm'] ?>
                </button>
            </div>
        </div>
    </div>

    <div>
        <div class="text-center">
            <p id="error-Pms-validator" class="integration-validator-error"></p>
        </div>
    </div>
</div>

<div class="integration-user-validator-list text-center row"></div>


<script>
    var data_select = null;
    var restrictivePortal = <?php echo $_SESSION['gdpr_restrictive'] ? 1 : 0 ?>;
    var portalProConfig = <?php echo json_encode($portalProConfig ?? []) ?>

   function validatePortalProEntry(formValues) {
        // Initialize with true
        var validated = true;
        // In case that is a access_code
        if (formValues['access_code'] && formValues['access_code'].trim() !== '') {
            return true;
        } else {
            delete formValues['access_code'];
        }

        // In case that is a radius_ticket
        <?php if(!(data_get($portalProductConfig, 'show_radius_ticket_to_hosted') && array_get($_SESSION, 'portalPro.isRadiusTicketValidation'))): ?>
            delete formValues['radius_ticket'];
        <?php endif; ?>

        // Otherwise, validate the config
        $.each(formValues, function(i, val) {
            // In case that one is not valid - check for empty, null, undefined, or whitespace-only strings
            var trimmedVal = val ? val.toString().trim() : '';
            if (trimmedVal === '' && portalProConfig[i] == '1') {
                validated = false;
            }
        })
        // Return result
        return validated;
    }

    $(document).ready(function() {
        // If it is a premium network we only show the ticket input and hide the tabs.
        <?php if (!$_SESSION['portalPro']['isPmsValidation'] ?? false) { ?>
            $('#access_codes').show();
        <?php } ?>

        // Check for mikrotik parameters and auto-populate form
        var mikrotikFirstName = '<?php echo array_get($_SESSION, 'first_name', ''); ?>';
        var mikrotikLastName = '<?php echo array_get($_SESSION, 'last_name', ''); ?>';
        var mikrotikRoomNumber = '<?php echo array_get($_SESSION, 'room_number', ''); ?>';

        // Auto-populate form if mikrotik parameters are present
        if (mikrotikFirstName || mikrotikLastName || mikrotikRoomNumber) {
            if (mikrotikFirstName) {
                $('#client-first-name').val(mikrotikFirstName);
            }
            if (mikrotikLastName) {
                $('#client-last-name').val(mikrotikLastName);
            }
            if (mikrotikRoomNumber) {
                $('#room-number').val(mikrotikRoomNumber);
            }

            // Check if all required fields are populated and enable button (but don't auto-submit)
            setTimeout(function() {
                var room_number = $('#room-number').val() ? $('#room-number').val().trim() : '';                
                var client_first_name = $('#client-first-name').val() ? $('#client-first-name').val().trim() : '';
                var client_last_name = $('#client-last-name').val() ? $('#client-last-name').val().trim() : '';
                var client_document_id = $('#client-document-id').val() ? $('#client-document-id').val().replace(/\s/g, '') : '';
                var access_code = $('#access_code').val() ? $('#access_code').val().trim() : '';
                var radius_ticket = $('#radius_ticket').val() ? $('#radius_ticket').val().trim() : '';

                // Validate portalPro inputs and enable button (but don't auto-submit)
                if (validatePortalProEntry({
                    "room_number": room_number,
                    "first_name": client_first_name,
                    "last_name": client_last_name,
                    "document_id": client_document_id,
                    "access_code": access_code,
                    "radius_ticket": radius_ticket
                })) {
                    $('#hotelClientBtn-PMS').prop("disabled", false);
                    callToPortalProValidator();
                }
            }, 500); // Small delay to ensure form is fully loaded
        }

        // Parsear pms_user to javascript object
        var pms_user_in_session = '<?php echo(array_has($_SESSION, 'pms_user') ? json_encode(array_get($_SESSION, 'pms_user')) : null); ?>';

        if (pms_user_in_session){
            $('.integration-user-validator-loader').hide();
            $('.integration-user-validator-list').hide();
            $('.integration-user-validator-form').hide();
        } else {
            resetIntegrationUserValidatorSettings(0)

            $('#error-Pms-validator').hide();
            $('#hotelClientBtn-PMS').prop("disabled", true);

            $('input').on('keyup', function() {
                var room_number = $('#room-number').val() ? $('#room-number').val().trim() : '';                
                var client_first_name = $('#client-first-name').val() ? $('#client-first-name').val().trim() : '';
                var client_last_name = $('#client-last-name').val() ? $('#client-last-name').val().trim() : '';
                var client_document_id = $('#client-document-id').val() ? $('#client-document-id').val().replace(/\s/g, '') : '';
                var access_code = $('#access_code').val() ? $('#access_code').val().trim() : '';
                var radius_ticket = $('#radius_ticket').val() ? $('#radius_ticket').val().trim() : '';

                // Validate portalPro inputs
                if (validatePortalProEntry({
                    "room_number": room_number,
                    "first_name": client_first_name,
                    "last_name": client_last_name,
                    "document_id": client_document_id,
                    "access_code": access_code,
                    "radius_ticket": radius_ticket
                })) {
                    $('#hotelClientBtn-PMS').prop("disabled", false);
                } else {
                    $('#hotelClientBtn-PMS').prop("disabled", true);
                }
            });
        }
    });

    function callToPortalProValidator() {
        $('#hotelClientBtn-PMS').prop("disabled", true);
        showLoading()

        var brand_id = <?php echo $brand_id ?>;
        var room_number = $('#room-number').val();
        var client_first_name = $('#client-first-name').val();
        var client_last_name = $('#client-last-name').val();
        var client_document_id = $('#client-document-id').val();
        var access_code = $('#access_code').val();
        var radius_ticket = $('#radius_ticket').val();
        var max_validations = <?php echo array_get($portalProConfig, 'max_validations', 3) ?>;
        var radius_ticket_to_hosted = <?php echo data_get($portalProductConfig, 'show_radius_ticket_to_hosted') == 1 ? 1 : 0 ?>;
        var ssid_type = "<?php echo $ssidType ?>" ?? null;


        if((room_number != "" && room_number !== null) || (access_code != "" && access_code !== null) || (radius_ticket != "" && radius_ticket !== null)){

            $.ajax({
                url: '/lib/webservices/portal-pro-ws.php',
                data: {
                    'action': "validate-room",
                    'brand_id': brand_id,
                    'room_number': room_number,
                    'first_name': client_first_name,
                    'last_name': client_last_name,
                    'document_id': client_document_id,
                    'access_code': access_code,
                    'radius_ticket': radius_ticket,
                    'max_validations': max_validations,
                    'radius_ticket_to_hosted': radius_ticket_to_hosted,
                    'ssid_type': ssid_type
                },
                type: 'POST',
                success: function(response) {
                    var response_array = JSON.parse(response);
                    var validated = Object.keys(response_array).indexOf('validated') > -1 ? response_array['validated'] : false;
                    var pmsData = Object.keys(response_array).indexOf('pms_data') > -1 ? response_array['pms_data'] : [];
                    var accessCode = Object.keys(response_array).indexOf('access_code') > -1 ? response_array['access_code'] : null;
                    var premiumCode = Object.keys(response_array).indexOf('premium_code') > -1 ? response_array['premium_code'] : null;
                    var premiumTicket = Object.keys(response_array).indexOf('premium_ticket') > -1 ? response_array['premium_ticket'] : null;
                    var radiusTicketValidated = Object.keys(response_array).indexOf('radius_ticket_validated') > -1 ? response_array['radius_ticket_validated'] : false;
                    
                    if (radius_ticket && radiusTicketValidated) {
                        $('#radius_ticket').attr('disabled', 'true')
                    }
                    
                    // Check if it was validated
                    if (response_array && validated == true){
                        // In case of premium code or ticket, we give internet
                        if (premiumCode == "1" || premiumTicket == "1") {
                            // publish the wifi-redirect event for the wifi integration to authenticate the user with the router
                            HLevents.publish('wifi-redirect');
                        } else {
                            // If accepted Gdpr do bypass
                            <?php if ($_SESSION['acceptedGDPR']) { ?>
                                    acceptedConditions();
                                    $('.gdprButtons').hide();
                                    $('.gdpr_intro').hide();
                            <?php } else { ?>
                                //user validated with access code, so not client
                                if (accessCode){
                                    gdpr_events.push({'event': 'not_client', 'created_at': now()});
                                    showGdprSection('notHotelClient');
                                } else {
                                    gdpr_events.push({'event': 'client', 'created_at': now()});
                                    showGdprSection('hotelClient');
                                }
                            <?php } ?>
                            
                            if (!restrictivePortal){
                                gdpr_events.push({'event': 'notifications', 'created_at': now()});
                            }

                            $('#error-Pms-validator').css('opacity', '0');

                            // Populate inputs with clients values
                            if (pmsData && pmsData.length){
                                populatePmsInputs(pmsData[0]);
                            }

                            // Reset state
                            resetIntegrationUserValidatorSettings(500);
                        }

                    } else if (response_array && validated == 'select'){
                        data_select = pmsData;

                        $('.integration-user-validator-list').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center mt"><ul>');
                        var toAppend = '';
                        pmsData.forEach(function(pms_user, i){
                            birthdayUser = new Date(pms_user['birthday']);
                            toAppend = '<li class="integration-validator-user-list" onClick="selectedValidateUser(' + i + ')">';
                            toAppend = toAppend + '<p>Nombre: ' + pms_user['first_name'] + ' ' + pms_user['last_name'] + '</p>';
                            toAppend = toAppend + '<p>Cumpleaños: ' + birthdayUser.toLocaleDateString() + '</p></li>'
                            $('.integration-user-validator-list').append(toAppend);
                        });
                        $('.integration-user-validator-list').append('</ul></div>');

                        hideLoadingAndShow('list')
                    } else if (response_array && validated == 'showNormalPortal'){
                        location.reload();
                    } else if (response_array && validated == 'invalidRoomNumber'){
                        showIntUserValidatorError(4100)
                        hideLoadingAndShow('form')

                        // Scroll to the error
                        $('.modal-body').animate({ scrollTop: $('#error-Pms-validator').offset().top }, 'slow');
                    } else {
                        showIntUserValidatorError(4073)
                        hideLoadingAndShow('form')

                        // Scroll to the error
                        $('.modal-body').animate({ scrollTop: $('#error-Pms-validator').offset().top }, 'slow');
                    }
                },
                error: function(error){
                    showIntUserValidatorError(4073)
                    hideLoadingAndShow('form')

                    // Scroll to the error
                    $('.modal-body').animate({ scrollTop: $('#error-Pms-validator').offset().top }, 'slow');
                }
            });
        } else {
            showIntUserValidatorError(4074)
            hideLoadingAndShow('form')

            // Scroll to the error
            $('.modal-body').animate({ scrollTop: $('#error-Pms-validator').offset().top }, 'slow');
        }
    }

    function selectedValidateUser(index){
        showLoading()

        $.ajax({
            url: '/lib/webservices/portal-pro-ws.php',
            data: {
                'action': "select-user",
                'brand_id': <?php echo $brand_id ?>,
                'pms_data': data_select[index]
                },
            type: 'POST',
            success: function(response) {
                var response_array = JSON.parse(response);
                if(response_array['validated'] == true){
                    // Add event gdpr client
                    gdpr_events.push({'event': 'client', 'created_at': now()});
                    if(!restrictivePortal){
                        gdpr_events.push({'event': 'notifications', 'created_at': now()});
                    }
                    showGdprSection('hotelClient');
                    $('#error-Pms-validator').css('opacity', '0');
                    // Populate inputs with clients values
                    populatePmsInputs(data_select[index]);
                    // Reset state
                    resetIntegrationUserValidatorSettings(500);
                } else {
                    showIntUserValidatorError(4073)
                    hideLoadingAndShow('form')
                    // Scroll to the error
                    $('.modal-body').animate({ scrollTop: $('#error-Pms-validator').offset().top }, 'slow');
                }
            }
        });
    }

    // Populate inputs that are needed to autocomplet in stay-share
    function populatePmsInputs(selected_user){
        // Populate inputs with clients values
        $('#PMS-first-name').val(selected_user.first_name);
        $('#PMS-last-name').val(selected_user.last_name);
        $('#PMS-birthday').val(selected_user.birthday);
        $('#PMS-gender').val(selected_user.gender);
        $('#PMS-email').val(selected_user.email);
        $('#PMS-phone').val(selected_user.telephone);
        // If has roomNumber use it
        var roomNumber = $('#room-number').val()
        if (roomNumber && roomNumber != '') {
            $('#PMS-room-number').val($('#room-number').val());
        }
    }

    // Reset to initiate state for when the user goback
    function resetIntegrationUserValidatorSettings(timeout){
        setTimeout(function () {
            hideLoadingAndShow('form')
            var roomNumber = $('#room-number').val()
            if(roomNumber && roomNumber != '' ){
                $('#hotelClientBtn-PMS').prop("disabled",false);
            }else{
                $('#hotelClientBtn-PMS').prop("disabled",true);
            }
            $('.integration-user-validator-list').html('<h3><?php echo $gdprLang['PMS validator title list users'] ?></h3>');
        }, timeout);
    }

    // Show loading
    function showLoading(){
        $('.integration-user-validator-loader').show();
        $('.integration-user-validator-list').hide();
        $('.integration-user-validator-form').hide();
    }

    // Hide loading and show a stage select
    function hideLoadingAndShow(stage){
        $('.integration-user-validator-loader').hide();
        if(stage == 'form'){
            $('.integration-user-validator-list').hide();
            $('.integration-user-validator-form').show();
        }else{
            $('.integration-user-validator-list').show();
            $('.integration-user-validator-form').hide();
        }
    }

    // Show error
    function showIntUserValidatorError(error_num){
        if (error_num == 4073) {
            $('#error-Pms-validator').text("<?php echo $msg4073 ?>");
        } else if (error_num == 4074) {
            $('#error-Pms-validator').text("<?php echo $msg4074 ?>");
        } else if (error_num == 4100) {
            $('#error-Pms-validator').text("<?php echo $msg4100 ?>");
        }
        $('#error-Pms-validator').show();
        $('#error-Pms-validator').css('opacity', '1');
        $('#hotelClientBtn-PMS').prop("disabled", false);
    }

</script>