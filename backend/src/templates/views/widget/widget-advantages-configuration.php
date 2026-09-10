<?php include LANG . $_SESSION['userLang'] . '/statistics/widgetAdvantages.php'?>

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
                    <h3 class="inline"><?php echo $lang['showAdvantages']?></h3>
                    <div id="advantagesToggle" class="ui toggle checkbox">
                        <input type="hidden" form="advantagesActivated" name="benefits_active" value="">
                        <input type="checkbox" form="advantagesActivated"
                               name="benefits_active" <?php echo $benefits_active != null ? 'checked' : '' ?>>
                        <form id="advantagesActivated" style="display: none" method="post" class="ui form"></form>
                    </div>
                    <a href="#" class="red" style="float:right" data-toggle="advantages"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="advantages" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <form class="ui form" id="benefits" method="post">
                                <div class="field">
                                    <div class="fields">
                                        <div class="ui top tabular itemBox-container menu">
                                            <?php foreach ($langs as $benefitLang) { ?>
                                                <div class="item itemBox <?php echo $benefitLang == 'en' ? 'active' : '' ?>"
                                                    data-tab="benefits_texts_<?php echo $benefitLang ?>"><?php echo $benefitLang; echo $benefitLang == 'en' ? '*' : '' ?></div>
                                            <?php } ?>
                                        </div>

                                        <?php foreach ($langs as $benefitLang) { ?>
                                            <div class="ui tab <?php echo $benefitLang == 'en' ? 'active' : '' ?>"
                                                style="border-top: none; width:100%" data-tab="benefits_texts_<?php echo $benefitLang ?>">

                                                <div class="ui active" data-tab="benefits_<?php echo $benefitLang ?>">
                                                    <div class="four wide field" style="display:inline-block">
                                                        <label><?php echo $lang['title']?> <?php echo ' ('. $lang['titleConditions'] . ')'?></strong></label>
                                                        <input maxlength="40" <?php echo $benefitLang == 'en' ? 'required' : '' ?>  <?php echo count($benefits_list) > 9 ? 'disabled' : '' ?> id="benefits-layer-text_<?php echo $benefitLang ?>" type="text" name="title_<?php echo $benefitLang ?>" placeholder="Título...">
                                                    </div>
                                                    <div class="ten wide field" style="display:inline-block">
                                                        <label><?php echo $lang['description']?></label>
                                                        <input id="benefits-layer-text_<?php echo $benefitLang ?>" type="text" name="description_<?php echo $benefitLang ?>" <?php echo count($benefits_list) > 9 ? 'disabled' : '' ?> placeholder="Descripción de la ventaja...">
                                                    </div>
                                                </div>

                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui checkbox guarantee-checkbox">
                                        <input <?php echo count($benefits_list) > 9 ? 'disabled' : '' ?> type="checkbox" tabindex="0" class="hidden" name="guaranteed">
                                        <label><?php echo $lang['advantagesGuaranteed']?></label>
                                    </div>
                                </div>
                                <div class="field">
                                    <input type="hidden" name="json_benefits_list" value="<?php echo htmlspecialchars($json_benefits_list, ENT_QUOTES, 'UTF-8');?>"/>
                                    <input <?php echo count($benefits_list) > 9 ? 'disabled' : '' ?> id="advantagesSubmitButton" type="submit" class="primary-color bg ui button" value="Añadir ventaja"  style="display:inline-block"/>
                                    <div id="validationError" class="red" style="display:inline-block"></div>
                                    <p class="mt-4"><strong><?php echo $lang['needAdvantageInEnglish']?></strong></p>
                                </div>
                            </form>
                        </div>
                        <div class="column sixteen wide" style="margin-top:40px;">
                            <div class="ui middle aligned divided list">
                                <?php if ($benefits_list) {
                                    foreach ($benefits_list as $benfit) { ?>
                                        <div class="item">
                                            <?php foreach ($langs as $benefitLang) { ?>
                                                <div class="ui tab <?php echo $benefitLang == 'en' ? 'active' : '' ?>"
                                                    style="border-top: none; width:100%" data-tab="benefits_texts_<?php echo $benefitLang ?>">
                                                    <form method="post">
                                                        <div class="right floated content">   
                                                            <?php if (isset($benfit['en']) && array_get($benfit['en'], 'guaranteed')) { ?>
                                                                <span style="margin-right:10px;"><strong><?php echo $lang['guaranteed'] ?> </strong></span>
                                                            <?php } ?> 

                                                            <input type="hidden" name="json_benefits_list" value="<?php echo htmlspecialchars($json_benefits_list, ENT_QUOTES, 'UTF-8');?>"/>
                                                            <input type="submit" class="ui button red" value="Eliminar">
                                                        </div>
                                                        <div class="content">
                                                            <div class="header">
                                                                <span>
                                                                    <?php 
                                                                        echo array_key_exists($benefitLang, $benfit) ?
                                                                            !empty(array_get($benfit[$benefitLang], 'title')) ? 
                                                                            array_get($benfit[$benefitLang], 'title') : 
                                                                            array_get($benfit['en'], 'title') :
                                                                        array_get($benfit['en'], 'title') ?> 
                                                                </span>    
                                                            </div>
                                                                
                                                                <?php echo (array_key_exists($benefitLang, $benfit) && !empty(array_get($benfit[$benefitLang], 'description'))  ? array_get($benfit[$benefitLang], 'description') : array_get($benfit['en'], 'description')) ?>
                                                                <input type="hidden" name="delete_title" value="<?php echo array_get($benfit['en'], 'title') ?>"
                                                            />
                                                        </div>
                                                    </form>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
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
                    <p><?php echo $lang['needOffer']?></p>
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

        $('#advantagesToggle').click(function(){
            $('#advantagesActivated').submit();
        });

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

        if (<?php echo $widgetActive ?> && <?php echo count($benefits_list)?> > 9) {
            $("#validationError").text("<?php echo $lang['onlyTenAdvantagesAllowed'] ?>");
        }

        $('#advantagesSubmitButton').click(function() {
            if (!$("input[name='title_en']").val()) {
                $("#validationError").text("<?php echo $lang['titleError']?>");
            }
        })


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