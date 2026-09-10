<?php include LANG . $_SESSION['userLang'] . '/statistics/widgetPrivacy.php'?>

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
                    <h3 class="inline"><?php echo $lang['showPrivacy']?></h3>
                    <div class="ui toggle checkbox"></div>
                    <a class="red" href="#" style="float:right" data-toggle="gdpr"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="gdpr" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <form class="ui form" method="post">
                                <div class="fields">
                                    <div class="field">
                                        <label><?php echo $lang['privacyText']?></label>
                                        <select class="ui search dropdown" name="gdpr-privacy-text">
                                            <option value="default" <?php echo array_get($smallEprivacyText, 'configuration') == "default" ? 'selected' : '' ?> >
                                                <?php echo $lang['privacyTextRecommend']?>
                                            </option>
                                            <option value="own_vars" <?php echo array_get($smallEprivacyText, 'configuration') == "own_vars" ? 'selected' : '' ?>>
                                                <?php echo $lang['customData']?>
                                            </option>
                                            <option value="custom_content" <?php echo array_get($smallEprivacyText, 'configuration') == "custom_content" ? 'selected' : '' ?>>
                                                <?php echo $lang['customizedText']?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="fields">
                                        <div class="six wide field">
                                            <label><?php echo $lang['entityName']?></label>
                                            <input required type="text" name="gdpr-company-name"
                                                value="<?php echo array_get($eprivacyBrandInfo, 'company_name') ?>"
                                                placeholder="Empresa S.L.">
                                        </div>
                                        <div class="four wide field">
                                            <label><?php echo $lang['cif']?></label>
                                            <input required type="text" name="gdpr-cif"
                                                value="<?php echo array_get($eprivacyBrandInfo, 'company_nif') ?>"
                                                placeholder="CIF...">
                                        </div>
                                        <div class="six wide field">
                                            <label><?php echo $lang['mail']?></label>
                                            <input required type="email" value="<?php echo array_get($eprivacyBrandInfo, 'company_email') ?>"
                                                name="gdpr-email" placeholder="Email...">
                                        </div>
                                    </div>
                                </div>
                                <div class="fields">
                                    <div class="sixteen wide field">
                                        <label><?php echo $lang['adress']?></label>
                                        <input required type="text" name="gdpr-address"
                                            value="<?php echo array_get($eprivacyBrandInfo, 'company_address') ?>"
                                            placeholder="Dirección...">
                                    </div>
                                </div>
                                <div class="fields" style="margin-bottom: 0">
                                    <div class="field" style="width: 100%;">
                                        <label><?php echo $lang['language']?></label>
                                        <div class="ui top attached tabular menu">
                                            <?php foreach ($langs as $langTexts) { ?>
                                                <div class="item <?php echo $langTexts == 'en' ? 'active' : '' ?>"
                                                    data-tab="texts_<?php echo $langTexts ?>"><?php echo $langTexts ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php foreach ($langs as $langTexts) { ?>
                                    <div class="ui field bottom attached tab segment <?php echo $langTexts == 'en' ? 'active' : '' ?>"
                                        style="border-top: none;" data-tab="texts_<?php echo $langTexts ?>">


                                        <div class="ui top attached tabular menu">
                                            <div class="item active" data-tab="first_<?php echo $langTexts ?>"><?php echo $lang['legalDescription']?></div>
                                            <div class="item" data-tab="second_<?php echo $langTexts ?>"><?php echo $lang['conditions']?></div>
                                            <div class="item" data-tab="fourth_<?php echo $langTexts ?>"><?php echo $lang['privacyPolicy']?></div>
                                        </div>
                                        <div class="ui bottom attached tab segment active" data-tab="first_<?php echo $langTexts ?>">
                                <textarea
                                    name="widget_eprivacy_small_text_<?php echo $langTexts ?>"
                                    id="first-layer-text_<?php echo $langTexts ?>"
                                ></textarea>
                                        </div>

                                        <div class="ui bottom attached tab segment" data-tab="second_<?php echo $langTexts ?>">
                                <textarea
                                    name="widget_eprivacy_checkbox_text_<?php echo $langTexts ?>"
                                    id="second-layer-text_<?php echo $langTexts ?>"
                                ></textarea>
                                        </div>

                                        <div class="ui bottom attached tab segment" data-tab="fourth_<?php echo $langTexts ?>">
                                <textarea
                                    name="legal_text_<?php echo $langTexts ?>"
                                    id="privacy-policy-text_<?php echo $langTexts ?>"
                                ></textarea>
                                        </div>

                                    </div>
                                <?php } ?>
                                <div class="field">
                                    <input type="submit" class="primary-color bg ui button"
                                        value="Guardar configuración legal"/>
                                </div>
                            </form>
                        </div>
                    </div> 
                </div>
            </div>
            
        <?php } else {?>
            <div>
                <div>
                    <h3><?php echo $lang['widgetDisabled']?></h3>
                </div>
                <div>
                    <p>
                        <?php echo $lang['needOffer']?>
                    </p>
                </div>
            </div>

        <?php } ?>
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
        <?php if($defaultOffer){ ?>
            <?php foreach ($langs as $lang) { ?>
                var firstLayerText_<?php echo $lang ?> = new Jodit('#first-layer-text_<?php echo $lang ?>', {
                    height: 500,
                });

                firstLayerText_<?php echo $lang ?>.value = "<?php echo addslashes(array_get($smallEprivacyText, $lang)) ?>";


                var secondLayerText_<?php echo $lang ?> = new Jodit('#second-layer-text_<?php echo $lang ?>', {
                    height: 500,
                });
                secondLayerText_<?php echo $lang ?>.value = "<?php echo addslashes(array_get($termsOfUseText, $lang)) ?>";


                var privacyPolicy_<?php echo $lang ?> = new Jodit('#privacy-policy-text_<?php echo $lang ?>', {
                    height: 500,
                });

                privacyPolicy_<?php echo $lang ?>.value = "<?php echo addslashes(array_get($eprivacyText, $lang)) ?>";
            <?php } ?>
            
            //Show tag code
            $('.modal_tag-code.modal')
            .modal('attach events', '.show-tag-button', 'show');
        <?php } ?>
    })

    // initials on multiple elements with loop
    var elems = document.querySelectorAll('.color-input');
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        var hueb = new Huebee(elem, {
            // options
        });
    }
</script>