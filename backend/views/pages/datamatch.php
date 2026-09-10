<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {echo 'No direct access allowed.';exit;}?>

<?php include LANG . $_SESSION['userLang'] . '/datamatch.php'?>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

<style>
	.sorting_disabled::after {
		display: none;
	}

	.datamatch-column-list-item {
		padding: 2px;
		margin: 10px 5px;
		border: 1px solid #45b7af;
		border-radius: 5px;
	}

	.datamatch-failed td{
		cursor: not-allowed!important;
		background-color: transparent!important;
	}

	.datamatch-started td{
		cursor: progress!important;
		background-color: transparent!important;
	}

	.spin-icon {
		-webkit-animation:spin 2s linear infinite;
		-moz-animation:spin 2s linear infinite;
		animation:spin 2s linear infinite;
	}
	@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
	@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
	@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
</style>

<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php';?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'datamatch-list-menu.php'?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="pull-left"><i class="fa fa-project-diagram"></i> <?php echo $DataMatchLangs['Data Match Header'] ?></h1>
					<div class="breadcrumbs pull-right">
						<ul>
							<?php include TEMPLATES . 'breadcrumbs.php';?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
				<?php if (!$datamatch_activated) {?><!-- IF DATAMATCH_ACTIVATED -->
					<div class="text-center mt2 container datamatch_not_activated">
						<i class="fa fa-project-diagram grisClaro fa-5x"></i>
						<h2><?php echo $DataMatchLangs['datamatch not activated yet'] ?></h2>
					</div>
				<?php } else {?><!-- ELSE IF DATAMATCH_ACTIVATED -->
					<div class="clearfix"></div>
					<div class="col-lg-12 mt relative datamatch-data" style="display: none;">
					<a class='btn btn-success' id='newDatamatch'><?php echo $DataMatchLangs['New Datamatch'] ?></a>
					<a class='btn btn-success pull-right' id='exportCsvDatamatch'><?php echo $DataMatchLangs['Export'] ?></a>
						<table id="datamatchTable" class="table cell-border compact order-column nowrap table-interactive">
							<thead>
								<tr>
									<?php foreach ($columnsArrayDatamatch as $key => $colDataMatchNames) {?>
										<td><strong class=""><?php echo $DataMatchLangs[$colDataMatchNames] ?></strong></td>
									<?php }?>
								</tr>
							</thead>
							<tfoot><tr></tr></tfoot>
						</table>

					</div>
				<?php }?> <!-- END IF DATAMATCH_ACTIVATED -->
			</div>
		</div>

		<div class="modal fade" tabindex="-1" role="dialog" id="modal_new_datamatch">
			<div class="modal-dialog" role="document" style="width:80%;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title azul pageTitle"><?php echo $DataMatchLangs['New Datamatch'] ?></h3>
					</div>
					<div class="modal-body">
						<form id="qq-form">
							<div class="row">
								<div class="col-lg-6 form-group">
									<label for="date_from"><?php echo $DataMatchLangs['From Date'] ?></label>
									<input type="date" class="form-control" id="date_from" name="date_from" required="true">
								</div>

							</div>
							<div class="row">
								<div class="col-lg-6 form-group">
									<label for="date_to"><?php echo $DataMatchLangs['To Date'] ?></label>
									<input type="date" class="form-control" id="date_to" name="date_to" required="true">
								</div>
							</div>
							<div class="row">
								<div id="fine-uploader"></div>
							</div>
							<div class="row">
								<div class="col-lg-12 form-group">
									<p>Remember the CSV file has to have the following headers : </p>
									<?php foreach($csvColumns as $columnName) {  ?>
										<i class="datamatch-column-list-item"><?php echo $columnName ?></i>
									<?php }  ?>

									<!-- <p style="overflow-wrap: break-word;"><?php echo (json_encode($csvColumns)) ?></p> -->
								</div>
							</div>
							<!-- <button type="submit">Submit</button> -->
						<form>
					</div>
					<div class="modal-footer">
						<!-- <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $DataMatchLangs['Close'] ?></button> -->
						<button type="submit" class="btn btn-success">Submit</button>
					</div>
				</div><!-- END .modal-content -->
			</div><!-- END .modal-dialog -->
		</div><!-- END .modal -->
	</div>
</div>

