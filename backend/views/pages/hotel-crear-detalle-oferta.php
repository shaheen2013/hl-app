<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG .$_SESSION['userLang']. '/hotel-crear-detalle-oferta.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-gestion-ofertas-menu.php'; ?>
            <!--<?php include TEMPLATES . 'hotel-crear-oferta-detalle-menu.php'; ?>-->
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar mb15">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="rubies rubix3">rubies</i> <?php echo $HotelCrearDetalleOfertaLang['Campaign details'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="panel panel-default mb2 overflowHidden text-center">
				<form name="offer" method="post" >
							<div class="ofertaDetalle">
							</div>
							<div class="panel panel-default overflowHidden">
								<div class="panel-body">
									<h3 class="mt"><?php echo $HotelCrearDetalleOfertaLang['Campaign title'] ?></h3>
									<div class="text-left">
										<input required="true" type="text" value="<?php echo !empty($offer_language['nombre']) ? htmlentities($offer_language['nombre']) : '' ?>"  class="form-control input-lg mt2" id="offerName" name="name">
										<div class="col-lg-6">
											<div class="row">
												<h4><?php echo $HotelCrearDetalleOfertaLang['date from'] ?></h4>
												<input required="true" type="text" value="<?php echo (!empty($offer['inicio']) ? girarFecha($offer['inicio']): '') ?>"
												id="offerValidFrom" class="form-control" name="startDate" placeholder="<?php echo $HotelCrearDetalleOfertaLang['dd-mm-yyyy'] ?>">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="row">
												<?php if (!empty($_SESSION['offerMethod']) && $_SESSION['offerMethod'] != 'ref'){ ?>
												<h4><?php echo $HotelCrearDetalleOfertaLang['date to'] ?></h4>
												<input required="true" type="text" value="<?php echo (!empty($_SESSION['fin']) ? $_SESSION['fin'] : '') ?>" id="offerValidTill" placeholder="<?php echo $HotelCrearDetalleOfertaLang['dd-mm-yyyy'] ?>" class="form-control" name="endDate">
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default mt2 overflowHidden">
								<div class="panel-body">
									<h3 class="mt"><?php echo $HotelCrearDetalleOfertaLang['Campaign description'] ?></h3>
									<textarea required="true" name="description" class="condicionesTextArea" rows="10"><?php echo !empty($offer_language['descripcion']) ? $offer_language['descripcion'] : '' ?></textarea>
								</div>
							</div>
							<div class="panel panel-default mt2 mb2 overflowHidden">
								<div class="panel-body">
									<h3 class="mt"><?php echo $HotelCrearDetalleOfertaLang['Campaign conditions'] ?></h3>
									<textarea required="true" name="conditions" class="condicionesTextArea" rows="10"><?php echo !empty($offer_language['condiciones']) ? $offer_language['condiciones'] : '' ?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="offer-sidebar">
							<div class="btn-group-vertical dblock offerOptions mb2 ">
								<a <?php echo empty($can_publish_offer) || $offer['estado'] !== '0' ? "disabled" : '' ?> class="btn btn-primary btn-lg btnPublishOffer" title="<?php echo $HotelCrearOfertaDetalleMenuLang['publish campaign text'] ?>" data-container="body" data-toggle="popover" data-placement="left" data-content="<?php echo $HotelCrearOfertaDetalleMenuLang['publish campaign tooltip'] ?>"><i class="fa fa-rocket pr"></i> <?php echo(!empty($_SESSION['editada']) && $_SESSION['editada'] == '1' ? $HotelCrearOfertaDetalleMenuLang['update campaign'] : $HotelCrearOfertaDetalleMenuLang['publish campaign']) ?></a>
								<button class="btn btn-primary btn-lg" type="submit" ><i class="fa fa-floppy-o pr"></i> <?php echo (!empty($offer) && $offer['estado'] == '0' ? $HotelCrearOfertaDetalleMenuLang['save draft'] : $HotelCrearOfertaDetalleMenuLang['update']) ?></button>
								<a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/ref/" class="btn btn-lg btn-primary" title="<?php echo $HotelCrearOfertaDetalleMenuLang['Cancel edit']?>"><i class="fa fa-times-circle-o pr"></i><?php echo $HotelCrearOfertaDetalleMenuLang['Cancel edit']?></a>
								<a href="<?php echo $urlTree['hotel-eliminar-oferta'] ?>" class="btn btn-warning btn-lg"><i class="fa fa-trash-o pr"></i> <?php echo $HotelCrearOfertaDetalleMenuLang['back to campaign'] ?></a>
							</div>
							<div class="panel panel-default overflow-hidden">
								<div class="panel-body">
									<p><?php echo $HotelCrearDetalleOfertaLang['Actual language:'] ?><span id="langSected"><img src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $hotel_languages[$language_selected]['img'] ?>" alt="<?php echo $hotel_languages[$language_selected]['lang'] ?> flag" class="pl flag-icon"> <strong><?php echo $HotelCrearDetalleOfertaLang['offerLangs'][$hotel_languages[$language_selected]['lang']] ?></strong></span></p>
									<div class="dropdown">
										<button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" >
											<?php echo $HotelCrearDetalleOfertaLang['Edit reward in other language'] ?>
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" id="langList">
											<?php foreach($hotel_languages as $idioma){ ?>
												<li role="presentation" onclick="<?php echo empty($offer['id']) ? "changeLang('".$idioma['lang']."')" : "saveLang('".$idioma['lang']."')" ?>" class='langLi'> <a class="langLia"><span id="circle-<?php echo $idioma['lang'] ?>"><?php if (!empty($idioma['id_oferta'])) : ?> <i class="fa fa-check-circle-o verde lang-ok"></i><?php endif ?></span> <img src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $idioma['img'] ?>" alt="<?php echo $idioma['lang'] ?> flag" class="pl flag-icon"> <strong><?php echo $HotelCrearDetalleOfertaLang['offerLangs'][$idioma['lang']] ?></strong></a></li>

											<?php } ?>
										</ul>
										<input type="text" id="langSelected" name="lang" value="<?php echo $hotel_languages[$language_selected]['lang'] ?>" hidden />
									</div>
									<p class="mt text-center"><small><a href="<?php echo $urlTree['hotel-profile-langs'] ?>"><?php echo $HotelCrearDetalleOfertaLang['Select more languages'] ?></a></small></p>
								</div>
							</div>
                   <?php if (array_get($offer, 'id')){ ?>
                <div class="panel panel-default overflowHidden">
                  <div class="panel-body">
                    <img id="bg_image" style="width:100%" src="<?php echo !empty($offer['foto']) ? array_get($offer, 'foto') : DIR_IMG . 'img-placeholder.jpg' ?>" alt="Offer Image" class="img-thumbnail text-center" >
                    <div id="fine-uploader"></div>
                  </div>
                </div>
                <?php }
                else{ ?>
                    <div class="panel panel-default overflowHidden">
                        <div class="panel-body">
                            <div>
                                <p><?php echo $HotelCrearDetalleOfertaLang['need offer to upload image'] ?></p>
                            </div>
                        </div>
                    </div>
                            <?php }?>
							<div class="panel panel-default overflow-hidden">
								<div class="panel-body">
									<i data-toggle="tooltip" class="absoluteHelp hasTooltip fa fa-question-circle" data-placement="top" title="<?php echo $HotelCrearDetalleOfertaLang['If you have your booking engine integrated with hotelinking Api you should find this field very helpful'] ?>"></i>
									<h3><?php echo $HotelCrearDetalleOfertaLang['Booking engine code'] ?></h3>
									<input required="true" type="text" name="booking_engine_code" class="form-control" placeholder="<?php echo $HotelCrearDetalleOfertaLang['Insert code...'] ?>" value="<?php echo !empty($offer['booking_engine_code']) ? $offer['booking_engine_code'] : ''?>" id="bookingEngineCode">
								</div>
							</div>
							<?php if(!empty($_SESSION['h_logueado'])){ ?>
							<?php if (!empty($_SESSION['offerMethod']) && $_SESSION['offerMethod'] != 'ref'){ ?>
							<div class="panel panel-default">
								<div class="panel-body">
									<button type="button" class="btn btn-primary editBtnCost" data-toggle="modal" data-target="#offerCostAndQuota"><i class="fa fa-pencil-square-o"></i></button>
									<h3><?php echo $HotelCrearDetalleOfertaLang['Offer Cost'] ?></h3>
									<h2 class="offerDetailsCost"><strong id="pointsCost"><?php echo (!empty($_SESSION['puntos']) ? $_SESSION['puntos'] : 'XXX') ?></strong> <?php echo (!empty($_SESSION['offerMethod']) && $_SESSION['offerMethod'] == 'adq' ? '<i class="rubies rubix2 rubiesHL">rubies</i>' : '<i class="rubies rubix2">rubies</i>') ?></h2>
									<a href="#" class="btn btn-lg btn-default mt disabled mb"><?php echo $HotelCrearDetalleOfertaLang['Redeem this offer'] ?></a>
									<p ><strong><?php if (!empty ($_SESSION['cupo']) && $_SESSION['cupo'] > 0){
										?><span class="quotaText"><?php echo ''. $_SESSION['cupo'] . ' left'?></span>
										<?php }else{ ?>
										<span class="quotaText"> <?php echo $HotelCrearDetalleOfertaLang['unlimited quota']; ?> </span>
										<?php } ?></strong>
									</p>
								</div>
							</div>
							<?php }else{ ?>
							<div class="panel panel-default">
								<div class="panel-body">
									<h3><?php echo $HotelCrearDetalleOfertaLang['Referral offer'] ?></h3>
									<p class="text-left"><?php echo $HotelCrearDetalleOfertaLang['Referral offers are special offers that serves as reward for referrals and loyal guests. Those offers can be configured either as a '] ?><a href=" <?php echo $urlTree['referral-goals'] ?>" title="<?php echo $HotelCrearDetalleOfertaLang['goals'] ?>" target="_blank"><?php echo $HotelCrearDetalleOfertaLang['goals'] ?></a>
										<?php echo $HotelCrearDetalleOfertaLang['or'] ?>  <a href="<?php echo $urlTree['hotel-gestion-ofertas'] ?>/ref/" title="<?php echo $HotelCrearDetalleOfertaLang['as a new guest reward'] ?>" target="_blank"> <?php echo $HotelCrearDetalleOfertaLang['as a new guest reward'] ?></a>.
									</p>
								</div>
							</div>
							<?php } ?>
							<?php } ?>
							<div class="alert alert-info offerSidebarAlert requerimentsAlert"><i class="fa fa-exclamation-circle"></i> <?php echo $HotelCrearDetalleOfertaLang['You need to stay in the hotel a minimum of']; ?> <strong><span><?php echo ''. $_SESSION['requerimientos'];?></span> <?php echo $HotelCrearDetalleOfertaLang['nights'] ?></strong>
							</div>
						</div>
					</div>
					<div class="modal fade cropperModal" id="cropper-example-2-modal">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4>Edit and prepare your reward image</h4>
								</div>
								<div class="modal-body">
									<div id="cropper-example-2">
										<img src="" alt="Picture">
									</div>
								</div>
								<div class="modal-footer">
									<div class="btn-group pull-left cropperBtns">
										<button class="btn btn-default" data-method="zoom" data-option="0.05" type="button" title="Zoom In"><span class="fa fa-search-plus"></span></button>
										<button class="btn btn-default" data-method="zoom" data-option="-0.05" type="button" title="Zoom Out"><span class="fa fa-search-minus"></span></button>
										<button class="btn btn-default" data-method="rotate" data-option="-5" type="button" title="Rotate Left"><span class="fa fa-undo"></span></button>
										<button class="btn btn-default" data-method="rotate" data-option="5" type="button" title="Rotate Right"><span class="fa fa-repeat"></span></button>
									</div>
									<button class="btn btn-success cropperClose">Done with edit</button>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
				</div>
					<input name="id_offer" value="<?php echo !empty($offer['id']) ? $offer['id'] : '' ?>" hidden/>
					<input name="publish" value="<?php echo !empty($can_publish_offer) ? true : false ?>" hidden/>
				</form>
			</div>
		</div>
	</div>
</div>
					<textarea name="descripcion" id="descripcion"  class="dnone"></textarea>
					<textarea name="conditions" id="conditions"  class="dnone"></textarea>
					<input type="hidden" id="hotelClosed" value="<?php if(!empty($_SESSION['hotel_cerrado'])){ echo $_SESSION['hotel_cerrado']; }else{ echo '0';} ?>"/>
					<input type="hidden" id="fechaIncorrecta" value="<?php if(!empty($_SESSION['fecha_incorrecta'])){ echo $_SESSION['fecha_incorrecta']; }else{ echo '0';} ?>"/>
					<input type="hidden" id="offerLang" value="<?php if(!empty($_SESSION['lang'])){ echo $_SESSION['lang']; }else{ echo '0';} ?>"/>
					<?php
					//include TEMPLATES . 'createOfferVideoModal.php';
					// include TEMPLATES . 'offerCostAndQuotaModal.php';
					if(!empty($_SESSION['offertype']) && $_SESSION['offertype'] == 'des'){
						include TEMPLATES . 'offerDiscountModal.php';
					};
					// include TEMPLATES . 'offerSaveErrorModal.php';
					include TEMPLATES . 'cantPublishOfferModal.php';
					?>
					<!-- <script src="<?php echo DIR_JS . 'bootstrap-tour.min.js'?>"></script> -->
					<script>
						var sessionReq = "<?php echo (!empty ($_SESSION['requerimientos']) ? $_SESSION['requerimientos'] : '') ?>",
							offerMet = "<?php echo (!empty ($_SESSION['offerMethod']) ? $_SESSION['offerMethod'] : '') ?>",
							pubUrl = "<?php echo $urlTree['hotel-publicar-oferta']?>",
							pubSave = "<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>/?action=save";
					</script>
						<script src="<?php echo DIR_JS ?>cropper.min.js"></script>
						<!-- <script src="<?php echo DIR_JS ?>crearDetalleOferta.js"></script> -->
						<!-- <script src="<?php echo DIR_JS ?>bootstrap-wysiwyg.js"></script> -->
						<!-- <script src="<?php echo DIR_JS ?>jquery.hotkeys.js"></script> -->
						<!-- <script src="<?php echo DIR_JS ?>quitarHtmlTagsPaste.js"></script> -->



<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>

<script>

	$(document).ready(function(){
		$('#imgCropUploadForm').on('change', function(e){
			var img = e.target.files[0];
			uploadImg(img);
		});

		// Botón que cierra la modal, y devuelve una imagen BASE 64 / IE 9+
		$('.cropperClose').click(function(e) {
			e.preventDefault();
			$('#cropper-example-2-modal').modal('hide');

			//Imagen BASE 64
			var croppedImg = $image.cropper('getCroppedCanvas', {
				height: 486,
				width: 744
			}).toDataURL('image/jpeg');
			$('.cropHolder > img').attr('src', croppedImg).hide().fadeIn('slow');
			//Resetea el cropper, si no hace cosas raras.
			$image.cropper('destroy');
			console.log(croppedImg);
			$("input[name='croppedImg']").val(croppedImg);
		});
	});

//if the offer already exists then enable user to change language
//if not the user can only create offer in one language first
<?php if(!empty($offer['id'])) :?>
	function saveLang(lang){
		var url = window.location.origin + window.location.pathname + "?id="+"<?php echo $offer['id']?>"+"&lang="+lang;
		return window.location.href = url;
	};

<?php else :?>

	var offerLangs = <?php echo json_encode($HotelCrearDetalleOfertaLang['offerLangs']);?>;

	function changeLang(lang){

		$('#langSelected').val(lang);
		$('#langSected').html('<img src="<?php echo BASE_PATH . DIR_IMG;?>/flags/'+lang+'.png" alt="en flag" class="pl flag-icon"> <strong>'+offerLangs[lang]+'</strong>');

	}

<?php endif ?>


<?php if(!empty($can_publish_offer)) : ?>


	$('.btnPublishOffer').on('click', function(){
		var url = window.location.origin + window.location.pathname + "?id="+"<?php echo $offer['id']?>"+"&lang=<?php echo $_GET['lang']?>"+"&publish=true";
		return window.location.href = url;
	})

<?php endif ?>


</script>

<!-- DatePicker CSS & JS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>


<script>
        //listen to change date in datepicker
        //format date as UTC and only if end date > start date then reload ajax
        $('#offerValidFrom').datepicker({
            "format": 'dd-mm-yyyy'
        }).on(
            'changeDate', function(e){
				console.log(e)
            }
        );
</script>


<?php if (array_get($offer, 'id')){ ?>


<script type="text/template" id="qq-template-s3">
    <div  class="qq-uploader-selector qq-uploader " >

        <div  class="btn btn-success center-block qq-upload-button-selector" >Upload Image</div>

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
        var offer_id = "<?php echo $offer['id'] ?>";
        var timestamp = new Date().getTime();

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
                    const s3Path = 'offers/'+ offer_id + '/images/' + file + '-' + timestamp + '.' + ext
                    if(isOriginal){
                        this.setUploadSuccessEndpoint('/lib/webservices/file-upload-success-ws.php', fileId);
                        this.setUploadSuccessParams({
                            'hotel_id': hotel_id,
                            'offer_id': offer_id,
                            'file_type': 'offer'
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
                        this.drawThumbnail(id, document.getElementById("bg_image"), 600);
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


<?php } ?>
