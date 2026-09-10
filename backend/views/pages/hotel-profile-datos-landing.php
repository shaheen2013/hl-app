<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/hotel-profile-datos-landing.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
        <div class="utility-bar mb15">
            <div class="col-lg-12">
                <h1 class="pull-left"><i
                            class="fa fa-sign-in"></i> <?php echo $HotelProfileDatosLandingLang['Edit data for your landing'] ?>
                </h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-lg-offset-3">
            <div class="panel panel-default" >
                <div class="panel-body">
                        <img id="bg_image" style="width:100%" src="<?php echo !empty($background_img) ? imageSize('original', $background_img) : DIR_IMG . 'placeholder.png' ?>" alt="Login Portal Background Image " class="img-thumbnail text-center" >
                    <div id="fine-uploader"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo DIR_JS ?>hotelProfile4.min.js"></script>




<script type="text/template" id="qq-template-s3">
    <div  class="qq-uploader-selector qq-uploader " >

        <div  class="btn btn-success btn-lg center-block qq-upload-button-selector" ><?php echo $HotelProfileDatosLandingLang['Upload Image'] ?></div>

        <div class="qq-upload-list-selector " style="display:none;" >
            <div class="image">
                <a class="preview-link" target="_blank">
                    <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale >
                </a>
            </div>
        </div>
    </div>
</script>


<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.fine-uploader/fine-uploader-gallery.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.fine-uploader/s3.fine-uploader.core.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.jquery.fine-uploader/s3.jquery.fine-uploader.min.js"></script>

<script>
        var hotel_guid = "<?php echo(array_get($_SESSION,'guid_logueado')) ?>";
        var hotel_id = "<?php echo(array_get($_SESSION,'h_logueado')) ?>";
        var brand_id = "<?php echo(array_get($_SESSION, 'loggedBrandID')) ?>";

        $('#fine-uploader').fineUploaderS3({
            // debug: true,
            template: 'qq-template-s3',
            multiple: false,
            objectProperties: {
                'bucket': "<?php echo $_ENV['S3_IMAGES_BUCKET'] ?>",
                'key': function(fileId){
                    const filename = $('#fine-uploader').fineUploader('getName', fileId);
                    const ext = filename.substr(filename.lastIndexOf('.') + 1);
                    const isOriginal = /\((.+)\)/gi.exec(filename) ? false : true;
                    const file = isOriginal ? 'original' : /\((.+)\)/gi.exec(filename)[1] ;

                    const s3Path = 'brands/'+ hotel_guid +'/images/background/' + file + '.jpg'
                    if(isOriginal){
                        this.setUploadSuccessEndpoint('/lib/webservices/file-upload-success-ws.php', fileId);
                        this.setUploadSuccessParams({
                            'hotel_id': hotel_id,
                            'brand_id': brand_id,
                            'file_type': 'background'
                        }, fileId);
                    }
                    return s3Path
                }
            },
            scaling: {
                sendOriginal: true,
                hideScaled : true,
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
                onValidate: function(file){
                    if(!hotel_id || !hotel_guid){
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
                onError: (id, name, errorReason, xhrOrXdr) => alertify.error(errorReason) ,
                onProgress: (id, name, uploadedBytes, totalBytes)=>{
                    let percent = Math.round(uploadedBytes / totalBytes * 100);
                    // console.log(percent);
                    // $(`[qq-file-id=${id}] > .qq-progress-bar-container-selector`).progress('set percent', percent )
                },
                onComplete: function(id, name, responseJSON, xhr){
                    if(name.indexOf('(') === -1){
                        this.drawThumbnail(id, document.getElementById("bg_image"), 2000);
                        alertify.success(`${name} successfully uploaded`)
                    }
                },
                onAllComplete: function(succeededIDs, failedIDs){
                    this.reset();
                }
            }
        });
    </script>
<script type="text/javascript">
    //override defaults
    alertify.defaults.transition = "fade";
    alertify.defaults.theme.ok = "ui positive button";
    alertify.defaults.theme.cancel = "ui negative button";
</script>
