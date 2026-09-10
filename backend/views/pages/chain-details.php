<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/chain-details.php' ?>
<?php include LANG . $_SESSION['userLang'] . '/hotel-crear-detalle-oferta.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'chain-management-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-building"></i> <?php echo $lang['Edit Hotel Chain details'] ?>
                </h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-12">
                <form role="form" class="mt2 validation-form" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-8 col-lg-offset-2">
                            <div class="form-group">
                                <label for="chainName"><?php echo $lang['Chain Name'] ?></label>
                                <input type="text" class="form-control" id="chainName" name="chainName"
                                       value="<?php echo $arrayDatosCadena['nombre'] ?>"
                                       placeholder="<?php echo $lang['Write the chain name...'] ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-8 col-lg-offset-2 mt2">
                            <div class="form-group">
                                <label for="ChainContactEmail"><?php echo $lang['Chain contact email'] ?></label>
                                <input type="email" class="form-control" id="ChainContactEmail" name="ChainContactEmail"
                                       value="<?php echo $arrayDatosCadena['email'] ?>"
                                       placeholder="<?php echo $lang['Write the chain contact email...'] ?>" required>
                            </div>
                        </div>

                        <div class="col-lg-8 col-lg-offset-2 mt2">
                            <div class="panel panel-default noPadding">

                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo $lang['Chain website'] ?></h3>
                                </div>
                           
                                <div class="panel-body">
                                        <?php 
                                            foreach($languages as $langs) {?>
                                                <label for="chainWebsite-<?php echo $langs->id ?>"> <?php echo $lang['Chain website'] ?> <?php echo $HotelCrearDetalleOfertaLang['offerLangs'][$langs->name] ?> </label>
                                                <input type="text" class="form-control" id="chainWebsite-<?php echo $langs->id ?>"
                                                    name="chainWebsite[<?php echo $langs->name ?>]"
                                                    placeholder="<?php echo $lang['Chain website'] ?>"
                                                    value="<?php foreach($brandLanguages as $brandLang) {?><?php echo $brandLang->name == $langs->name ? $brandLang->url : '' ?><?php } ?>"/>
                                                   
                                        <?php } ?>

                                <div class="panel-heading  second-heading">
                                    <h3 class="panel-title"><?php echo $lang['secondary urls by country'] ?></h3>
                                </div>

                                <div class="panel-body">
                                    <?php foreach($arrayChainCountryLangs as $chainCountryLang){
                                        if($chainCountryLang['country_lang_id']!=1){?>

                                            <div class="form-group" style = "position:relative">
                                                <a class="delete_url_btn" data-country-lang-id="<?php echo $chainCountryLang['country_lang_id']?>" data-toggle="modal" data-target="#deleteLangUrlModal"><i class="clicable fa fa-minus-square-o"></i>
                                                </a>

                                                <label for="hotelWebsite_<?php echo $chainCountryLang['country_lang_id']?>"><?php echo $chainCountryLang['country']?> website </label>
                                                <input type="text" class="form-control" id="hotelWebsite_<?php echo $chainCountryLang['country_lang_id']?>"
                                                       name="<?php echo $chainCountryLang['country_lang_id']?>"
                                                       placeholder="<?php echo $lang['Direct booking website here...'] ?>"
                                                       value="<?php echo $chainCountryLang['url']?>">

                                            </div>
                                        <?php } }  ?>
                                    <div class="addLang-group">
                                        <label>
                                            <a data-toggle="modal" data-target="#newLangUrlModal"><i class="clicable fa fa-plus-square-o"></i></a>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 col-lg-offset-2 mt2">
                            <div class="form-group">
                                <label><?php echo $lang['stay_time_label'] ?></label>
                                <input type="number" min="1" max="60" class="form-control" value="<?php echo $arrayDatosCadena['stay_time'] ?>" name="stay_time" required>
                            </div>
                        </div>

                        <div class="col-lg-8 col-lg-offset-2 mt2">
                            <div class="form-group">
                                <label><?php echo $lang['loyalty_min_visits_label'] ?></label>
                                <input type="number" min="2" class="form-control" value="<?php echo $arrayDatosCadena['loyalty_min_visits'] ?>" name="loyalty_min_visits" required>
                            </div>
                        </div>

                        <div class="col-lg-8 col-lg-offset-2 mt2">
                            <div class="form-group">
                                <label for="ChainContactNumber"><?php echo $lang['Chain phone contact'] ?></label>
                                <input type="telefone" class="form-control" id="ChainContactNumber"
                                       value="<?php echo $arrayDatosCadena['numero'] ?>" name="ChainContactNumber"
                                       required>
                            </div>
                        </div>
                        <div class="col-lg-8 col-lg-offset-2 mt2">
                            <div class="form-group">
                                <label for="logoChain"><?php echo $lang['Chain logo'] ?></label>
                            </div>

                            <div class="img-holder">
                                <img 
                                    id="chainLogo"
                                    style="width:100%"
                                    class="img-thumbnail"
                                    src="<?php echo(!empty($arrayDatosCadena['logo']) ? imageSize('original', $arrayDatosCadena['logo']) : DIR_IMG . 'img-placeholder.jpg') ?>"
                                    alt="bg img" width="100"
                                >
                                <div id="fine-uploader"></div>
                            </div>

                            <div class="alert alert-info mt">
                                <div class="pull-left">
                                    <i class="fa fa-lightbulb-o pr"></i>
                                </div>
                                <p><?php echo $lang['Important advice about upload your logo'] ?> <a href="#"
                                                                                                     class="modal-link"
                                                                                                     data-toggle="modal"
                                                                                                     data-target="#logo-advice-modal"><?php echo $lang['advice link'] ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-8 col-lg-offset-2 mt2">
                            <div class="form-group">
                                 <label for="chain-color"><?php echo $lang['Chain color'] ?></label>
                                 <input id="chain-color" type="text"  value="<?php echo $arrayDatosCadena['background_color'] ?>" name="chainColor" class="form-control color-input" style="width: 6em;">
                            </div>
                        </div>
                        <div class="col-lg-8 col-lg-offset-2 mt2">
                            <label for="chainDescription"><?php echo $lang['Chain description'] ?></label>
                            <div class="white-module mb2 overflowHidden">
                                <div data-target="#hotelDesc" data-role="editor1-toolbar" class="btn-toolbar">
                                    <div class="btn-group">
                                        <a title="" data-toggle="dropdown" class="btn btn-default dropdown-toggle"
                                           data-original-title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b
                                                    class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a data-edit="fontSize 4"><font size="5">Huge</font></a></li>
                                            <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
                                            <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <a title="" data-edit="bold" class="btn btn-default"
                                           data-original-title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                                        <a title="" data-edit="italic" class="btn btn-default"
                                           data-original-title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                                        <a title="" data-edit="underline" class="btn btn-default"
                                           data-original-title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                                    </div>
                                    <div class="btn-group">
                                        <a title="" data-edit="insertunorderedlist" class="btn btn-default"
                                           data-original-title="Bullet list"><i class="fa fa-list-ul"></i></a>
                                        <a title="" data-edit="insertorderedlist" class="btn btn-default"
                                           data-original-title="Number list"><i class="fa fa-list-ol"></i></a>
                                    </div>
                                    <div class="btn-group">
                                        <a title="" data-edit="justifyleft" class="btn btn-info btn-default"
                                           data-original-title="Align Left (Ctrl/Cmd+L)"><i
                                                    class="fa fa-align-left"></i></a>
                                        <a title="" data-edit="justifycenter" class="btn btn-default"
                                           data-original-title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                                        <a title="" data-edit="justifyright" class="btn btn-default"
                                           data-original-title="Align Right (Ctrl/Cmd+R)"><i
                                                    class="fa fa-align-right"></i></a>
                                        <a title="" data-edit="justifyfull" class="btn btn-default"
                                           data-original-title="Justify (Ctrl/Cmd+J)"><i
                                                    class="fa fa-align-justify"></i></a>
                                    </div>
                                    <div class="btn-group">
                                        <a title="" data-toggle="dropdown" class="btn dropdown-toggle btn-default"
                                           data-original-title="Hyperlink"><i class="fa fa-link"></i></a>
                                        <div class="dropdown-menu input-append">
                                            <input type="text" data-edit="createLink" placeholder="URL" class="span2">
                                            <button type="button" class="btn">Add</button>
                                        </div>
                                        <a title="" data-edit="unlink" class="btn btn-default"
                                           data-original-title="Remove Hyperlink"><i class="fa fa-chain-broken"></i></a>
                                    </div>
                                    <input type="text" x-webkit-speech="" id="voiceBtn" data-edit="inserttext"
                                           style="display: none;">
                                </div>
                                <div class="condicionesTextArea"
                                     id="chainDescription"><?php echo $arrayDatosCadena['descripcion'] ?></div>
                            </div>
                            <div class="col-lg-5">
                                <input name="save-chain-details" type="submit"
                                       value="<?php echo $lang['Confirm changes'] ?>"
                                       class="btn btn-success btn-lg mt2 btn-block"/>
                            </div>
                        </div>
                    </div>
                    <textarea name="chainDescription" id="chainDescriptionText"
                              class="dnone"><?php echo $arrayDatosCadena['descripcion'] ?></textarea>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="newLangUrlModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo $lang['add new language to booking'] ?></h4>
                </div>
                <div class="modal-body">
                    <label><?php echo $lang['Lang'] ?></label>
                    <select class="form-control survey-comment" name="country" >
                        <?php foreach($arrayCountryLangs as $countryLang){
                            if($countryLang['locale']!='NULL'){6?>

                                <option value="<?php echo $countryLang['id']?>"><?php echo $countryLang['country']?></option>
                            <?php }
                        }?>
                    </select>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="<?php echo  $lang['Create'] ?>"
                           class="btn btn-success btn-lg mt2 btn-block" name="hotelAddUrlLang">
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-1" role="dialog" id="deleteLangUrlModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo $lang['warning'] ?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="countryLangId" name="country_lang_id"/>
                    <p><?php echo $lang['sure'] ?></p>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="<?php echo  $lang['delete'] ?>"
                           class="btn btn-success btn-lg mt2 btn-block" name="deleteLang">
                    <input type="button" value="<?php echo  $lang['Cancel'] ?>"
                           class="btn .btn-danger btn-lg mt2 btn-block"  data-dismiss="modal" aria-label="Close">
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/template" id="qq-template-s3">
    <div  class="qq-uploader-selector qq-uploader " >

        <div  class="btn btn-success btn-lg center-block qq-upload-button-selector" >Upload Logo</div>

        <div class="qq-upload-list-selector " style="display:none;" >
            <div class="image">
                <a class="preview-link" target="_blank">
                    <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale >
                </a>
            </div>
        </div>
    </div>
