<?php if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}?>

<?php include_once LANG . $_SESSION['userLang'] . '/gdpr.php'; ?>


<div class="modal" id="gdprModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-content ">
        <div class="modal-body ">
            <div>
                <div class="row">
                <div class="gdpr-intro col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
                    <img src="<?php echo !empty($hotelInfo['logo']) ? imageSize('small', $hotelInfo['logo']) : DIR_IMG . 'img-placeholder.jpg' ?>"
                         alt="Hotel logo" class="img-thumbnail img-circle hotel-stay-share-logo" width="100" height="100">
                    <h3 class="gdpr_title text-center">
                            <!-- <strong><?php echo $gdprLang['intro_title'] ?></strong> -->
                            <strong><?php echo $hotelInfo['hotelName'] ?></strong>
                    </h3>
                    <br>
                </div>
            </div>
            <div class="gdpr_intro">
                <div class="row">
                    <?php if ($_SESSION['showPortalPro']) { ?>
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
                            <?php include TEMPLATES . "portal-pro.php"; ?>
                    <?php } else { ?>
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-4 col-lg-offset-4">
                            <?php echo $gdpr_intro;?>
                            <!-- <p><strong><?php echo $hotelInfo['hotelName'] ?></strong></p> -->
                    <?php } ?>
                        </div>
                </div>
            </div>
            <div class="hotelClient gdprConditions">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-4 col-lg-offset-4">
                        <?php echo $gdpr_outro ?>
                    </div>
                </div>
            </div>
            <div class="notHotelClient gdprConditions">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-4 col-lg-offset-4">
                        <?php echo $gdpr_restrictive_outro ?>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="ripple" ><img src="<?php echo DIR_IMG . 'ripple.svg' ?>" alt="loaded spinner" width="100" height="100"></div>

        <div class="modal-footer">
            <div class="hotelClient gdprConditions row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-4 col-lg-offset-4">
                    <div class="form-group">
                        <?php if ($gdpr_restrictive) { ?>
                            <label class="text-left gdprLabel">
                                <input type="checkbox"
                                    class="acceptGdprNotifications"> <?php echo fillTextWith($gdprLang['checkbox_notifications'], $brandLegalName) ?>
                            </label>
                        <?php } ?>

                        <?php if(data_get($portalProductConfig, 'commercial_profile')): ?>
                            <label class="text-left gdprLabel">
                                <input type="checkbox"
                                    class="acceptCommercialProfile"> <?php echo fillTextWith($gdprLang['commercial_profile'], $brandLegalName) ?>
                            </label>
                        <?php endif; ?>
                    </div>

                    <button class="acceptGdprConditions btn btn-success btn-block">
                        <?php 
                            echo $gdpr_restrictive 
                                ? $gdprLang['intro_accept_restrictive'] 
                                : $gdprLang['intro_accept_conditions']; 
                        ?>
                    </button>               
                    <br>
                    <p <?php if ($brand_is_not_hotel) {
        ?>style="display: none;"<?php
    }?> class="gdprBack text-center"><a><?php echo $gdprLang['go_back'] ?></a></p>
                </div>
            </div>

            <div class="notHotelClient gdprConditions row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-4 col-lg-offset-4">
                    <div class="form-group">
                        <label class="text-left gdprLabel">
                            <input type="checkbox"
                                   class="acceptGdprNotifications"> <?php echo fillTextWith($gdprLang['checkbox_notifications'], $brandLegalName) ?>
                        </label>
                        <?php if(data_get($portalProductConfig, 'commercial_profile')): ?>
                        <label class="text-left gdprLabel">
                            <input type="checkbox"
                                    class="acceptCommercialProfile"> <?php echo fillTextWith($gdprLang['commercial_profile'], $brandLegalName) ?>
                        </label>
                        <?php endif; ?>
                    </div>

                    <button class="acceptGdprConditions btn btn-success btn-block"><?php echo $gdprLang['intro_accept_conditions'] ?></button>
                    <br>
                    <p <?php if ($brand_is_not_hotel) {
        ?> style="display: none;"<?php
    }?> class="gdprBack text-center"><a><?php echo $gdprLang['go_back'] ?></a></p>
                </div>
            </div>

            <div class="gdprButtons row">
                <?php if (!$isPortalPro) { ?>
                    <div class="row text-center">
                        <div class="col-md-12">
                            <strong><?php echo $gdprLang['intro_question'] ?></strong>
                        </div>
                    </div>
                    <br>
                    <?php if (strlen($gdprLang['intro_answer_client']) > 5) { ?>
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-4 col-lg-offset-4">
                            <button 
                                id="hotelClientBtn"
                                class="btn btn-success btn-block"><?php echo $gdprLang['intro_answer_client'] ?>
                            </button>
                            <br>
                            <button 
                                id="notHotelClientBtn"
                                class="btn btn-danger btn-block"><?php echo $gdprLang['intro_answer_not_client'] ?>
                            </button>
                        </div>
                    <?php } else { ?>
                        <div class="text-center col-xs-12 col-sm-8 col-sm-offset-2 col-lg-4 col-lg-offset-4">
                            <button 
                                style="width: 48%"
                                id="notHotelClientBtn"
                                class="btn btn-danger"><?php echo $gdprLang['intro_answer_not_client'] ?>
                            </button>
                            <button 
                                style="margin-left: 0; width: 48%" 
                                id="hotelClientBtn"
                                class="btn btn-success"><?php echo $gdprLang['intro_answer_client'] ?>
                            </button>    
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="privacyModal">
    <div class="header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
    </div>
    <div class="scroll-wrapper">
        <iframe src="" frameborder="0"></iframe>
    </div>

</div>

<form id="gdprForm" method="POST">
    <input type="hidden" name="gdprEvents"/>
    <?php if ($isPortalPro ) { ?>
        <input type="hidden" id="PMS-first-name" name="first-name" value="<?php echo array_has($_SESSION, 'pms_user') ? array_get($_SESSION, 'pms_user.first_name') : '' ?>"/>
        <input type="hidden" id="PMS-last-name" name="last-name" value="<?php echo array_has($_SESSION, 'pms_user') ? array_get($_SESSION, 'pms_user.last_name') : '' ?>"/>
        <input type="hidden" id="PMS-birthday" name="birthday" value="<?php echo array_has($_SESSION, 'pms_user') ? array_get($_SESSION, 'pms_user.birthday') : '' ?>"/>
        <input type="hidden" id="PMS-gender" name="gender" value="<?php echo array_has($_SESSION, 'pms_user') ? array_get($_SESSION, 'pms_user.gender') : '' ?>"/>
        <input type="hidden" id="PMS-room-number" name="room-number" value="<?php echo array_has($_SESSION, 'pms_user') ? array_get($_SESSION, 'pms_user.res_room_number') : '' ?>"/>
        <input type="hidden" id="PMS-email" name="pms_user_email" value="<?php echo array_has($_SESSION, 'pms_user') ? array_get($_SESSION, 'pms_user.email') : '' ?>"/>
        <input type="hidden" id="PMS-phone" name="phone-number" value="<?php echo array_has($_SESSION, 'pms_user') ? array_get($_SESSION, 'pms_user.telephone') : '' ?>"/>
    <?php } ?>
</form>


<?php if ($showGDPR) {
        ?>

    <script>
        'use strict';
        var customCssTag;

        //check if customer has been specified to us from integration
        function customerInSession() {
            return <?php echo array_has($_SESSION, 'customer') ? 'true' : 'false' ?>
        }

        function portalProNotInStayTime() {
            return <?php echo (isset($_SESSION['regularUser']['stayTimeReconnection']) && $_SESSION['regularUser']['stayTimeReconnection'] == 0) ? 'true' : 'false' ?>
        }

        // Function to verify if pms_user is in session
        function pmsUserInSession() {
            return <?php echo(array_get($_SESSION, 'pms_user') ? 'true' : 'false') ?>
        }

        //check if user is already a hotel client from session (netllar)
        function isHotelClient() {
            return <?php echo array_get($_SESSION, 'customer') ? 'true' : 'false' ?>
        }

        //show gdpr intro
        function showGdprIntro() {
            $('.gdprButtons').fadeIn();
            $('.gdpr_intro').fadeIn();
        }

        //show hide intro
        function hideGdprIntro() {
            $('.gdprButtons').fadeOut();
            $('.gdpr_intro').fadeOut();
        }

        //show the corresponding GDPR segment
        function showGdprSection(className, timeout) {

            // On success, we remove and save the background page custom styles to show GDPR correctly
            customCssTag = $('.custom-portal-pro').detach();
            
            if (!timeout) {
                timeout = 500
            }
            hideGdprIntro();
            setTimeout(function () {
                $('.' + className).show();
            }, timeout);
        }

        //slice the iso string datetime to be a valid sql time
        function now()
        {
            return new Date().toISOString().slice(0, 19).replace('T', ' ');
        }


        var gdpr_events = [];


        //callback when user accepts GDPR conditions
        function acceptedConditions() {
            gdpr_events.push({'event': 'conditions', 'created_at': now()})
            //submit form
            sendGDPRForm(gdpr_events);
        }

        //submit the gdprform
        function sendGDPRForm(gdpr_events) {
            //todo check integrity of gdpr_events
            $('[name="gdprEvents"]').val(JSON.stringify(gdpr_events));

            // console.log(gdpr_events);
            $('#gdprForm').submit();

            //show loader
            $(".gdprConditions").fadeOut();
            $("#gdprModal .ripple").addClass("animated dblock bounceIn");
        }

        <?php
            if ($_SESSION['acceptedGDPR']) { ?>
                $(".gdprConditions").fadeOut();
                $('.notHotelClient').fadeOut();
                $('.hotelClient').fadeOut();
        <?php } ?>

        $(document).ready(function () {
            var modal = $('#gdprModal');

            $('.gdprButtons').hide();
            $('.gdpr_intro').hide();

            // If not in stay time, show portal pro
            if (portalProNotInStayTime()) {
                // Print portal pro without GDPR
                $('.notHotelClient').fadeOut();
                $('.hotelClient').fadeOut();
                $('.gdpr_intro').fadeIn();
                $('.gdprButtons').fadeIn();
            } else {
                //show gdpr intro or not
                if (customerInSession()) {
                    if (isHotelClient()) {
                        showGdprSection('hotelClient');
                    } else {
                        showGdprSection('notHotelClient');
                    }
                } else {
                    <?php if (!$brand_is_not_hotel || $isPortalPro) { ?>
                        showGdprIntro();
                    <?php } else { ?>
                        showGdprSection('hotelClient');
                    <?php } ?>
                }
            }

            // Check if that is pms_user in session and show gdpr
            if (pmsUserInSession()) {
                showGdprSection('hotelClient', 0);
            }

            //show the gdpr conditions modal
            modal.modal({
                show: true,
                keyboard: false,
                backdrop: false
            });

            //show the privacy modal when clicked
            $('#gdprModal .privacy').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                showPrivacyTerms();
            })

            //when user clicks on being a hotel client show conditions
            $('#hotelClientBtn').on('click', function () {
                var restrictivePortal = <?php echo $_SESSION['gdpr_restrictive'] ? 1 : 0 ?> ;
                gdpr_events.push({'event': 'client', 'created_at': now()});
                if(!restrictivePortal){
                    gdpr_events.push({'event': 'notifications', 'created_at': now()});
                }
                //hide button and show checkboxes
                showGdprSection('hotelClient');

            });

            //when user is not a hotel client show conditions
            $('#notHotelClientBtn').on('click', function () {
                gdpr_events.push({'event': 'not_client', 'created_at': now()});
                //hide button and show checkboxes
                showGdprSection('notHotelClient');
            });

            //when user clicks on accepts notifications
            $('.acceptGdprNotifications').on('click', function () {
                if ($('.acceptGdprNotifications').is(':checked')) {
                    gdpr_events.push({'event': 'notifications', 'created_at': now()})
                }
            });

            $('.acceptCommercialProfile').on('click', function () {
                if ($('.acceptCommercialProfile').is(':checked')) {
                    gdpr_events.push({'event': 'commercial_profile', 'created_at': now()})
                }
            })

            //if back button is pressed show initial state
            $('.gdprBack').on('click', function () {

                if (customCssTag != null) {
                    $('body').append(customCssTag);
                }

                //only go back if customer is not in session
                if (!customerInSession()) {
                    $('.notHotelClient').fadeOut();
                    $('.hotelClient').fadeOut();
                    setTimeout(function () {
                        $('.gdpr_intro').fadeIn();
                        $('.gdprButtons').fadeIn();
                    }, 500);
                }
            });

            //if user accepts conditions
            $('.acceptGdprConditions').on('click', acceptedConditions);

            //show privacy iframe modal
            function showPrivacyTerms() {
                var src = $("#privacyModal iframe").attr('src')
                if(src != 'privacy'){
                    $("#privacyModal iframe").attr('src', '<?php echo $urlTree['privacy'] ?>');
                }
                $('#privacyModal').modal({show: true, keyboard: true});
            }
        });

    </script>
<?php
    }?>



