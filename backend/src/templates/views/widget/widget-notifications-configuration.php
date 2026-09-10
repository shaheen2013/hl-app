<?php include LANG . $_SESSION['userLang'] . '/statistics/widgetNotifications.php'?>

<?php $this->layout('_layout::layout', [
    'title' => $this->e($title),
    'page_title' => $this->e($page_title),
    'page_icon' => $this->e($page_icon),
    'hotel_name' => $this->e($hotel_name),
    'hotel_logo' => $this->e($hotel_logo),
    'url' => $url
]) ?>
<style>

    .itemBox.active{
        margin-bottom: 1px !important;
        border-bottom: 1px solid #d4d4d5 !important;
        border-radius: .28571429rem .28571429rem !important;
    }

    .itemBox-container{
        border-bottom: 0 !important;
    }

    .huebee {
        z-index: 1000;
    }

    .position-selector-field {
        margin-top: 13px !important;
    }

    .position-selector-field .checkbox {
        margin-right: 10px;
    }

    .red {
        color: red;
    }

    pre {
        white-space: pre-wrap; /* Since CSS 2.1 */
        white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word; /* Internet Explorer 5.5+ */
    }
</style>
<div class="ui four column grid" style="padding:2rem">
    <?php if (($widgetActive || $parentWidgetActive) && array_get($_SESSION, 'permisos.widget')) { ?>
        <?php if($defaultOffer){ ?>
            <div class="ui card">
                <div class="content">
                    <h3 class="inline"><?php echo $lang['newBookingNotification'] ?></h3>
                    <div class="ui toggle checkbox" id="widget_booking_email_active">
                        <input type="hidden" form="widgetBookingEmailForm" name="widget_booking_email_active" value="">
                        <input type="checkbox" form="widgetBookingEmailForm"
                               name="widget_booking_email_active" <?php echo $widget_booking_email_active != null && $widget_booking_email_active == 'on' ? 'checked' : '' ?>>
                        <form id="widgetBookingEmailForm" style="display: none" method="post" class="ui form"></form>
                    </div>
                    <a href="#" class="red" style="float:right" data-toggle="widget-booking-email-notification"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="widget-booking-email-notification" class="content">
                    <div class="row">
                       <p><?php echo $lang['newBookingNotificationText'] ?></p>
                    </div>
                </div>
            </div>
            <div class="ui card">
                <div class="content">
                    <h3 class="inline"><?php echo $lang['nearCheckIn'] ?></h3>
                    <div class="ui toggle checkbox" id="notification_active">
                        <input type="hidden" form="checkInForm" name="notification_active" value="">
                        <input type="checkbox" form="checkInForm"
                               name="notification_active" <?php echo $notification_active != null && $notification_active == 'on'  ? 'checked' : '' ?>>
                        <form id="checkInForm" style="display: none" method="post" class="ui form"></form>
                    </div>
                    <a href="#" class="red" style="float:right" data-toggle="booking-notification"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="booking-notification" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <p><?php echo $lang['nearCheckInDescription']?></p>

                            <form class="ui form" id="widget-checkin-notifications" method="post">
                                <div class="field">
                                    <div class="fields">
                                        <div class="field">
                                            <label><?php echo $lang['daysBefore'] ?></label>
                                            <select class="ui search dropdown" name="days_antelation">
                                                <option value=""><?php echo $lang['select'] ?></option>
                                                <option value="1" <?php echo $days_selected == 1 ? 'selected' : '' ?>>1</option>
                                                <option value="2" <?php echo $days_selected == 2 ? 'selected' : '' ?>>2</option>
                                                <option value="3" <?php echo $days_selected == 3 ? 'selected' : '' ?>>3</option>
                                                <option value="4" <?php echo $days_selected == 4 ? 'selected' : '' ?>>4</option>
                                                <option value="5" <?php echo $days_selected == 5 ? 'selected' : '' ?>>5</option>
                                                <option value="6" <?php echo $days_selected == 6 ? 'selected' : '' ?>>6</option>
                                                <option value="7" <?php echo $days_selected == 7 ? 'selected' : '' ?>>7</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <input type="submit" class="primary-color bg ui button has-loader"
                                        value="Guardar configuración "/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ui card">
                <div class="content">
                    <h3 class="inline"><?php echo $lang['remarketing'] ?></h3>
                    <div class="ui toggle checkbox" id="remarketing_notification_active">
                        <input type="hidden" form="remarketingForm" name="remarketing_notification_active" value="">
                        <input type="checkbox" form="remarketingForm"
                               name="remarketing_notification_active" <?php echo $remarketing_notification_active != null && $remarketing_notification_active == 'on' ? 'checked' : '' ?>>
                        <form id="remarketingForm" style="display: none" method="post" class="ui form"></form>
                    </div>
                    <a href="#" class="red" style="float:right" data-toggle="remarketing-notification"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="remarketing-notification" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <p><?php echo $lang['remarketingDescription'] ?></p>

                            <form class="ui form" id="widget-remarketing-notifications" method="post">
                                <div class="field">
                                    <div class="fields">
                                        <div class="field">
                                            <label><?php echo $lang['numberMails'] ?></label>
                                            <select class="ui search dropdown" name="remarketing_number_emails">
                                                <option value=""><?php echo $lang['select'] ?></option>
                                                <option value="1" <?php echo $remarketing_number_emails == 1 ? 'selected' : '' ?>>1</option>
                                                <option value="2" <?php echo $remarketing_number_emails == 2 ? 'selected' : '' ?>>2</option>
                                                <option value="3" <?php echo $remarketing_number_emails == 3 ? 'selected' : '' ?>>3</option>
                                            </select>

                                        </div>
                                        <div class="field">
                                            <label><?php echo $lang['separateEmails']?></label>
                                            <select class="ui search dropdown" name="remarketing_days_subsequent">
                                                <option value=""><?php echo $lang['select']?></option>
                                                <option value="1" <?php echo $remarketing_days_subsequent == 1 ? 'selected' : '' ?>>1</option>
                                                <option value="2" <?php echo $remarketing_days_subsequent == 2 ? 'selected' : '' ?>>2</option>
                                                <option value="3" <?php echo $remarketing_days_subsequent == 3 ? 'selected' : '' ?>>3</option>
                                                <option value="4" <?php echo $remarketing_days_subsequent == 4 ? 'selected' : '' ?>>4</option>
                                                <option value="5" <?php echo $remarketing_days_subsequent == 5 ? 'selected' : '' ?>>5</option>
                                                <option value="6" <?php echo $remarketing_days_subsequent == 6 ? 'selected' : '' ?>>6</option>
                                                <option value="7" <?php echo $remarketing_days_subsequent == 7 ? 'selected' : '' ?>>7</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label><?php echo $lang['separateHoursEmails'] ?></label>
                                            <select class="ui search dropdown" name="remarketing_first_lapse">
                                                <option value=""><?php echo $lang['select'] ?></option>
                                                <option value="30" <?php echo $remarketingFirstLapse == 30 ? 'selected' : '' ?>>30 minutos</option>
                                                <option value="60" <?php echo $remarketingFirstLapse == 60 ? 'selected' : '' ?>>1 hora</option>
                                                <option value="120" <?php echo $remarketingFirstLapse == 120 ? 'selected' : '' ?>>2 horas</option>
                                                <option value="360" <?php echo $remarketingFirstLapse == 360 ? 'selected' : '' ?>>6 horas</option>
                                                <option value="1440" <?php echo $remarketingFirstLapse == 1440 ? 'selected' : '' ?>>1 día</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <input type="submit" class="primary-color bg ui button has-loader"
                                        value="Guardar configuración "/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ui card">
                <div class="content">
                    <h3 class="inline"><?php echo $lang['present'] ?></h3>
                    <div class="ui toggle checkbox" id="promocode_notification_active">
                        <input type="hidden" form="promocodeForm" name="promocode_notification_active" value="">
                        <input type="checkbox" form="promocodeForm"
                               name="promocode_notification_active" <?php echo $promocode_notification_active != null && $promocode_notification_active == 'on' ? 'checked' : '' ?>>
                        <form id="promocodeForm" style="display: none" method="post" class="ui form"></form>
                    </div>
                    <a href="#" class="red" style="float:right" data-toggle="promocode-notification"><?php echo $lang['closeConfig'] ?></a>
                </div>
                <div data-config="promocode-notification" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <p><?php echo $lang['presentInfo']?></p>

                            <form class="ui form" id="widget-promocode-notifications" method="post">
                                <div class="field">
                                    <div class="fields">
                                        <div class="field">
                                            <label><?php echo $lang['numberMails'] ?></label>
                                            <select class="ui search dropdown" name="promocode_number_emails">
                                                <option value=""><?php echo $lang['select'] ?></option>
                                                <option value="1" <?php echo $promocode_number_emails == 1 ? 'selected' : '' ?>>1</option>
                                                <option value="2" <?php echo $promocode_number_emails == 2 ? 'selected' : '' ?>>2</option>
                                                <option value="3" <?php echo $promocode_number_emails == 3 ? 'selected' : '' ?>>3</option>
                                            </select>
                                        </div>

                                        <div class="field">
                                            <label><?php echo $lang['separateEmails'] ?></label>
                                            <select class="ui search dropdown" name="promocode_days_subsequent">
                                                <option value=""><?php echo $lang['select'] ?></option>
                                                <option value="1" <?php echo $promocode_days_subsequent == 1 ? 'selected' : '' ?>>1</option>
                                                <option value="2" <?php echo $promocode_days_subsequent == 2 ? 'selected' : '' ?>>2</option>
                                                <option value="3" <?php echo $promocode_days_subsequent == 3 ? 'selected' : '' ?>>3</option>
                                                <option value="4" <?php echo $promocode_days_subsequent == 4 ? 'selected' : '' ?>>4</option>
                                                <option value="5" <?php echo $promocode_days_subsequent == 5 ? 'selected' : '' ?>>5</option>
                                                <option value="6" <?php echo $promocode_days_subsequent == 6 ? 'selected' : '' ?>>6</option>
                                                <option value="7" <?php echo $promocode_days_subsequent == 7 ? 'selected' : '' ?>>7</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label><?php echo $lang['separateHoursEmails'] ?></label>
                                            <select class="ui search dropdown" name="promocode_first_lapse">
                                                <option value=""><?php echo $lang['select'] ?></option>
                                                <option value="30" <?php echo $promocodeFirstLapse == 30 ? 'selected' : '' ?>>30 minutos</option>
                                                <option value="60" <?php echo $promocodeFirstLapse == 60 ? 'selected' : '' ?>>1 hora</option>
                                                <option value="120" <?php echo $promocodeFirstLapse == 120 ? 'selected' : '' ?>>2 horas</option>
                                                <option value="360" <?php echo $promocodeFirstLapse == 360 ? 'selected' : '' ?>>6 horas</option>
                                                <option value="1440" <?php echo $promocodeFirstLapse == 1440 ? 'selected' : '' ?>>1 día</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <input type="submit" class="primary-color bg ui button has-loader"
                                        value="Guardar configuración "/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            

        <?php } else {
            $this->insert('partials::widget/no-widget-message');
        } ?>
    <?php } else { 
        $this->insert('partials::widget/no-widget-message');
    } ?>