</script>
<?php include TEMPLATES . 'logo-advice-modal.php' ?>
<script>
    $(document).on("click", ".delete_url_btn", function () {
        var myBookId = $(this).data('countryLangId');
        $("#countryLangId").val( myBookId );
    });
</script>
<script src="<?php echo DIR_JS ?>bootstrap-wysiwyg.js"></script>
<script src="<?php echo DIR_JS ?>jquery.hotkeys.js"></script>
<script src="<?php echo DIR_JS ?>quitarHtmlTagsPaste.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
<script src="<?php echo DIR_JS ?>bootstrap-colorpicker.min.js" defer></script>
<link href="<?php echo DIR_CSS ?>bootstrap-colorpicker.min.css" rel="stylesheet" />
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.fine-uploader/fine-uploader-gallery.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.fine-uploader/s3.fine-uploader.core.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.jquery.fine-uploader/s3.jquery.fine-uploader.min.js"></script>
<script>
    jQuery(document).ready(function ($) {
        var hotel_guid = "<?php echo(array_get($_SESSION,'guid_logueado')) ?>";
        var chain_id = "<?php echo(array_get($_SESSION,'c_logueado')) ?>";
        var timestamp = new Date().getTime();
        
        function initToolbarBootstrapBindings() {
            $('.btn-toolbar a[title]').tooltip({container: 'body'});
            $('.dropdown-menu input').click(function () {
                return false;
            })
                .change(function () {
                    $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
                })
                .keydown('esc', function () {
                    this.value = '';
                    $(this).change();
                });
            $('#chain-color').colorpicker({
            format: 'hex',
            align: 'left'
            });
        };

        function showErrorAlert(reason, detail) {
            var msg = '';
            if (reason === 'unsupported-file-type') {
                msg = "Unsupported format " + detail;
            }
            else {
                console.log("error uploading file", reason, detail);
            }
            $('&lt;div class="alert"&gt; &lt;button type="button" class="close" data-dismiss="alert"&gt;&amp;times;&lt;/button&gt;' +
                '&lt;strong&gt;File upload error&lt;/strong&gt; ' + msg + ' &lt;/div&gt;').prependTo('#alerts');
        };

        initToolbarBootstrapBindings();

        $('#chainDescription').wysiwyg({toolbarSelector: '[data-role=editor1-toolbar]'});
        $('#chainDescription').bind("blur", function () {
            if (($('#chainDescription').text()) == "") {
                $('#chainDescription').empty();
            } else {
                var descContent = $('#chainDescription').html();
                $('#chainDescriptionText').html(descContent);
            }
        });
        $('#fine-uploader').fineUploaderS3({
            template: 'qq-template-s3',
            multiple: false,
            objectProperties: {
                'bucket': "<?php echo $_ENV['S3_IMAGES_BUCKET'] ?>",
                'key': function(fileId){
                    const filename = $('#fine-uploader').fineUploader('getName', fileId);
                    const ext = filename.substr(filename.lastIndexOf('.') + 1);
                    const isOriginal = /\((.+)\)/gi.exec(filename) ? false : true;
                    const file = isOriginal ? 'original' : /\((.+)\)/gi.exec(filename)[1] ;
                    const s3Path = 'brands/'+ hotel_guid +'/images/logo/' + file + '-' + timestamp + '.jpg';

                    if(isOriginal){
                        if(chain_id){
                            this.setUploadSuccessEndpoint('/lib/webservices/file-upload-success-ws.php', fileId);
                            this.setUploadSuccessParams({
                                'chain_id': chain_id,
                                'file_type': 'chainLogo'
                            }, fileId);
                        }
                    }

                    return s3Path
                }
            },
            scaling: {
                sendOriginal: true,
                hideScaled : true,
                sizes: [
                    {name: "small", maxSize: 300},
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
                itemLimit: 4
            },
            notAvailablePath: "<?php echo(DIR_IMG . 'placeholder.png') ?>",
            waitingPath: "<?php echo(DIR_IMG . 'placeholder.png') ?>",
            callbacks: {
                onValidate: function(file){
                    if(!chain_id || !hotel_guid){
                        alertify.error('There was an error, please refresh the page');
                        return false;
                    }
                    if(file.name.indexOf('(') !== -1){
                        alertify.error('No parenthesis allowed');
                        return false;
                    } else {
                        return file;
                    }
                },
                onError: function (id, name, errorReason, xhrOrXdr){
                    alertify.error(errorReason)
                },
                onProgress: (id, name, uploadedBytes, totalBytes)=>{
                    let percent = Math.round(uploadedBytes / totalBytes * 100);
                },
                onComplete: function(id, name, responseJSON, xhr){
                    if(name.indexOf('(') === -1){
                        this.drawThumbnail(id, document.getElementById("chainLogo"), 400);
                        alertify.success(`${name} successfully uploaded`)
                    }
                },
                onAllComplete: function(succeededIDs, failedIDs){
                    this.reset();
                }
            }
        });
    });
    var arrayText = new Array('#chainDescription');
    arrayText.forEach(quitarHtmlTagsPaste);
</script>