<script type="text/template" id="qq-template-s3">
    <div  class="qq-uploader-selector qq-uploader " >

		<!-- <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
			<span class="qq-upload-drop-area-text-selector"></span>
		</div> -->

        <!-- <input type="file" class="qq-upload-button-selector" >Upload Hotel CSV</input> -->
		<div class="col-lg-12 form-group">
			<label>CSV File</label>
			<div class="btn btn-default qq-upload-button-selector" disabled >Choose</div>
		</div>

		<!-- <div class="progress qq-progress-bar-container-selector">
			<div class="bar qq-progress-bar-selector" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div> -->

		<ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
			<li>
				<!-- <div class="progress qq-progress-bar-container-selector">
					<div class="bar qq-progress-bar-selector" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div> -->
				<span class="qq-upload-spinner-selector qq-upload-spinner"></span>
				<img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
				<span class="qq-upload-file-selector qq-upload-file"></span>
				<!-- <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span> -->
				<!-- <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text"> -->
				<span class="qq-upload-size-selector qq-upload-size"></span>
				<button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
				<!-- <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
				<button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button> -->
				<span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
			</li>
		</ul>


        <!-- <div class="qq-upload-list-selector "> </div> -->
        </div>
    </div>
</script>

<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.16.2/s3.fine-uploader/fine-uploader-gallery.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.16.2/s3.fine-uploader/s3.fine-uploader.core.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/file-uploader/5.16.2/s3.jquery.fine-uploader/s3.jquery.fine-uploader.min.js"></script>


