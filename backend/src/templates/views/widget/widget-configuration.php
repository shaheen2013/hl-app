<?php include LANG . $_SESSION['userLang'] . '/statistics/widgetConfiguration.php' ?>

<?php $this->layout(
    '_layout::layout', [
                         'title'      => $this->e($title),
                         'page_title' => $this->e($page_title),
                         'page_icon'  => $this->e($page_icon),
                         'hotel_name' => $this->e($hotel_name),
                         'hotel_logo' => $this->e($hotel_logo),
                         'url'        => $url
                     ]
) ?>
<style>
    .itemBox.active {
        margin-bottom: 1px !important;
        border-bottom: 1px solid #d4d4d5 !important;
        border-radius: .28571429rem .28571429rem !important;
    }

    .itemBox-container {
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

    .messages-container {
        display:flex; 
        align-items: end;
        margin-top: .5em;
    }

    .global-info {
        color: #aaaaaa;
        display: inline-flex;
        align-items: center;
        flex-direction: row;
        vertical-align: middle;
    }

    @media (max-width: 425px) {
        .global-info {
            margin-top: .5em;
            padding-left: .5em;
            padding-right: .5em;
        }
    }

    .global-info-text {
        max-width: 30em;
        margin-left: 1em;
    }

    .lang-buttons-container {
        display: inline-flex;
        margin-bottom: .5em;
    }
    
    .lang-buttons {
        min-height: unset !important;
        margin: .5em 0 .5em 0 !important;
    }

    .lang-button {
        padding: .78571429em 1.5em .78571429em !important;
        border-radius: .5em !important;
    }

    @media (max-width: 768px) {
        .lang-buttons {
            flex-wrap: wrap;
        }

        .lang-button {
            flex-basis: 25% !important;
            margin-top: .5em !important;
            width: 1em !important:
            justify-content: center;
        }
  
    }
    
    .ui.tabular.menu .item {
        background-color: #eeeeee;
        margin-right: .5em;
    }

    .ui.tabular.menu .active.item {
        border-color: #7258f6 !important;
        border-radius: .5em !important;
        background-color: #eeeeee;
    }

    .ui.tabular.menu .item:hover {
        background-color: #ddd;
    }

    .correctMessage {
        border: 1px solid #9ffa5c !important;
    }
    
    .warningMessage {
        border: 1px solid #ed8919 !important;
    }
    
    .errorMessage {
        border: 1px solid red !important;
    }
</style>

<div class="ui four column grid" style="padding:2rem">
    <?php if (($widgetActive || $parentWidgetActive) && array_get($_SESSION, 'permisos.widget')) { ?>
    <div class="ui modal modal_tag-code">
        <i class="close icon"></i>
        <div class="header">
            <?php echo $lang['tagCode'] ?>
        </div>
        <div class="content">
            <div class="description">
                <p><strong><?php echo $lang['tagCodeInstructions'] ?></strong></p>
                <div class="ui raised segment">
                    <pre>
                        <?php echo htmlspecialchars(
                            "
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
    (window, document, 'script', 'hlw', '$builderUrl'));
    hlw('uid', '$widgetCode');
    hlw('position', 'right');
</script>"
                        ); ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column">
            <a href="#" class="show-tag-button"><?php echo $lang['getCode'] ?></a>
        </div>
    </div>

    <div class="ui card">
        <div class="content">
            <h3 class="inline"><?php echo $lang['chainWidget'] ?></h3>
            <div class="ui toggle checkbox" id="chainWidget">
                <input type="hidden" form="widgetChainForm" name="chainWidget" value="">
                <input type="checkbox" form="widgetChainForm"
                       name="chainWidget" <?php echo $chainWidget != null ? 'checked' : '' ?>>
            </div>
            <form id="widgetChainForm" style="display: none" method="post" class="ui form"></form>
        </div>
    </div>
    <script>
        $('#chainWidget').click(function () {
            $('#widgetChainForm').submit();
        });
    </script>

    <div class="ui card">
        <div class="content">
            <h3 class="inline"><?php echo $lang['widgetConfig'] ?></h3>
            <a href="#" class="red" style="float:right" data-toggle="widget"><?php echo $lang['closeConfig'] ?></a>
        </div>
        <div data-config="widget" class="content">
            <div class="row">
                <div class="sixteen wide column">
                    <form id="widget-base-config" method="post" class="ui form">
                        <div class="fields">
                            <div class="field">
                                <label><?php echo $lang['bgColor'] ?></label>
                                <input type="text" name="widget_background_color" id="widget_background_color"
                                       class="color-input"
                                       value="<?php echo $widget_background_color ?>">
                            </div>
                            <div class="field">
                                <label><?php echo $lang['titlesColor'] ?></label>
                                <input type="text" name="widget_title_color" class="color-input"
                                       value="<?php echo $widget_title_color ?>">
                            </div>
                            <div class="field">
                                <label><?php echo $lang['textColor'] ?></label>
                                <input type="text" name="widget_text_color" class="color-input"
                                       value="<?php echo $widget_text_color ?>">
                            </div>
                            <div class="field">
                                <label><?php echo $lang['linksColor'] ?></label>
                                <input type="text" name="link_colors" class="color-input"
                                       value="<?php echo $link_colors ?>">
                            </div>
                            <div class="field">
                                <label><?php echo $lang['buttonsColor'] ?></label>
                                <input type="text" name="button_color" class="color-input"
                                       value="<?php echo $button_color ?>">
                            </div>
                            <div class="field">
                                <label><?php echo $lang['buttonsTextColor'] ?></label>
                                <input type="text" name="text_button_color" class="color-input"
                                       value="<?php echo $text_button_color ?>">
                            </div>
                        </div>
                        <div class="field">
                            <button class="primary-color bg ui button has-loader" id="set-default">
                                <?php echo $lang['defaultConfig'] ?>
                            </button>

                            <script>
                                $('#set-default').click(function (e) {
                                    e.preventDefault();
                                    $('#position-right').prop('checked', true);
                                    $("[name='widget_background_color']").val('#edf2fe');
                                    $("[name='widget_title_color']").val('#000');
                                    $("[name='widget_text_color']").val('#000');
                                    $("[name='link_colors']").val('#ff6138');
                                    $("[name='button_color']").val('#ff6138');
                                    $("[name='text_button_color']").val('#FFF');
                                    $("#widget-base-config").submit();

                                })
                            </script>
                            <input type="submit" class="primary-color bg ui button has-loader"
                                   value="Guardar configuración del widget">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($defaultOffer) { ?>
        <!--            activar y configuracion basica-->

        <div class="ui card">
            <div class="content">
                <h3 class="inline"><?php echo $lang['widgetAssistent'] ?></h3>
                <div class="ui toggle checkbox" id="widgetActive">
                    <input type="hidden" form="assistantActiveForm" name="widgetActive" value="">
                    <input type="checkbox" form="assistantActiveForm"
                           name="widgetActive" <?php echo $showWidget != null ? 'checked' : '' ?>>
                </div>
                <form id="assistantActiveForm" style="display: none" method="post" class="ui form"></form>
            </div>

            <div class="content">
                <div class="row">
                    <?php echo $lang['showAssistentDescription'] ?>
                </div>
            </div>
        </div>

        <div class="ui card">
            <div class="content">
                <h3 class="inline"><?php echo $lang['widgetAssistentConfig'] ?></h3>
                <form id="assistantActiveForm" style="display: none" method="post" class="ui form"></form>
                <a href="#" class="red" style="float:right"
                   data-toggle="widget-assistent"><?php echo $lang['closeConfig'] ?></a>
            </div>

            <div data-config="widget-assistent" class="content">
                <div class="row">
                    <div class="sixteen wide column">
                        <form id="widget-assistent-config" method="post" class="ui form">
                            <div class="fields">
                                <div class="field">
                                    <label><?php echo $lang['assistentImage'] ?></label>
                                    <div class="panel panel-default">
                                        <input type="hidden" name="assistant_img" id="assistant_img" class="color-input"
                                               value="<?php echo $background_img ?>">
                                        <div class="panel-body" style="text-align: center;">
                                            <img id="bg_image"
                                                 style="margin-bottom:1rem;width: 8rem;height: 8rem;border: 7px solid white;box-shadow: 0px 0px 5px 0px black;border-radius: 100%;"
                                                 src="<?php echo !empty($background_img) ? imageSize('original', $background_img) : DIR_IMG . 'placeholder.png' ?>"
                                                 alt="Assistant Image" class="img-thumbnail text-center">
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <label><?php echo $lang['assistentName'] ?></label>
                                    <input type="text" name="assistant_name" id="assistant_name"
                                           value="<?php echo $assistant_name ?>">
                                </div>
                            </div>

                            <div class="field">
                                <div id="fine-uploader" style="display:inline-block;vertical-align: top;"></div>
                                <script type="text/template" id="qq-template-s3">
                                    <div class="qq-uploader-selector qq-uploader ">

                                        <div class="primary-color bg ui button btn btn-success btn-lg center-block qq-upload-button-selector">
                                            Subir Imagen
                                        </div>

                                        <div class="qq-upload-list-selector " style="display:none;">
                                            <div class="image">
                                                <a class="preview-link" target="_blank">
                                                    <img class="qq-thumbnail-selector" qq-max-size="120"
                                                         qq-server-scale>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </script>
                                <!-- JavaScript -->
                                <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
                                <!-- CSS -->
                                <link rel="stylesheet"
                                      href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
                                <!-- Semantic UI theme -->
                                <link rel="stylesheet"
                                      href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>

                                <link href="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.fine-uploader/fine-uploader-gallery.min.css"
                                      rel="stylesheet">
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.fine-uploader/s3.fine-uploader.core.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.jquery.fine-uploader/s3.jquery.fine-uploader.min.js"></script>
                                <script>
                                    var hotel_guid = "<?php echo(array_get($_SESSION, 'guid_logueado')) ?>";
                                    var hotel_id = "<?php echo(array_get($_SESSION, 'h_logueado')) ?>";
                                    var timestamp = new Date().getTime();

                                    console.log(hotel_guid);
                                    console.log(hotel_id);

                                    $('#fine-uploader').fineUploaderS3({
                                        // debug: true,
                                        template: 'qq-template-s3',
                                        multiple: false,
                                        objectProperties: {
                                            'bucket': "<?php echo $_ENV['S3_IMAGES_BUCKET'] ?>",
                                            'key': function (fileId) {
                                                const filename = $('#fine-uploader').fineUploader('getName', fileId);
                                                const ext = filename.substr(filename.lastIndexOf('.') + 1);
                                                const isOriginal = /\((.+)\)/gi.exec(filename) ? false : true;
                                                const file = isOriginal ? 'original' : /\((.+)\)/gi.exec(filename)[1];
                                                const s3Path = 'brands/' + hotel_guid + '/widget/images/' + file + '-' + timestamp + '.jpg';
                                                $('#assistant_img').val("https://<?php echo $_ENV['S3_IMAGES_BUCKET'] . '/'; ?>" + s3Path);
                                                if (isOriginal) {
                                                    this.setUploadSuccessEndpoint('/lib/webservices/file-upload-success-ws.php', fileId);
                                                    this.setUploadSuccessParams({
                                                        'hotel_id': hotel_id,
                                                        'file_type': 'none'
                                                    }, fileId);
                                                }
                                                return s3Path
                                            }
                                        },
                                        scaling: {
                                            sendOriginal: true,
                                            hideScaled: true,
                                            sizes: [
                                                {name: "small", maxSize: 400},
                                                {name: "medium", maxSize: 800},
                                                {name: "large", maxSize: 1200}
                                            ]
                                        },
                                        maxConnections: 10,
                                        request: {
                                            endpoint: "<?php echo $_ENV['S3_IMAGES_BUCKET_ENDPOINT'] ?>",
                                            accessKey: '<?php echo AWS['credentials']['key']; ?>'
                                        },
                                        signature: {
                                            endpoint: '/lib/webservices/image-upload-ws.php'
                                        },
                                        // uploadSuccess: {
                                        //     endpoint: '/lib/webservices/file-upload-success-ws.php'
                                        // },
                                        chunking: {
                                            enabled: true,
                                            concurrent: {
                                                enabled: true
                                            }
                                        },
                                        resume: {
                                            enabled: true
                                        },
                                        validation: {
                                            allowedExtensions: ['jpeg', 'jpg', 'png'],
                                            // sizeLimit: 10000000, // 10mb
                                            // minSizeLimit: 400000, // 400kb
                                            itemLimit: 4
                                        },
                                        notAvailablePath: "<?php echo(DIR_IMG . 'placeholder.png') ?>",
                                        waitingPath: "<?php echo(DIR_IMG . 'placeholder.png') ?>",
                                        callbacks: {
                                            onValidate: function (file) {
                                                if (!hotel_id || !hotel_guid) {
                                                    alertify.error('There was an error, please refresh the page');
                                                    return false;
                                                }

                                                if (file.name.indexOf('(') !== -1) {
                                                    alertify.error('No parenthesis allowed');
                                                    return false;
                                                } else {
                                                    return file;
                                                }
                                            },
                                            onError: (id, name, errorReason, xhrOrXdr) => alertify.error(errorReason),
                                            onProgress: (id, name, uploadedBytes, totalBytes) => {
                                                let percent = Math.round(uploadedBytes / totalBytes * 100);
                                                // console.log(percent);
                                                // $(`[qq-file-id=${id}] > .qq-progress-bar-container-selector`).progress('set percent', percent )
                                            },
                                            onComplete: function (id, name, responseJSON, xhr) {
                                                if (name.indexOf('(') === -1) {
                                                    this.drawThumbnail(id, document.getElementById("bg_image"), 2000);
                                                    alertify.success(`${name} successfully uploaded`)
                                                }
                                            },
                                            onAllComplete: function (succeededIDs, failedIDs) {
                                                this.reset();
                                            }
                                        }
                                    });
                                </script>
                                <div class="row">
                                    <div class="sixteen wide column">
                                        <div class="field">
                                            <div class="fields">
                                                <div class="field lang-buttons-container ">
                                                    <div class="ui top tabular lang-buttons itemBox-container menu">
                                                        <?php foreach ($langs as $messageLang) { ?>
                                                            <div id="button_tab_assistant_job_<?php echo $messageLang ?>" class="item itemBox ui button lang-button <?php echo $messageLang == 'en' ? 'active' : '' ?>"
                                                                data-tab="assistant_job_texts_<?php echo $messageLang ?>"><?php echo $messageLang; ?></div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div id="globalMessages" class="global-info">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="item">
                                                <?php foreach ($langs as $messageLang) { ?>
                                                    <div class="ui tab <?php echo $messageLang == 'en' ? 'active' : '' ?>"
                                                        style="border-top: none; width:100%" data-tab="assistant_job_texts_<?php echo $messageLang ?>">
                                                        <div class="ui messages-container" data-tab="assistant_job_messages_<?php echo $messageLang ?>">
                                                            <div style="margin-right:2em"class="ten wide field">
                                                                <div class="field">
                                                                    <label><?php echo $lang['widgetAssistantJob'] ?></label>
                                                                    <input 
                                                                        id="assistant-job-layer-text_<?php echo $messageLang ?>" 
                                                                        type="text" 
                                                                        name="message_<?php echo $messageLang ?>" 
                                                                        placeholder="Introduce el cargo" 
                                                                        value="<?php echo !empty($assistantJob[$messageLang]) ? 
                                                                            $assistantJob[$messageLang] : 
                                                                            '' ?>"
                                                                    >
                                                                    <input type="hidden" name="assistant_job" value="assistant_job" />

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="field">
                                <input type="submit" class="primary-color bg ui button has-loader"
                                    value="Guardar configuración del widget">
                                <input id="resetAssistantJob" type="submit" class="red bg ui button" value="Reset" />
                            </div>
                        </form>
                        <form id="resetAssistantJobFrom" method="post" style="display:inline-block">
                            <input type="hidden" name="resetAssistantJob" value="reset" />
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui card">
            <div class="content">
                <h3 class="inline"><?php echo $lang['offerDescription'] ?></h3>
                <a href="#" class="red" style="float:right"
                   data-toggle="behaviour"><?php echo $lang['closeConfig'] ?></a>
            </div>
            <div data-config="behaviour" class="content">
                <div class="row">
                    <div>
                        <form class="ui form" method="post">
                            <div class="field">
                                <div class="fields">
                                    <div class="field">
                                        <label><?php echo $lang['behaviour'] ?></label>
                                        <select class="ui search dropdown" name="behaviour">
                                            <option value=""><?php echo $lang['select'] ?></option>
                                            <?php foreach ($events as $event) { ?>
                                                <option value="<?php echo array_get($event, 'id') ?>">
                                                    <?php echo $lang[array_get($event, 'name')] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label for="behaviour-qty"><?php echo $lang['quantity'] ?></label>
                                        <input type="number" id="behaviour-qty" name="behaviour-qty" value="1" min="1">

                                    </div>
                                    <div class="field">
                                        <label><?php echo $lang['offer'] ?></label>
                                        <select class="ui search dropdown" name="offers">
                                            <?php foreach ($offers as $offer) { ?>
                                                <option value="<?php echo array_get($offer, 'id') ?>_<?php echo array_get($offer, 'booking_engine_code') ? array_get($offer, 'booking_engine_code') : 'none' ?>"><?php echo array_get($offer, 'offer_lang.name') ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <input type="submit" class="primary-color bg ui button has-loader"
                                       value="Añadir Oferta">
                            </div>
                        </form>
                    </div>
                    <div class="" style="width:100%; margin-top:40px;">
                        <div class="ui middle aligned divided list">
                            <?php foreach ($widgetOffers as $widgetOffer) {
                                $widgetOfferId = array_get($widgetOffer, 'offer_id');
                                $offer         = array_filter(
                                    $offers, function ($k) use ($widgetOfferId) {
                                    return array_get($k, 'id') == $widgetOfferId;
                                }
                                );
                                $offerName     = reset($offer);
                                ?>
                                <div class="item">
                                    <form method="post"><input type="hidden"
                                                               value="<?php echo array_get($widgetOffer, 'id') ?>"
                                                               name="widgetOfferToDelete">
                                        <div class="right floated content">
                                            <input type="submit" class="ui button red" value="Eliminar"/>
                                        </div>
                                        <div class="content">
                                            <div class="header">
                                                <?php echo array_get($widgetOffer, 'number_triggers') . " " . $lang[array_get($widgetOffer, 'widgets_event.event.name')] ?>
                                            </div>
                                            <?php echo array_get($offerName, 'offer_lang.name') ?>
                                        </div>
                                    </form>
                                </div>

                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui card">
            <div class="content">
                <h3 class="inline"><?php echo $lang['hidePromocodesTitle']?></h3>
                <div class="ui toggle checkbox" id="hide_promocode">
                    <input type="hidden" form="hidePromocodeForm" name="hide_promocode" value="">
                    <input type="checkbox" form="hidePromocodeForm"
                           name="hide_promocode" <?php echo $hide_promocode != null && $hide_promocode == 'on'  ? 'checked' : '' ?>>
                    <form id="hidePromocodeForm" style="display: none" method="post" class="ui form"></form>
                </div>
                <a href="#" class="red" style="float:right" data-toggle="comments"><?php echo $lang['closeConfig']?></a>
            </div>
            <div data-config="comments" class="content">
                <div class="row">
                    <form class="ui form" method="post">
                        <div class="field">
                            <?php echo $lang['hidePromocodesDescription']?>
                        </div>

                        <div class="fields">
                            <div class="field">
                                <label><?php echo $lang['hidePromocodesParam'] ?></label>
                                <input  
                                    type="text" 
                                    name="hide_promocode_param" 
                                    value="<?php echo $hide_promocode_param ?>"
                                >
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

    <?php } else { ?>
    <div>
        <div>
            <h3><?php echo $lang['widgetDisabled'] ?></h3>
        </div>
        <div>
            <p><?php echo $lang['needOffer'] ?></p>
        </div>
    </div>
    <div class="ui card">
        <div class="content">
            <h3 class="inline"><?php echo $lang['offerDescription'] ?></h3>
            <a href="#" class="red" style="float:right" data-toggle="behaviour"><?php echo $lang['closeConfig'] ?></a>
        </div>
        <div data-config="behaviour" class="row" style="padding-left: 1rem;
    padding-right: 1rem;">
            <div>
                <form class="ui form" method="post">
                    <div class="field">
                        <div class="fields">
                            <div class="field">
                                <label><?php echo $lang['behaviour'] ?></label>
                                <select class="ui search dropdown" name="behaviour">
                                    <option value=""><?php echo $lang['select'] ?></option>
                                    <?php foreach ($events as $event) { ?>
                                        <option value="<?php echo array_get($event, 'id') ?>">
                                            <?php echo $lang[array_get($event, 'name')] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="field">
                                <label for="behaviour-qty"><?php echo $lang['quantity'] ?></label>
                                <input type="number" id="behaviour-qty" name="behaviour-qty" value="1" min="1">

                            </div>
                            <div class="field">
                                <label><?php echo $lang['offer'] ?></label>
                                <select class="ui search dropdown" name="offers">
                                    <?php foreach ($offers as $offer) { ?>
                                        <option value="<?php echo array_get($offer, 'id') ?>_<?php echo array_get($offer, 'booking_engine_code') ? array_get($offer, 'booking_engine_code') : 'none' ?>"><?php echo array_get($offer, 'offer_lang.name') ?></option>
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

                    <?php foreach ($widgetOffers as $widgetOffer) {
                        $widgetOfferId = array_get($widgetOffer, 'offer_id');
                        $offer         = array_filter(
                            $offers, function ($k) use ($widgetOfferId) {
                            return array_get($k, 'id') == $widgetOfferId;
                        }
                        );
                        $offerName     = reset($offer);
                        ?>
                        <div class="item">
                            <form method="post"><input type="hidden"
                                                       value="<?php echo array_get($widgetOffer, 'id') ?>"
                                                       name="widgetOfferToDelete">
                                <div class="right floated content">
                                    <input type="submit" class="ui button red" value="Eliminar"/>
                                </div>
                                <div class="content">
                                    <div class="header"><?php echo array_get($widgetOffer, 'number_triggers') . " " . $lang[array_get($widgetOffer, 'widgets_event.event.name')] ?></div>
                                    <?php echo array_get($offerName, 'offer_lang.name') ?>
                                </div>
                            </form>
                        </div>

                    <?php } ?>

                </div>
            </div>
        </div>
        </div>
        <?php } ?>

        <div class="ui card">
            <div class="content">
                <h3 class="inline"><?php echo $lang['showComments']?></h3>
                <div class="ui toggle checkbox" id="satisfaction_active">
                    <input type="hidden" form="satisfactionActiveForm" name="satisfaction_active" value="">
                    <input type="checkbox" form="satisfactionActiveForm"
                           name="satisfaction_active" <?php echo $satisfaction_active != null && $satisfaction_active == 'on' ? 'checked' : '' ?>>
                    <form id="satisfactionActiveForm" style="display: none" method="post" class="ui form"></form>
                </div>
                <a href="#" class="red" style="float:right" data-toggle="comments"><?php echo $lang['closeConfig']?></a>
            </div>
            <div data-config="comments" class="content">
                <div class="row">
                    <div class="sixteen wide column">
                        <form class="ui form" method="post" id="satisfaction-form">
                            <div style="margin:0 0 1em;" class="field two fields">
                                <div class="field fields">
                                    <label><?php echo $lang['favoriteComments']?></label>
                                    <div class="ui toggle checkbox">
                                        <input type="hidden" form="satisfaction-form" name="exclusive_favorite" value="">
                                        <input type="checkbox" form="satisfaction-form"
                                                name="exclusive_favorite" <?php echo $exclusive_favorite != null && $exclusive_favorite == 'on' ? 'checked' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="two fields">
                                <div class="field">
                                    <label><?php echo $lang['minConfig']?></label>
                                    <input type="number" name="min_satisfaction_score" placeholder="Entre 1 y 10..."
                                           value="<?php echo $min_satisfaction_score ?>">
                                </div>
                                <div class="field">
                                    <label><?php echo $lang['antiquity']?></label>
                                    <select class="ui search dropdown" name="min_satisfaction_date">
                                        <option value=""><?php echo $lang['select']?></option>
                                        <option value="-7" <?php echo $min_satisfaction_date == -7 ? 'selected' : '' ?>>
                                            <?php echo $lang['week']?>
                                        </option>
                                        <option value="-15" <?php echo $min_satisfaction_date == -15 ? 'selected' : '' ?>>
                                            <?php echo $lang['halfMonth']?>
                                        </option>
                                        <option value="-30" <?php echo $min_satisfaction_date == -30 ? 'selected' : '' ?>>
                                            <?php echo $lang['month']?>
                                        </option>
                                        <option value="-90" <?php echo $min_satisfaction_date == -90 ? 'selected' : '' ?>>
                                            <?php echo $lang['tripleMonth']?>
                                        </option>
                                        <option value="-180" <?php echo $min_satisfaction_date == -180 ? 'selected' : '' ?>>
                                            <?php echo $lang['halfYear']?>
                                        </option>
                                        <option value="-360" <?php echo $min_satisfaction_date == -360 ? 'selected' : '' ?>>
                                            <?php echo $lang['year']?>
                                        </option>
                                        <option value="<?php echo PHP_INT_MIN;?>" <?php echo $min_satisfaction_date == null || $min_satisfaction_date == PHP_INT_MIN ? 'selected' : '' ?>>
                                            <?php echo $lang['all']?>
                                        </option>
                                    </select>
                                </div>
            
                            </div>
                            <div class="two fields">
                                <div class="field">
                                    <label><?php echo $lang['betweenComments']?></label>
                                    <div class="ui right labeled input">
                                        <input type="number" name="satisfaction_lapse" placeholder="Recomendado: 5"
                                               value="<?php echo $satisfaction_lapse ?>">
                                        <div class="ui basic label">
                                            <?php echo $lang['seconds']?>
                                        </div>
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

    </div>

    <script src="https://unpkg.com/huebee@1/dist/huebee.pkgd.min.js"></script>
    <script src="<?php echo $this->asset('/public/javascript/jodit.min.js') ?>"></script>
    <script>
        $(document).ready(function () {
            //Show tag code
            $('.modal_tag-code.modal').modal('attach events', '.show-tag-button', 'show');
            // $(".row").find("[data-config]").slideToggle('fast');
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

            $('#widgetActive').click(function () {
                $('#assistantActiveForm').submit();
            });

            $('#satisfaction_active').click(function(){
                $('#satisfactionActiveForm').submit();
            });
            
            $('#hide_promocode').click(function(){
                $('#hidePromocodeForm').submit();
            });
            
            $('#resetAssistantJob').click(function(e){
                e.preventDefault();
                $('#resetAssistantJobFrom').submit();
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

        var globalInfo = [];

        $('[id*=-layer-text_]').each(function() {
            var lang = this.id.slice(-2);

            if (this.value) {
                $('#button_tab_assistant_job_' + lang).addClass('correctMessage');
            } else {
                $('#button_tab_assistant_job_' + lang).addClass('warningMessage');
            }
        });

        $('[id*=-layer-text_]').each(function(element) {
            reactElements(this);
        });

        // React elements when insert character on input
        $("body").on("keyup", "[id*=-layer-text_]", function(element) {
            reactElements(element.currentTarget);  
        });  

        function reactElements(element) {
            var lang = element.id.slice(-2);
            
            if (!element.value) {
                globalInfo.push({element: element.id, type: "warning"});

                $('#button_tab_assistant_job_' + lang).removeClass('correctMessage');
                $('#button_tab_assistant_job_' + lang).addClass('warningMessage');
            } else if (elementIsDirty(globalInfo, element) && element.value) {
                globalInfo = globalInfo.filter(function(e){
                    return e.element != element.id;
                });

                if (!languageIsDirty(globalInfo, lang)) {
                    $('#button_tab_assistant_job_' + lang).removeClass('warningMessage');
                    $('#button_tab_assistant_job_' + lang).addClass('correctMessage');
                }

                if (lang == 'en') {
                    $('.' + element.id[0] + '_messagesSubmitButton_' + messageType).attr("disabled", false);
                }
            } 

            if (globalInfo[0] && globalInfo[0].type == "warning") {
                $(".global-info").html('<i class="ui icon exclamation triangle orange big inline"></i><p class="global-info-text inline orange"><?php echo $lang['warningAssistantJob']?></p>')
            }  else {
                $(".global-info").html('<i class="ui icon check green big inline"></i><p class="global-info-text inline green"><?php echo $lang['successAssistantJob']?></p>')
            }  
        }

        function languageIsDirty (globalInfo, lang) {
            return globalInfo.filter(function(e) { 
                return e.element.slice(-2) === lang; 
            }).length > 0
        }

        function elementIsDirty (globalInfo, element) {
            return globalInfo.filter(function(e) { 
                return e.element === element.id; 
            }).length > 0
        }

    </script>