</div>

<script src="https://unpkg.com/huebee@1/dist/huebee.pkgd.min.js"></script>
<script src="<?php echo $this->asset('/public/javascript/jodit.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        //toggle configurations
        $("[data-toggle]").click(function (e) {
            e.preventDefault();
            var link = $(this);
            var configuration = link.data("toggle");
            $(".row").find("[data-config='" + configuration + "']").slideToggle('fast', function () {
                link.html(link.text() == '<?php echo $lang['openConfig']?>' ? '<?php echo $lang['closeConfig']?>' : '<?php echo $lang['openConfig']?>');
                link.toggleClass('red');
            }).toggleClass('dnone');
            //Adjust the weight of sidebar
            adjustWidth();
            adjustHeight();
        });

        $('#widget_booking_email_active').click(function(){
            $('#widgetBookingEmailForm').submit();
        });

        $('#notification_active').click(function(){
            $('#checkInForm').submit();
        });
        
        $('#remarketing_notification_active').click(function(){
            $('#remarketingForm').submit();
        });
        
        $('#promocode_notification_active').click(function(){
            $('#promocodeForm').submit();
        });
    });

    // initials on multiple elements with loop
    var elems = document.querySelectorAll('.color-input');
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        var hueb = new Huebee(elem, {
            // options
        });
    }

    
</script>