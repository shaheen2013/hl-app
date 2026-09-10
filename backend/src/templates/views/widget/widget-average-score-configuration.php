<?php

include LANG . $_SESSION['userLang'] . '/statistics/widgetAverageScore.php'?>

<?php $this->layout('_layout::layout', [
    'title'         => $this->e($title),
    'page_title'    => $this->e($page_title),
    'page_icon'     => $this->e($page_icon),
    'hotel_name'    => $this->e($hotel_name),
    'hotel_logo'    => $this->e($hotel_logo),
    'url'           => $url
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
            <div class="ui card">
                <div class="content">
                    <h3 class="inline">Mostrar nota media de los comentarios</h3>
                    <div class="ui toggle checkbox" id="average_score_active">
                        <input type="hidden" form="averageScoreActiveForm" name="average_score_active" value="">
                        <input type="checkbox" form="averageScoreActiveForm"
                            name="average_score_active" <?php echo $average_score_active != null && $average_score_active == 'on' ? 'checked' : '' ?>>
                        <form id="averageScoreActiveForm" style="display: none" method="post" class="ui form"></form>
                    </div>
                    <a href="#" class="red" style="float:right" data-toggle="comments"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="comments" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <form class="ui form" method="post" id="average_score">
                                <div class="fields">
                                    <div class="field">
                                        <label><?php echo $lang['antiquity']?></label>

                                        <select class="ui search dropdown" name="min_average_score_date" >

                                            <option value=""><?php echo $lang['select']?></option>
                                            <option value="-7" <?php echo $min_average_score_date == -7 ? 'selected' : '' ?>>
                                                <?php echo $lang['week']?>
                                            </option>
                                            <option value="-15" <?php echo $min_average_score_date  == -15 ? 'selected' : '' ?>>
                                                <?php echo $lang['halfMonth']?>
                                            </option>
                                            <option value="-30" <?php echo $min_average_score_date  == -30 ? 'selected' : '' ?>>
                                                <?php echo $lang['month']?>
                                            </option>
                                            <option value="-90" <?php echo $min_average_score_date == -90 ? 'selected' : '' ?>>
                                                <?php echo $lang['tripleMonth']?>
                                            </option>
                                            <option value="-180" <?php echo $min_average_score_date == -180 ? 'selected' : '' ?>>
                                                <?php echo $lang['halfYear']?>
                                            </option>
                                            <option value="-360" <?php echo $min_average_score_date  == -360 ? 'selected' : '' ?>>
                                                <?php echo $lang['year']?>
                                            </option>
                                            <option value="<?php echo $bigDaysValue ?>" <?php echo $min_average_score_date == null || $min_average_score_date == $bigDaysValue ? 'selected' : '' ?> >
                                                <?php echo $lang['all']?>
                                            </option>
                                                
                                        </select>
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

        $('#average_score_active').click(function(){
            $('#averageScoreActiveForm').submit();
        });

    })
    
</script>