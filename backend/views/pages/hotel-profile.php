<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/hotel-profile.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>
        <div class="utility-bar mb15">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-building"></i> <?php echo $HotelProfileLang['Basic info'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <form role="form" class=" validation-form" action="<?php echo $urlTree['hotel-profile'] ?>" method="post" enctype="multipart/form-data">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php if (isset($errorMsg)) { ?>
                            <div class="alert alert-danger fade in text-center">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <p><?php echo $errorMsg; ?></p>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <h3><strong>1.</strong> <?php echo $HotelProfileLang['Localization info'] ?></h3>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="hotelName"><?php echo $HotelProfileLang['Hotel name'] ?></label>
                                    <input type="text" class="form-control" id="hotelName" name="hotelName" value="<?php echo $hotelName; ?>" placeholder="<?php echo $HotelProfileLang['Hotel name here...'] ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="hotelCity"><?php echo $HotelProfileLang['City'] ?></label>
                                    <input class="form-control" name="hotelCity" id="hotelCity" required value="<?php echo $arrayDatosHotel['city'] ?>">
                                    <input id="place_name" type="hidden" name="place_name" value="<?php echo $arrayDatosHotel['place_name'] ?>" />
                                    <input id="place_country" type="hidden" name="place_country" value="<?php echo $arrayDatosHotel['place_country'] ?>" />
                                    <input id="place_adm_area" type="hidden" name="place_adm_area" value="<?php echo $arrayDatosHotel['place_adm_area'] ?>" />
                                    <input id="lat" type="hidden" name="lat" value="<?php echo $arrayDatosHotel['lat'] ?>" />
                                    <input id="lng" type="hidden" name="lng" value="<?php echo $arrayDatosHotel['lng'] ?>" />
                                    <input id="place_id" type="hidden" name="place_id" value="<?php echo $arrayDatosHotel['place_id'] ?>" />
                                    <input id="country_name" type="hidden" name="country_name" value="<?php echo $arrayDatosHotel['country'] ?>" />
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="mt2">
                                        <?php echo $HotelProfileLang['Timezone'] ?>
                                        <i data-toggle="tooltip" class="hasTooltip fa fa-question-circle" data-placement="top" title="" data-original-title="<?php echo $HotelProfileLang['Timezone help'] ?>"></i>
                                    </label>
                                    <select id="timeZone" name="timeZone" class="selectpicker form-control" data-live-search="true" data-dropup-auto="false">
                                        <?php foreach ($time_zones_array as $time_zone) {
                                            var_dump($time_zone['id']); ?>

                                            <option value="<?php echo $time_zone['id'] ?>" <?php if ($time_zone['id'] == $hotel_time_zone_id) {
                                                                                                echo 'selected';
                                                                                            } ?>><?php echo $time_zone['gmt'] . '   ' . $time_zone['description'] . ' (' . $time_zone['time_zone'] . ')'  ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="hotelStreet"><?php echo $HotelProfileLang['Address'] ?></label>
                                    <input type="text" class="form-control" id="hotelStreet" name="hotelStreet" placeholder="<?php echo $HotelProfileLang['Hotel address here...'] ?>" value="<?php echo $arrayDatosHotel['street'] ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="hotelStars"><?php echo $HotelProfileLang['Stars'] ?></label>
                                    <select name="hotelStars" id="hotelStars" class="form-control">
                                        <option value="0">0</option>
                                        <option value="1" <?php echo ($arrayDatosHotel['estrellas'] == 1 ? 'selected' : '') ?>>
                                            1
                                        </option>
                                        <option value="2" <?php echo ($arrayDatosHotel['estrellas'] == 2 ? 'selected' : '') ?>>
                                            2
                                        </option>
                                        <option value="3" <?php echo ($arrayDatosHotel['estrellas'] == 3 ? 'selected' : '') ?>>
                                            3
                                        </option>
                                        <option value="4" <?php echo ($arrayDatosHotel['estrellas'] == 4 ? 'selected' : '') ?>>
                                            4
                                        </option>
                                        <option value="5" <?php echo ($arrayDatosHotel['estrellas'] == 5 ? 'selected' : '') ?>>
                                            5
                                        </option>
                                        <option value="6" <?php echo ($arrayDatosHotel['estrellas'] == 6 ? 'selected' : '') ?>>
                                            6
                                        </option>
                                        <option value="7" <?php echo ($arrayDatosHotel['estrellas'] == 7 ? 'selected' : '') ?>>
                                            7
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="hotelRooms">Rooms number</label>
                                    <input type="number" class="form-control" id="hotelRooms" name="hotelRooms" value="<?php echo $arrayDatosHotel['n_habitaciones'] ?>" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3><strong>2.</strong> <?php echo $HotelProfileLang['Digital info'] ?></h3>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="hotelWebsite"><?php echo $HotelProfileLang['Hotel web page'] ?></label>
                                    <input type="text" class="form-control" id="hotelWebsite" name="hotelWebsite" value="<?php echo $hotelWebsite; ?>" placeholder="<?php echo $HotelProfileLang['Hotel website here...'] ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="sending_email"><?php echo $HotelProfileLang['Email for transactional communications'] ?></label>
                                <input type="email" class="form-control" id="sending_email" name="sending_email" placeholder="<?php echo $HotelProfileLang['Email for customer communications...'] ?>" value="<?php echo $arrayDatosHotel['sending_email']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3><strong>3.</strong> <?php echo $HotelProfileLang['Currency info'] ?></h3>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="currency"><?php echo $HotelProfileLang['select currency'] ?></label>
                                    <select id="divisas" name="currency" class="form-control" required>
                                        <?php foreach ($selectDivisas as $key => $divisa) {
                                            if ($key == $arrayDatosHotel['moneda']) {
                                        ?>
                                                <option selected="selected" value="<?php echo $key ?>"><?php echo $divisa ?></option>
                                            <?php } else {
                                            ?>
                                                <option value="<?php echo $key ?>"><?php echo $divisa ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3><strong>4.</strong> <?php echo $HotelProfileLang['Your hotel logo'] ?></h3>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <img id="logoHotel" style="width:100%" src="<?php echo (!empty($arrayDatosHotel['logo']) ? imageSize('original', $arrayDatosHotel['logo']) : DIR_IMG . 'img-placeholder.jpg') ?>" alt="Hotel Logo" class="img-thumbnail">
                                        <div id="fine-uploader"></div>
                                    </div>
                                </div>

                                <div class="alert alert-info mt">
                                    <div class="pull-left">
                                        <i class="fa fa-lightbulb-o pr"></i>
                                    </div>
                                    <p><?php echo $HotelProfileLang['Quality logo advice'] ?> <a href="#" class="modal-link" data-toggle="modal" data-target="#logo-advice-modal"><?php echo $HotelProfileLang['click for advice'] ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default noPadding">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3><strong>5.</strong> <?php echo $HotelProfileLang['stay_configuration'] ?></h3>
                                <div class="input-group mt2">
                                    <label><?php echo $HotelProfileLang['stay_time_label'] ?></label>
                                    <input required type="number" min="1" max="60" class="form-control" name="stay_time" placeholder="Stay time" value="<?php echo $arrayDatosHotel['stay_time'] ?>">
                                </div>
                                <div class="input-group mt2">
                                    <label class="input-group">
                                        <input type="checkbox" name="chain_bypass" <?php echo (array_get($arrayDatosHotel, 'chain_bypass') == 1) ? "checked" : "" ?>>
                                        <?php echo $HotelProfileLang['chain bypass'] ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (array_get($arrayDatosHotel, 'activated') == 1 && array_get($_SESSION, 'c_logueado', null)) { ?>
                    <div class="panel panel-default noPadding">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3><strong>6.</strong><?php echo $HotelProfileLang['archive hotel'] ?></h3>
                                    <div class="input-group mt2">
                                        <p><?php echo $HotelProfileLang['explanation archive hotel'] ?> </p>
                                    </div>
                                    <div class="input-group mt2">
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#archiveHotel"><?php echo $HotelProfileLang['archive hotel'] ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-lg-5">
                    <div class="row">
                        <input type="submit" value="<?php echo $HotelProfileLang['Save changes button'] ?>" class="btn btn-success btn-lg btn-block" style="margin-bottom: 10px" name="hotelConfirmButton" onclick="sessionStorage.clear()">
                    </div>
                </div>
                <input type="hidden" name="verificado" value="<?php echo $arrayDatosHotel['verificado']; ?>" />
            </div>
        </form>
    </div>
</div>
<!-- Modal Chain -->
<div class="modal fade" id="archiveHotel" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $HotelProfileLang['archive hotel'] ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $HotelProfileLang['modal archive hotel'] ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <?php echo $HotelProfileLang['cancel'] ?></button>
                <a class="archive-hotel"><button type="button" class="btn btn-primary" data-dismiss="modal"> <?php echo $HotelProfileLang['accept'] ?></button></a>
            </div>
        </div>
    </div>
</div>
<?php include TEMPLATES . 'logo-advice-modal.php' ?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en&key=<?php echo GOOGLE_API_KEY ?>"></script>

<script src="<?php echo DIR_JS ?>googlePlacesCity.js"></script>

<script type="text/template" id="qq-template-s3">
    <div  class="qq-uploader-selector qq-uploader " >

        <div  class="btn btn-success btn-lg center-block qq-upload-button-selector">
            <?php echo $HotelProfileLang['upload logo'] ?>
        </div>

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
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" />
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.fine-uploader/fine-uploader-gallery.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.fine-uploader/s3.fine-uploader.core.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.15.5/s3.jquery.fine-uploader/s3.jquery.fine-uploader.min.js"></script>

<script>
    function initialize() {
        var options = {
            types: ['(cities)'],
        };
        var input = document.getElementById('hotelCity');
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            getTimeZone(latitude, longitude);
        });
    }

    function getTimeZone(latitude, longitude) {

        var timestamp = Math.floor(Date.now() / 1000);
        var url = 'https://maps.googleapis.com/maps/api/timezone/json?location=' + latitude + ',' + longitude + '&timestamp=' + timestamp + '&key=' + '<?php echo GOOGLE_API_KEY ?>';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                let timeZoneId = data.timeZoneId;
                selectTimeZone(timeZoneId);
            })
            .catch(error => {
                console.error('Error fetching time zone data:', error);
            });
    }

    function selectTimeZone(timeZoneId) {
        var select = $('#timeZone');

        // Iterates over all the options and compares the timeZoneId
        select.find('option').each(function(index, option) {
            var optionText = $(option).text();
            var timezoneMatch = optionText.match(/\((.*?)\)/); // Extrae el timezone que se encuentra entre paréntesis

            if (timezoneMatch && timezoneMatch.length > 1) {
                var timeZone = timezoneMatch[1];
                if (timeZone === timeZoneId) {
                    $(option).prop('selected', true);
                }
            }
        });
        select.selectpicker('refresh');
    }
    window.addEventListener('load', initialize);
