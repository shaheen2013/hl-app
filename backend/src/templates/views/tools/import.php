<?php //Miramos si esta definida la variable de control de index.php
// if (!defined('INDEXCONTROLVAL')) {
//     echo 'No direct access allowed.';
//     exit;
// }?>

<?php $this->layout('_layout::layout', [
    'title' => 'Import your users',
    'page_title' => 'Import your users',
    'page_icon' => $this->e($page_icon),
    'url' => $url,
])?>


<script type="text/template" id="qq-template-s3">
        <div class="qq-uploader-selector ui segment qq-uploader qq-gallery" >

            <!-- <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div> -->

            <!-- <div class="qq-upload-button-selector ui button large">
                <div>Upload a file</div>
            </div> -->

            <div class="ui center aligned basic segment">
                <div class="ui right labeled green icon button qq-upload-button-selector">
                    Upload
                    <i class="cloud upload icon"></i>
                </div>
                <div class="ui horizontal divider">
                    Or
                </div>
                <div class="qq-upload-drop-area-selector">
                    <h3 class="qq-upload-drop-area-text-selector">Drop files here</h3>
                </div>
            </div>

            <!-- <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Processing dropped files...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span> -->

            <ul class="qq-upload-list-selector ui cards" >
                <li class="card">
                    <div class="qq-progress-bar-container-selector ui top attached active progress">
                        <div class="bar"> </div>
                    </div>
                    <div class="content">
                        <i class="right floated close icon qq-upload-cancel-selector"></i>
                        <div class="header qq-upload-file-selector"></div>
                        <div class="meta qq-upload-size-selector"></div>

                        <div class="image">
                            <a class="preview-link" target="_blank">
                                <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale >
                            </a>
                        </div>
                        <!-- <div class="ui active centered loader"></div> -->
                    </div>
                    <div class="extra content">
                        <button class="left floated ui disabled button ">
                            <span class="qq-upload-status-text-selector"></span>
                        </button>
                        <div class="ui right floated small basic icon buttons">
                            <button class="ui button qq-upload-pause-selector"><i class="pause icon"></i></button>
                            <button class="ui button qq-upload-continue-selector"><i class="refresh icon"></i></button>
                            <button class="ui button qq-upload-delete-selector"><i class="bin icon"></i></button>
                            <button type="button ui" class="qq-upload-retry-selector"> Retry </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </script>

<?php include LANG . $_SESSION['userLang'] . '/import-users.php'?>


    <!-- <div class="alert alert-info">
        <div class="pull-left">
            <i class="fa fa-lightbulb-o fa-3x pr"></i>
        </div>
        <p>
        <?php if ($_SESSION['permisos']['LY'] == '1') {
    echo $InviteUsersLang['invite new guests text'];
} else {
    echo $InviteUsersLang['invite new guests text referral'];
}?>
        </p>
    </div> -->
    <div id="fine-uploader-s3"></div>



    <!-- <div class="ui one column center aligned grid">
        <div class="fourteen wide column">
            <div class="ui center aligned segment">
                <button class="ui large button">Upload CSV</button>
                <h5 class="ui horizontal header divider">
                    OR
                </h5>
                <button class="ui large disabled button">Drop file here</button>
            </div>
            <div class="ui center aligned segment">
                <?php echo $InviteUsersLang['invite new guests text']; ?>
            </div>
        </div>
    </div> -->


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
        var hotel_guid = '<?php echo ($_SESSION['hotel']['guid']) ?>';
        var hotel_id = <?php echo ($_SESSION['hotel']['id']) ?>;
        var files = $('#fine-uploader-s3').fineUploaderS3({
            debug: true,
            template: 'qq-template-s3',
            objectProperties: {
                'bucket': "<?php echo $_ENV['S3_BUCKET_NAME'] ?>",
                'key': function(fileId){
                    return 'brands/'+ hotel_guid +'/users/users-' + new Date().toISOString().slice(0,10); + '.csv'
                }
            },
            maxConnections: 10,
            request: {
                endpoint: "<?php echo $_ENV['S3_BUCKET_ENDPOINT'] ?>",
                accessKey: '<?php echo $_ENV['S3_ACCESS_KEY']; ?>'
            },
            signature: {
                endpoint: '/lib/webservices/file-upload-ws.php'
            },
            uploadSuccess: {
                // endpoint: '/lib/webservices/users-import-ws.php'
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
                allowedExtensions: ['csv'],
                itemLimit: 1
            },
            notAvailablePath: "<?php echo (DIR_IMG . 'placeholder.png') ?>",
            waitingPath: "<?php echo (DIR_IMG . 'placeholder.png') ?>",
            callbacks: {
                onSubmit: (fileId)=> validateFile(fileId) ,
                onError: (id, name, errorReason, xhrOrXdr) => alertify.error(errorReason) ,
                onProgress: (id, name, uploadedBytes, totalBytes)=>{
                    let percent = Math.round(uploadedBytes / totalBytes * 100);
                    $(`[qq-file-id=${id}] > .qq-progress-bar-container-selector`).progress('set percent', percent )
                },
                onComplete: (id, name, responseJSON, xhr) => alertify.success(`${name} successfully uploaded`),
                onAllComplete: (succeededIDs, failedIDs)=>{}
            },
            showMessage: (message)=>{ },
            // showConfirm: (message)=>{
            //     const promise = new qq.Promise();
            //     alertify.confirm(message, function(result) {
            //         if (result) {
            //             return promise.success(result);
            //         } else {
            //             return promise.failure();
            //         }
            //     });
            //     return promise;
            // },
        });
    </script>
<script type="text/javascript">
    //override defaults
    alertify.defaults.transition = "fade";
    alertify.defaults.theme.ok = "ui positive button";
    alertify.defaults.theme.cancel = "ui negative button";
</script>
<script>
    function validateFile(fileId){
        return new Promise((resolve, reject) => {
            var file = $('#fine-uploader-s3').fineUploader('getFile', fileId);
            var reader = new FileReader();
            reader.onload = function(event) {
                var allTextLines = event.target.result.split(/\r\n|\n/);
                var entries = allTextLines[0].split(',');
                var start = 0;
                // TODO: create the good list of column names necessary
                var restrictions = ['Nombre','Email','Lang', 'Birthday', 'Hotel_name', 'Chain_name'];
                if (validateRowFile(restrictions, entries)) {
                    resolve();
                } else {
                    reject();
                }
            }
            reader.readAsText(file)
        })
    }

    function validateRowFile(restrictions, entries){
        for(var i = 0, j=restrictions.length; i<j; i++) {
            if (restrictions.indexOf(entries[i]) == -1) {
                alert("<?php echo $InviteUsersLang["Error column does not exist"] ?>" + entries[i] + ". <?php echo $InviteUsersLang['Validation error'] ?>" );
                return false;
            }
        }
        return true;
    }

</script>
