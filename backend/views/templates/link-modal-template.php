<div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h5 class="modal-title text-center"
                    id="myModalLabel"><?php echo $stayShareLang['Give us your name and email'] ?></h5>
            </div>
            <div class="modal-body">
                <form method="post" id="logByEmail"
                      action="<?php echo $urlTree['stay-share'] . '/' . $_SESSION['guidHotel'] ?>">
                    <label id="refShareStep2firstNameIdLabel"><?php echo $stayShareLang["Your name"] ?></label>
                    <input class="form-control"
                        id="refShareStep2firstNameId"
                        name="refShareStep2firstName"
                        type="text"
                        autocomplete='firstName'
                        minlength='<?php echo $firstNameLength?>'
                        pattern='<?php echo $firstNamePattern?>'
                        onfocusout="trimSpaces()"
                        required
                        placeholder="<?php echo $stayShareLang['We love to be more kind and personal...'] ?>"
                        value="<?php echo array_get($_SESSION, 'first_name', array_get($_SESSION, 'first_name_prefill', '')) ?>"
                    >

                    <label id="refShareStep2lastNameIdLabel" class="mt"><?php echo $stayShareLang["Your last name"] ?></label>
                    <input class="form-control"
                        id="refShareStep2lastNameId"
                        name="refShareStep2lastName"
                        type="text"
                        autocomplete='lastName'
                        minlength='<?php echo $lastNameLength?>'
                        pattern='<?php echo $lastNamePattern?>'
                        onfocusout="trimSpaces()"
                        required
                        placeholder="..."
                        value="<?php echo array_get($_SESSION, 'last_name', array_get($_SESSION, 'last_name_prefill', '')) ?>"
                    >

                    <label class="mt" <?php echo (array_get($_SESSION, 'pms_user_email') && array_get($_SESSION, 'is_pms_user_email_valid', true)) || (array_get($_SESSION, 'email', '') && !empty(array_get($_SESSION, 'email'))) ? 'style=display:none' : '' ?>><?php echo $stayShareLang['Your email'] ?></label>
                    <input class="form-control"
                           name="refShareStep2email"
                           type="email"
                           autocomplete='email'
                           required
                           placeholder="<?php echo $stayShareLang['Email where you want to receive your link...'] ?>"
                           value="<?php echo array_get($_SESSION, 'email') ? array_get($_SESSION, 'email') : array_get($_SESSION, 'pms_user_email') ?>"
                        <?php echo array_get($_SESSION, 'pms_user_email') && array_get($_SESSION, 'is_pms_user_email_valid', true) || (array_get($_SESSION, 'email', '') && !empty(array_get($_SESSION, 'email'))) ? 'style=display:none' : '' ?> />

                    <?php if (!$portalProductConfig || !isset($portalProductConfig->remove_gender_field) || !$portalProductConfig->remove_gender_field): ?>
                    <label class="mt" id="refShareStep2genderIdLabel"><?php echo $stayShareLang['Gender *(required)'] ?></label>
                    <select id="refShareStep2genderId" name="refShareStep2gender" required class="form-control">
                        <option value="" <?php echo array_get($_SESSION, 'gender') != 'male' && array_get($_SESSION, 'gender') != 'female' && array_get($_SESSION, 'gender') != 'other' ? 'selected' : '' ?>
                                disabled><?php echo $stayShareLang['Select you gender'] ?></option>
                        <option value="male" <?php echo array_get($_SESSION, 'gender') === 'male' ? 'selected' : '' ?>><?php echo $stayShareLang['Male'] ?></option>
                        <option value="female" <?php echo array_get($_SESSION, 'gender') === 'female' ? 'selected' : '' ?> ><?php echo $stayShareLang['Female'] ?></option>
                        <?php if ($portalProductConfig && isset($portalProductConfig->add_other_option_on_gender) && $portalProductConfig->add_other_option_on_gender): ?>
                        <option value="other" <?php echo array_get($_SESSION, 'gender') === 'other' ? 'selected' : '' ?> ><?php echo $stayShareLang['Other'] ?? 'Other' ?></option>
                        <?php endif; ?>
                    </select>
                    <?php endif; ?>
                    <?php
                    $productConfig = array_filter($_SESSION['brandProducts'], function($product) {
                        return $product['product_id'] == '24';
                    });

                    include_once RUTA_DIR . LIB . 'countryCodes.php';

                    // Delete duplicated codes
                    $uniqueCountryCodes = array_unique(array_column($countryCodes, 'code'));

                    // Convert back to original format array
                    $uniqueCountryCodes = array_map(function($code) {
                        return ['code' => $code];
                    }, $uniqueCountryCodes);

                    // Sort country codes
                    usort($uniqueCountryCodes, function ($a, $b) {
                        return intval($a['code']) - intval($b['code']);
                    });

                    if(!empty($productConfig)) {
                        $firstProduct = reset($productConfig);
                        $config = json_decode($firstProduct['config']);

                        if ($config && isset($config->phone_active) && $config->phone_active) {
                            echo '<label class="mt" id="phone_numberLabel">'
                                        . $stayShareLang["Your phone"]
                                . '</label>';

                            echo '<div class="row">';
                            echo '  <div class="col-xs-5 col-md-3">';
                            echo '      <select class="form-control" id="phone_country_code" name="phone_country_code">';
                            foreach ($uniqueCountryCodes as $country) {
                                $countryCodes = [
                                    'en' => "+44",
                                    'es' => "+34",
                                    'de' => "+49",
                                    'fr' => "+33",
                                    'ca' => "+34",
                                    'it' => "+39",
                                    'zh' => "+86",
                                    'bg' => "+359"
                                ];
                                $dialCodeSelected = $countryCodes[$_SESSION['userLang']] ?? "+34";
                                $selected = ($country['code'] === $dialCodeSelected) ? 'selected' : '';
                                echo '<option value="' . $country['code'] . '" ' . $selected . '>' . $country['code'] . '</option>';
                            }
                            echo '      </select>';
                            echo '  </div>';
                            echo '  <div class="col-xs-7 col-md-9">';
                            echo '      <input class="form-control"
                                                id="phone_number" name="phone_number"
                                                type="tel" maxlength="20"
                                                required
                                                pattern="[\+]?[0-9]{1,19}"
                                                value="' . array_get($_SESSION, 'phoneNumber') . '"
                                                placeholder="'
                                                . $stayShareLang["Enter your phone number"]
                                          . '">';
                            echo '  </div>';
                            echo '</div>';
                        }
                    }
                    ?>
                    <?php if (!array_get($_SESSION, 'birthday')) { ?>
                        <label class="mt"><?php echo $stayShareLang['Date of birth *(required)'] ?></label>
                        <div class="row">
                            <div class="col-xs-4">
                                <?php echo $stayShareLang['Year'] ?>
                                <select name="refShareStep2year" class="form-control" required>
                                    <option disabled selected value="0">...</option>
                                    <?php
                                    $actual = date("Y") - 5;
                                    $back = $actual - 100;
                                    for ($i = $actual; $i >= $back; $i--) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <?php echo $stayShareLang['Month'] ?>
                                <select name="refShareStep2month" class="form-control" required>
                                    <option disabled selected value="0">...</option>
                                    <option value="01">1</option>
                                    <option value="02">2</option>
                                    <option value="03">3</option>
                                    <option value="04">4</option>
                                    <option value="05">5</option>
                                    <option value="06">6</option>
                                    <option value="07">7</option>
                                    <option value="08">8</option>
                                    <option value="09">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <?php echo $stayShareLang['Day'] ?>
                                <select name="refShareStep2day" class="form-control" required>
                                    <option disabled selected value="0">...</option>
                                    <option value="01">1</option>
                                    <option value="02">2</option>
                                    <option value="03">3</option>
                                    <option value="04">4</option>
                                    <option value="05">5</option>
                                    <option value="06">6</option>
                                    <option value="07">7</option>
                                    <option value="08">8</option>
                                    <option value="09">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="checkbox gdprConditions">
                        <label>
                            <input type="checkbox" class="checkbox_check "
                                   name="gdpr_year"> <?php echo $stayShareLang['gdpr_year'] ?>
                        </label>
                    </div>
                    <br>
                    <input type="hidden" name="socialLoginId">
                    <input type="hidden" name="refShareStep2Origin" value="Form">
                    <input type="hidden" name="PHPSESSID" value="<?php echo session_id() ?>">
                    <input class="btn btn-success mt2 disabled send-email-form-button" type="submit"
                           value="<?php echo $stayShareLang['Send me the link'] ?>" name="refShareStep2userData">
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    //check if birthday is less than consent age
    //in that case show checkbox for adult
    $(document).ready(function () {
        "use strict";
        if (<?php echo empty($_SESSION['showNormalPortal']) || ($regularUser && !$showGDPR && !array_get($_SESSION, 'bypassInvalid')) ? 1 : 0 ?>) {
            hideValidElement("refShareStep2firstNameId");
            hideValidElement("refShareStep2lastNameId");
            <?php if (!$portalProductConfig || !isset($portalProductConfig->remove_gender_field) || !$portalProductConfig->remove_gender_field): ?>
            hideValidElement("refShareStep2genderId");
            <?php endif; ?>
            hideValidElement("phone_number");
        }

        var isLessThanConsentAge = false;

        //simple function to see if age is < 16
        var checkIfLessThanConsentAge = function (date) {
            var year = date.getFullYear();
            var month = date.getMonth();
            var day = date.getDate();
            return new Date(parseInt(year) + 16, month - 1, day) > new Date()
        }


        var toggleCheckBox = function (date) {
            if (checkIfLessThanConsentAge(date)) {
                isLessThanConsentAge = true;
                //show checkbox
                $('.gdprConditions').fadeIn();

                //disable submit button and add required param to gdpr checkbox
                $('.send-email-form-button').addClass('disabled');
                $('[name="gdpr_year"]').prop('required', true);
            } else {
                isLessThanConsentAge = false;
                $('.gdprConditions').fadeOut();
                $('[name="gdpr_year"]').prop('required', false);
                $('.send-email-form-button').removeClass('disabled');
            }
        }

        //checkbox checks
        $('.checkbox_check').change(function () {
            if (isLessThanConsentAge) {
                if ($('[name="gdpr_year"]').is(':checked')) {
                    $('.send-email-form-button').removeClass('disabled');
                } else {
                    $('.send-email-form-button').addClass('disabled');
                }
            } else {
                $('.send-email-form-button').removeClass('disabled');
            }
        });

        <?php if (array_get($_SESSION, 'birthday')) {?>
        var birthday = "<?php echo $_SESSION['birthday'] ?>";
        toggleCheckBox(new Date(birthday));
        // $('.send-email-form-button').removeClass('disabled');
        <?php }?>

        //listen to change of birthday selects
        $('[name="refShareStep2day"],[name="refShareStep2month"], [name="refShareStep2year"]').change(function () {
            //get values
            var day = $('[name="refShareStep2day"]').val();
            var month = $('[name="refShareStep2month"]').val();
            var year = $('[name="refShareStep2year"]').val();

            //show gdprconditions isunder constent age
            if (day && month && year) {
                var date = new Date(year, month, day);
                toggleCheckBox(date);
            }
        })


    })

    function trimSpaces() {
        $('#refShareStep2firstNameId').val($('#refShareStep2firstNameId').val().trim());
        $('#refShareStep2lastNameId').val($('#refShareStep2lastNameId').val().trim());
    }

    function hideValidElement (elementId) {
        if ($("#" + elementId).length) {
            if (typeof $('#' + elementId)[0].checkValidity === "function") {
                if ($('#' + elementId)[0].checkValidity()) {
                    $('#' + elementId).hide();
                    $('#' + elementId + 'Label').hide();

                    if (elementId === "phone_number") {
                        $('#phone_country_code').remove();
                    }
                } else {
                    $('#logByEmail').append('<input type="hidden" name="' + elementId + 'Update" value="true" />');
                }
            } else {
                // Old browser, keep old functionality to hide all values already filled in
                if($('#' + elementId).val()) {
                    $('#' + elementId).hide();
                    $('#' + elementId + 'Label').hide();

                    if (elementId === "phone_number") {
                    $('#phone_country_code').remove();
                }
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const symbolPattern = /[!@#$%^&*()+=\[\]{};:"\\|,.<>\/?~`]/;

        function validateNameInput(input) {
            if (!input) return true;

            const value = input.value;
            if (symbolPattern.test(value)) {
                input.setCustomValidity('Special characters are not allowed in names');
                return false;
            } else {
                input.setCustomValidity('');
                return true;
            }
        }

        const nameInputs = document.querySelectorAll('#refShareStep2firstNameId, #refShareStep2lastNameId');

        nameInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                validateNameInput(this);
            });
        });

        const logByEmailForm = document.getElementById('logByEmail');
        if (logByEmailForm) {
            logByEmailForm.addEventListener('submit', function(e) {
                let isValid = true;

                nameInputs.forEach(function(input) {
                    if (!validateNameInput(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });

</script>