<?php //TODO insert lang file here ?>

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
        <div class="ui modal modal_tag-code">
            <i class="close icon"></i>
            <div class="header">
                Código de la etiqueta
            </div>
            <div class="content">
                <div class="description">
                    <p><strong>Copia y pega el siguiente código en el pie de tu página, antes de la etiqueta de cierre
                            "/body"</strong></p>
                    <div class="ui raised segment">
                    <pre>
                        <?php echo htmlspecialchars("
<script>
    var hotelinkingWidgetContainer = document.createElement('div');
    hotelinkingWidgetContainer.setAttribute('id', 'app');
    document.body.appendChild(hotelinkingWidgetContainer);
    (function (w,d,s,o,f,js,fjs) {
            w['hlwidget']=o;w[o] = w[o] || function () { 
            (w[o].q = w[o].q || []).push(arguments) };
            js = d.createElement(s), fjs = d.getElementsByTagName(s)[0];
            js.id = o; js.src = f; js.async = 1; fjs.parentNode.insertBefore(js, fjs);
    }
    (window, document, 'script', 'hlw', 'https://s3-eu-west-1.amazonaws.com/$env-statics.hotelinking.com/widget/builder/bundle.js'));
    hlw('uid', '$widgetCode');
    hlw('position', '$widgetPosition');
</script>"); ?>
                    </pre>
                    </div>
                </div>
            </div>
        </div>
        <!--        ofertas-->
        <div class="row">
            <div class="column">
                <h3>Entregar ofertas según el comportamiento del usuario</h3>
            </div>
            <div class="column">

                <a href="#" style="float:right" data-toggle="behaviour">Abrir configuración</a>
            </div>
        </div>
        <div data-config="behaviour" class="row dnone" style="padding-left: 1rem;
    padding-right: 1rem;">
            <div>
                <form class="ui form" method="post">
                    <div class="field">
                        <div class="fields">
                            <div class="field">
                                <label>Comportamiento</label>
                                <select class="ui search dropdown" name="behaviour">
                                    <option value="">Selecciona...</option>
                                    <?php foreach ($events as $event) { ?>
                                        <option value="<?php echo array_get($event, 'id') ?>"><?php echo array_get($event, 'name') ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="field">
                                <label for="behaviour-qty">Cantidad</label>
                                <input type="number" id="behaviour-qty" name="behaviour-qty" value="1">

                            </div>
                            <div class="field">
                                <label>Oferta</label>
                                <select class="ui search dropdown" name="offers">
                                    <?php foreach ($offers as $offer) { ?>
                                        <option value="<?php echo array_get($offer, 'id') ?>_<?php echo array_get($offer, 'booking_engine_code')?array_get($offer, 'booking_engine_code'):'none' ?>"><?php echo array_get($offer, 'offer_lang.0.name') ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <input type="submit" class="primary-color bg ui button has-loader" value="Añadir Oferta">
                    </div>
                </form>
            </div>
            <div class="" style="width:100%; margin-top:40px;">
                <div class="ui middle aligned divided list">

                    <?php  foreach($widgetOffers as $widgetOffer){
                        $widgetOfferId = array_get($widgetOffer, 'offer_id');
                        $offer = array_filter($offers, function($k) use ($widgetOfferId){
                            return array_get($k, 'id') == $widgetOfferId;
                        });
                        $offerName = reset($offer);
                        ?>
                        <div class="item">
                            <form method="post"><input type="hidden" value="<?php echo array_get($widgetOffer, 'id')?>" name="widgetOfferToDelete">
                                <div class="right floated content">
                                    <input type="submit" class="ui button red" value="Eliminar"/>
                                </div>
                                <div class="content">
                                    <div class="header"><?php echo array_get($widgetOffer, 'number_triggers'). " ".array_get($widgetOffer, 'widgets_event.event.name')?></div>
                                    <?php echo array_get($offerName, 'offer_lang.0.name')?>
                                </div>
                            </form>
                        </div>

                    <?php } ?>

                </div>
            </div>
            <div class="ui divider"></div>
        </div>
    <?php } else { 
        $this->insert('partials::widget/no-widget-message');
    } ?>

</div>

<script src="https://unpkg.com/huebee@1/dist/huebee.pkgd.min.js"></script>
<script src="<?php echo $this->asset('/public/javascript/jodit.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        $(".row").find("[data-config]").slideToggle('fast');
        //toggle configurations
        $("[data-toggle]").click(function (e) {
            e.preventDefault();
            var link = $(this);
            var configuration = link.data("toggle");
            $(".row").find("[data-config='" + configuration + "']").slideToggle('fast', function () {
                link.html(link.text() == 'Abrir configuración' ? 'Cerrar configuración' : 'Abrir configuración');
                link.toggleClass('red');
            }).toggleClass('dnone');
            //Adjust the weight of sidebar
            adjustWidth();
            adjustHeight();
        });


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