<script>
	// Function to redirect to datamatch-users matcheados
	var showMatchedList =  function (id) {
		if (!window.location.origin) {
			window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
		}

		redirectUrl = window.location.origin + "/datamatch-users/" + id;
		location.href = redirectUrl;
	};

	$(document).ready(function(){
		var datamatch_product_activated = '<?php echo $datamatch_activated; ?>';
		var brand_id = <?php echo $brand_id ?>;
		var brand_uid = '<?php echo $brand_uid ?>';
		var by_chain = 0;
		var draw_count = 1;
		var statusDatamatchIndex = <?php echo json_encode($columnStatusDatamatch); ?>;
		var importedDatamatchIndex = <?php echo json_encode($columnImportedDatamatch); ?>;
		var visiblesColumnsIndex = <?php echo json_encode($visiblesColumnsIndexs); ?>;
		var translatedColumns = <?php echo json_encode($translatedColumns); ?>;

		$('#newDatamatch').on('click', function(){
			$('#modal_new_datamatch').modal('show');
		})

		$('.datamatch-data').fadeIn();


		// Send a call to integrations to get CSV
		$('#exportCsvDatamatch').on('click', function(e){
            showLoader();
			// Get
			$.ajax({
                url: `<?php echo INTEGRATIONS_URL_FRONT ?>brand/${brand_id}/datamatch/chain/users/csv`,
				headers: {
					'Authorization': "<?php echo INTEGRATIONS_TOKEN ?>",
					'Content-Type': "text/csv; charset=UTF-8",
				},
				type: 'GET',
                success: function(response, status, xhr) {
                    if (response){
						hideLoader();
						if (response.errors) {
							alertify.error(response.message)
							return;
						}

						// Get filename from server
						var filename = 'hotelinking_datamatch'; // Default value if dont have any name in response
						var disposition = xhr.getResponseHeader('Content-Disposition');
						if (disposition && disposition.indexOf('attachment') !== -1) {
							var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
							var matches = filenameRegex.exec(disposition);
							if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
						}

						// Get content type
						var type = xhr.getResponseHeader('Content-Type');

						// Set download
                        var blob = new Blob(["\ufeff" + response], { type: type });
                        if (navigator.msSaveBlob) { // IE 10+
                            navigator.msSaveBlob(blob, filename);
                        } else {
                            var link = document.createElement("a");
                            if (link.download !== undefined) { // feature detection
                                // Browsers that support HTML5 download attribute
                                var url = URL.createObjectURL(blob);
                                link.setAttribute("href", url);
                                link.setAttribute("download", filename);
                                link.style.visibility = 'hidden';
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            }
						}
						alertify.success('Well downloaded!');
                    } else {
                        location.reload();
                    }
                },
				error: function (jqxhr, textStatus, errorThrown) {
					hideLoader();
					alert('Some server error, we are working on it!');
					// console.log(textStatus, errorThrown)
				}
            });
		})

		var table = $('#datamatchTable').on('preXhr.dt', function(e, settings, json){
            showLoader();
            }).DataTable( {
			"processing": true,
            "serverSide": true,
            "scrollX": true,
			"autoWidth": false,
            "searchDelay": 600,
			"ajax": {
                "url": '<?php echo (SECURE_BASE_PATH . LIB . 'webservices/datamatch-ws.php') ?>',
                "method": "POST",
                "data": function ( d ) {
					var info = $('#datamatchTable').DataTable().page.info();

					d.action = 'list_datamatch';
					d.brand_id = brand_id;
					d.draw_count = draw_count;
					d.page = info.page;
					d.n_per_page = info.length;

					// console.log(orderList);

					return d;
				}
            },
			"language": {
				"emptyTable": "<?php echo $DataMatchLangs['No data available in table'] ?>"
			},
			"order": [],
            "columns": [
                <?php foreach ($columnsArrayDatamatch as $colDataMatchNames) {?>
					{
                        "data": "<?php echo $colDataMatchNames ?>",
						"searchable": false,
						"orderable": <?php echo $colDataMatchNames === 'date_from' || $colDataMatchNames === 'date_to' ? 'true' : 'false' ?>
                    },
				<?php }?>
            ],
			"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
			"columnDefs": [

            	{"className": "dt-center text-center", "targets": "_all"},

                {"orderSequence" : ['desc', 'asc'], "targets": "_all"},

				{ "targets": visiblesColumnsIndex, "visible": true },
				{ "targets": "_all", "visible": false },


				{
                    "targets": statusDatamatchIndex,
                    "render": function(data, type, row){
                        if (data == 'succeeded') {
                            return '<strong class="verde"><?php echo $DataMatchLangs['Done'] ?></strong>';
                        } else if (data == 'failed') {
                            return '<i class="naranja">Error</i>';
                        } else {
                            return '<i class="azul2"><?php echo $DataMatchLangs['In progress'] ?></i>';
                        }
                    }
                },
				{
                    "targets": importedDatamatchIndex,
                    "render": function(data, type, row){
                        if (data == 1){
							if(row['status'] == 'succeeded'){
								return '<i><?php echo $DataMatchLangs['Click me to see more data'] ?></i>';
							} else {
								return '<i></i>';
							}
                        } else {
							if(row['status'] == 'failed'){
								return '<i><?php echo $DataMatchLangs['We are working on it'] ?></i>';
							} else {
								return '<i></i>';
							}
						}
                    }
                },
				{
                    "targets": "_all",
                    "render": function(data, type, row){
                        if (data !== ''){
                            return data;
                        } else {
                            return 'N/A';
                        }
                    }
                }
            ],
			"dom": "<'row dataTables_header'>"+
					"<'row'<'dataTables_custom_loader'><'col-sm-12 dataTables_main'rt>>"+
					"<'row dataTables_footer'<'col-sm-2'l><'col-sm-offset-5 col-sm-5'p>>"


		}).on('xhr', function(e, settings, json, xhr){
			hideLoader();
			draw_count++;
		});

		$('.dataTables_custom_loader').prepend('<div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div>')
        showLoader();


		// On click in a row
		$('tbody').on('click', 'tr', function () {
			var data = table.row( this ).data();

			if(data) {
				if(data['imported'] == 1 && data['status'] == 'succeeded'){
					if (!window.location.origin) {
						window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
					}

					redirectUrl = window.location.origin + "/datamatch-users/" + data["id"];
					location.href = redirectUrl;
				}else if(data['status'] == 'succeeded'){
					// $result_confim = confirm('Quieres iniciar el importe de ese datamatch?');
				}else{
					// alert('No se puede hacer el importe todavia');
				}
			}
		});

		// On mouseover in a row
		$('tbody').on('mouseover', 'tr', function () {
			var data = table.row( this ).data();

			if(data) {
				if(data['imported'] != 1 || data['status'] != 'succeeded'){
					$(this).addClass("not-succeded");
					if(data['status'] == 'failed'){
						$(this).addClass("datamatch-failed");
					} else {
						$(this).addClass("datamatch-started");
					}
				}
			}
		});

		// On mouseover in a row
		$('tbody').on('mouseout', 'tr', function () {
			$(this).removeClass("not-succeded datamatch-failed datamatch-started");
		});

		//manually show Loader and hide table
        function showLoader(){
            $('.dataTables_custom_loader').fadeIn();
            $('.dataTables_main').hide();
        }

        //manually hide Loader and show table
        function hideLoader(){
            $('.dataTables_custom_loader').hide();
            $('.dataTables_main').fadeIn();
        }

	$('#qq-form').on('change', 'input', function(){
		var incorrect = $('#qq-form').find('input:not(:valid):not([type=file])').length;
		if(incorrect < 1){
			let from = $('#date_from').val();
			let to = $('#date_to').val();
			if(from && to && from > to){
				alert('not cool')
				$(".qq-upload-button-selector").attr("disabled", true);
			} else {
				$(".qq-upload-button-selector").removeAttr("disabled");
			}
		}
	});


    const uploader = $('#fine-uploader').fineUploaderS3({
            template: 'qq-template-s3',
			multiple: false,
			autoUpload: false,
            objectProperties: {
                'bucket': "<?php echo S3_DATAMATCH_BUCKET ?>",
                'key': function (fileId) {
                    const filename = $('#fine-uploader').fineUploader('getName', fileId);
					const ext = filename.substr(filename.lastIndexOf('.') + 1);
					const from = $('#date_from').val();
					const to = $('#date_to').val();
                    const s3Path = 'brands/' + brand_id + '/users/' + from + '.csv';
                    return s3Path
                }
            },
            maxConnections: 1,
            request: {
                endpoint: "<?php echo S3_DATAMATCH_BUCKET_ENDPOINT ?>",
                accessKey: '<?php echo AWS['credentials']['key']; ?>'
            },
            signature: {
                endpoint: '/lib/webservices/datamatch-csv-upload-ws.php'
            },
            chunking: {
                enabled: false,
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
					// let percent = Math.round(uploadedBytes / totalBytes * 100);
					// console.log(percent);
				},
                onAllComplete: (succeededIDs, failedIDs)=>{
					if(succeededIDs.length > 0){
						const date_from = $('#date_from').val();
						const date_to = $('#date_to').val();
						const path = $("#fine-uploader").fineUploader('getKey', succeededIDs[0]);
						const brand_csv = path;
						createDatamatch(brand_id, date_from, date_to, brand_csv);
					}
					return true;
				}
            }
		});
	});

	$(document).ready(function(){
		$('#qq-form').bootstrapValidator({
			fields:{
				'date_to': {
					validators: {
						notEmpty: {
							message: 'The date is required and cannot be empty'
						},
					}
				},
				'qqfile': {
					validators: {
						notEmpty: {
							message: 'The date is required and cannot be empty'
						}
					}
				}
			}
		})
	})


    function validateFile(fileId){
        return new Promise((resolve, reject) => {
			var file = $('#fine-uploader').fineUploader('getFile', fileId);
            var reader = new FileReader();
            reader.onload = function(event) {
                var allTextLines = event.target.result.split(/\r\n|\n/);
                var entries = allTextLines[0].split(',');
				var start = 0;
				let restrictions = <?php echo (json_encode(DATAMATCH_COLUMNS)) ?>;
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
		let errors = [];
        for(var i = 0, j=entries.length; i<j; i++) {
            if (restrictions.indexOf(entries[i]) == -1) {
				errors.push(entries[i]);
            }
		}
		if (errors.length > 1){
			alert(`The following columns do not match our schema : ` + JSON.stringify(errors));
			return false;
		}
        return true;
    }

	// function onClickImportDatamatch(target, brand_id, id){
	// 	target.html('<i class="fa fa-circle-o-notch verde spin-icon"></i>');
	// 	$.ajax({
	// 		url: '/lib/webservices/datamatch-ws.php',
	// 		data: {
	// 			'action': 'import_datamatch',
	// 			'brand_id': brand_id,
	// 			'datamatch_id': id
	// 			},
	// 		type: 'POST',
	// 		success: function(response) {
	// 			console.log(response);
	// 			if(response == true){
	// 				alertify.success('Initiated import of data... Wait some minutes until we importe everthing for you');
	// 			}else{
	// 				alertify.error('Sorry there was an error... try again in a couple of minutes');
	// 				target.html('<span class="naranja">Error</span>');
	// 			}

	// 		},
	// 	});
	// }

	function createDatamatch(brand_id, date_from, date_to, brand_csv){
		if (brand_id && date_from && date_to && brand_csv){
			return $.ajax({
				url: '/lib/webservices/datamatch-ws.php',
				data: {
					'action': 'create_datamatch',
					'brand_id': brand_id,
					'brand_csv': brand_csv,
					'date_from': date_from,
					'date_to': date_to
					},
				type: 'POST',
				success: function(response) {
					if(response && response.errors){
						$('#modal_new_datamatch').modal('hide');
						alertify.error(response.message);
					} else {
						//reload the page to show the new datamatch created
						location.reload();
					}
				},
				error: function(error){
					$('#modal_new_datamatch').modal('hide');
					alertify.error('Sorry there was an error');
				}
			})
		}
	}

</script>