</script>

<script>
    var hotel_guid = "<?php echo (array_get($_SESSION, 'guid_logueado')) ?>";
    var hotel_id = "<?php echo (array_get($_SESSION, 'h_logueado')) ?>";
    var chain_id = "<?php echo (array_get($_SESSION, 'c_logueado')) ?>";
    var brand_id = "<?php echo (array_get($_SESSION, 'loggedBrandID')) ?>";
    var timestamp = new Date().getTime();

    $('.archive-hotel').on('click', function(e) {
        archiveHotel()
    });

    function archiveHotel() {
        var datos = {
            "hotel_id": hotel_id,
            "chain_id": chain_id,
            "brand_id": brand_id,
            "status": 0
        };

        $.ajax({
            "url": '<?php echo (SECURE_BASE_PATH . LIB . 'webservices/archive-hotel.php') ?>',
            "data": datos,
            type: 'POST',
            success: function(response) {
                if (response != null) {
                    // We clean sessionStorage after 'archiveHotel' in order to show correct hotel list
                    sessionStorage.clear();
                    if (response.active_hotel_count > 1) { //If there are more hotels in the chain, redirect
                        location.href = '<?php echo $url['dir1'] ?>/?change=' + response.id
                    } else { //If last or independent hotel, reload the page
                        location.reload();
                    }
                } else {
                    alertify.error("<?php echo $HotelProfileLang["archived maxim"] ?>");
                }
            },
            error: function(response) {
                alertify.error("<?php echo $HotelProfileLang["delete error"] ?>");
            }
        });
    };
    $('#fine-uploader').fineUploaderS3({
        // debug: true,
        template: 'qq-template-s3',
        multiple: false,
        objectProperties: {
            'bucket': "<?php echo $_ENV['S3_IMAGES_BUCKET'] ?>",
            'key': function(fileId) {
                const filename = $('#fine-uploader').fineUploader('getName', fileId);
                const ext = filename.substr(filename.lastIndexOf('.') + 1);
                const isOriginal = /\((.+)\)/gi.exec(filename) ? false : true;
                const file = isOriginal ? 'original' : /\((.+)\)/gi.exec(filename)[1];
                const s3Path = 'brands/' + hotel_guid + '/images/logo/' + file + '-' + timestamp + '.jpg';

                if (isOriginal) {
                    if (hotel_id) {
                        this.setUploadSuccessEndpoint('/lib/webservices/file-upload-success-ws.php', fileId);
                        this.setUploadSuccessParams({
                            'hotel_id': hotel_id,
                            'brand_id': brand_id,
                            'hotel_guid': hotel_id,
                            'file_type': 'logo'
                        }, fileId);
                    }
                }

                return s3Path
            }
        },
        scaling: {
            sendOriginal: true,
            hideScaled: true,
            sizes: [{
                    name: "small",
                    maxSize: 300
                },
                {
                    name: "medium",
                    maxSize: 800
                },
                {
                    name: "large",
                    maxSize: 1200
                }
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
        notAvailablePath: "<?php echo (DIR_IMG . 'placeholder.png') ?>",
        waitingPath: "<?php echo (DIR_IMG . 'placeholder.png') ?>",
        callbacks: {
            onValidate: function(file) {
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
            onError: function(id, name, errorReason, xhrOrXdr) {
                alertify.error(errorReason)
            },
            onProgress: (id, name, uploadedBytes, totalBytes) => {
                let percent = Math.round(uploadedBytes / totalBytes * 100);
            },
            onComplete: function(id, name, responseJSON, xhr) {
                if (name.indexOf('(') === -1) {
                    this.drawThumbnail(id, document.getElementById("logoHotel"), 400);
                    this.drawThumbnail(id, document.getElementById("hotel-logo-sidebar"), 100);
                    alertify.success(`${name} successfully uploaded`)
                }
            },
            onAllComplete: function(succeededIDs, failedIDs) {
                this.reset();
            }
        }
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>