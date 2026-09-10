<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/datamatch-users.php' ?>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

<style>
	.sorting_disabled::after {
		display: none;
	}
	/* .datamatch-border-separation-left {
		border-left: 1px solid #D7D7D7
	} */
	#modal_user_datamatch .modal-body span {
		font-size: 1.1em;
	}

	/* #datamatchUserDataTable_wrapper table thead tr th{
		min-width: 200px!important;
	} */

	.datamatch-user-export li{
		cursor: pointer!important;
	}

</style>

<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'datamatch-list-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="pull-left"><i class="fa fa-project-diagram"></i> <?php echo $DataMatchUsersLangs['Data Match Header']; ?> <?php echo $datamatch_id=='chain'?' Cadena':''; ?></h1>
					<div class="breadcrumbs pull-right">
						<ul>
							<?php include (TEMPLATES .'breadcrumbs.php'); ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
				<?php if(!$datamatch_activated){ ?><!-- IF DATAMATCH_ACTIVATED -->
					<div class="text-center mt2 container datamatch_not_activated">
						<i class="fa fa-project-diagram grisClaro fa-5x"></i>
						<h2><?php echo $DataMatchUsersLangs['datamatch not activated yet'] ?></h2>
					</div>
				<?php } else { ?><!-- ELSE IF DATAMATCH_ACTIVATED -->
					<div class="clearfix"></div>
					<div class="col-lg-12 mt relative datamatch-data" style="display: none;">
						<div class="row"><i class="pull-right"><?php echo $DataMatchUsersLangs['for more details in datamatch']?></i></div>
						<table id="datamatchTable" class="table cell-border compact order-column nowrap table-interactive">
							<thead>
								<tr>
									<th colspan="<?php echo count($columnsArrayDatamatch) ?>" class="text-center "><?php echo $DataMatchUsersLangs['Consolidate data'] ?></th>
								</tr>
								<tr>
									<!-- <?php foreach ($columnsArrayDatamatch as $key => $colDataMatchNames) { ?>
										<td <?php echo $key==$columnDivisorDatamatch? 'class="datamatch-border-separation-left"' : ''  ?> >
											<strong class=""><?php echo $DataMatchUsersLangs[$colDataMatchNames] ?></strong>
										</td>
									<?php } ?> -->
									<?php foreach ($columnsArrayDatamatch as $key => $colDataMatchNames) { ?>
										<td><strong class=""><?php echo $DataMatchUsersLangs[$colDataMatchNames] ?></strong></td>
									<?php } ?>
								</tr>
							</thead>
							<tfoot><tr></tr></tfoot>
						</table>
						
					</div>
				<?php } ?> <!-- END IF DATAMATCH_ACTIVATED -->
			</div>
		</div>

		<div class="modal fade" tabindex="-1" role="dialog" id="modal_user_datamatch">
			<div class="modal-dialog" role="document" style="width:80%;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title azul pageTitle text-center"><?php echo $DataMatchUsersLangs['Datamatch modal-header'] ?></h3>
						<!-- <h3 class="modal-title azul pageTitle"></h3> -->
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12" id="modal_user_basic_data_content"> </div>
							<hr>
							<div class="col-xs-12 mt2" id="modal_user_datamatch_content"> 
								<h4><strong><?php echo $DataMatchUsersLangs['Datamatch data'] ?></strong></h4>
								<div class="col-xs-12" id="modal_user_datamatch_personal_content">
									<h4><strong><?php echo $DataMatchUsersLangs['Personal data'] ?></strong></h4>
									
									<table id="datamatchUserDataTable" class="table cell-border compact order-column nowrap"></table>
								</div>

								<div class="col-xs-12 mt2" id="modal_user_datamatch_res_content"> 
									<h4><strong><?php echo $DataMatchUsersLangs['Reservation data'] ?></strong></h4>
									<table id="datamatchReservationDataTable" class="table cell-border compact order-column nowrap"></table>
								</div>						
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-xs-12" style="padding: 0px 30px;">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $DataMatchUsersLangs['Close'] ?></button>
							</div>
						</div>
					</div>
				</div><!-- END .modal-content -->
			</div><!-- END .modal-dialog -->
		</div><!-- END .modal -->

	</div>
</div>


<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>

<script>
	
	$(document).ready(function(){
		var datamatch_product_activated = '<?php echo $datamatch_activated;?>';
		var brand_id = <?php echo $brand_id ?>;
		var datamatch_id = <?php echo ($datamatch_id!='chain' ? $datamatch_id : 0);?>;
		var draw_count = 1;
		var indexColumnDivisor = parseInt('<?php echo $columnDivisorDatamatch;?>');
		var visiblesColumnsIndex = <?php echo json_encode($visiblesColumnsIndexs);?>;
		var translatedColumns = <?php echo json_encode($translatedColumns);?>;
		var schemaDatamatchModal = <?php echo json_encode($schemaDatamatchModal);?>;
		var draw_modal_count = 1;

		var searchText = '';
		var searchWait = 0;
		var searchWaitInterval;

		$('.datamatch-data').fadeIn();

		var table = $('#datamatchTable').on('preXhr.dt', function(e, settings, json){
            showLoader();
            }).DataTable( {
			"processing": true,
            "serverSide": true,
            "scrollX": true,
			"autoWidth": false,
            "searchDelay": 600,
			"ajax": {
                "url": '<?php echo(SECURE_BASE_PATH . LIB . 'webservices/datamatch-ws.php') ?>',
                "method": "POST",
                "data": function ( d ) {
					var info = $('#datamatchTable').DataTable().page.info();
					
					d.action = 'list_matched_users';
					d.brand_id = brand_id;
					d.datamatch_id = datamatch_id;
					d.draw_count = draw_count;
					d.page = info.page;
					d.n_per_page = info.length;
					d.searchText = searchText;
					return d;
				} 
            },
			"language": {
				"emptyTable": "<?php echo $DataMatchUsersLangs['No data available in table']?>"
			},
			"order": [],
            "columns": [
                <?php foreach ($columnsArrayDatamatch as $colDataMatchNames) { ?>
					{ 
                        "data": "<?php echo $colDataMatchNames ?>",
                        "searchable": <?php echo $colDataMatchNames==='nombre' || $colDataMatchNames==='first_name'? 'true' : 'false' ?>,
                        "orderable": <?php echo $colDataMatchNames==='check_in' || $colDataMatchNames==='check_out'? 'true' : 'false' ?>
                    },
				<?php } ?>
            ],
			"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
			"columnDefs": [
                
            	{"className": "text-center", "targets": "_all"},
				
                {"orderSequence" : ['desc', 'asc'], "targets": "_all"},
				
				{ "targets": visiblesColumnsIndex, "visible": true },
				{ "targets": "_all", "visible": false },

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
			"dom": "<'row dataTables_header'<'col-sm-2 dataTables_search'f><'col-sm-2 col-sm-offset-1'l><'col-sm-5'p><'col-sm-2 dataTables_buttons' >>"+
					"<'row'<'dataTables_custom_loader'><'col-sm-12 dataTables_main'rt>>"+
					"<'row dataTables_footer'<'col-sm-2'l><'col-sm-5 col-sm-offset-5'p>>"

		}).on('xhr', function(e, settings, json, xhr){
			hideLoader();
			draw_count++;
		});
		
		if(datamatch_id != 0){
			var buttons = `
			<div class="btn-group pull-right"> 
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo $DataMatchUsersLangs['Export'] ?> <span class="caret"></span> </button> 
				<ul class="dropdown-menu datamatch-user-export"> 
					<li><a class="export-query" ><?php echo $DataMatchUsersLangs['Export query'] ?></a></li> 
					<li><a class="export-all"><?php echo $DataMatchUsersLangs['Export all'] ?></a></li> 
				</ul> 
			</div>`
			
			$('.dataTables_buttons').prepend(buttons)

			// Send a call to integrations to get CSV
			var exportCsvFunction = function(type) {
				return function() {
					showLoader();
					// Get 
					$.ajax({
						url: `<?php echo INTEGRATIONS_URL_FRONT ?>brand/${brand_id}/datamatch/${datamatch_id}/users/csv?query=${type}&search=${searchText}`,
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
							alertify.error('Some server error, we are working on it!')
							// console.log(textStatus, errorThrown)
						}
					});
				};				
			};
			
			$('.export-query').on('click', exportCsvFunction('query'));
			$('.export-all').on('click', exportCsvFunction('all'));
		}

		$('.dataTables_custom_loader').prepend('<div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div>')
        showLoader();

		// Search input server side with delay
		$('.dataTables_filter input').unbind();
		$('.dataTables_filter input').bind('input', function(){
			searchText = $(this).val();
			searchWait = 0;
			if(!searchWaitInterval){
				searchWaitInterval = setInterval( function(){
					if(searchWait>=3){
						clearInterval(searchWaitInterval);
						searchWaitInterval = '';
						table.ajax.reload();
						searchWait = 0;
					}
					searchWait++;
				},200);
			} 
		});

		// On click in a row
		$('tbody').on('click', 'tr', function () {
			// Fetch the row datamatch
            var datamatchRow = table.row(this).data();
			
			$('#modal_user_basic_data_content').html('<h3><?php echo $DataMatchUsersLangs['Guest'] ?>: <strong><a href="/clients-profile/' + datamatchRow['user_id'] + '">' + datamatchRow['nombre'] + '</a></strong></h3> ');

			// Data table for user data 
			var tableUserData = $('#datamatchUserDataTable').DataTable( {
				"processing": true,
				"serverSide": false,
				"scrollX": true,
				"autoWidth": false,
				"destroy": true,
				"data": [datamatchRow],
				"columns": [
					<?php foreach ($columnsArrayDatamatch as $colDataMatchNames) { ?>
						{ 
							"data": "<?php echo $colDataMatchNames ?>",
							"title": "<?php echo $DataMatchUsersLangs[$colDataMatchNames] ?>",
							"searchable": false,
							"orderable": false
						},
					<?php } ?>
				],
				"columnDefs": [
					{"className": "dt-center text-center", "targets": "_all"},
					{ "targets": <?php echo json_encode($visiblesColumnsSchemaUserIndexs);?>, "visible": true },
					{ "targets": "_all", "visible": false },
					{
						"targets": "_all",
						"render": function(data, type, row){
							if (data!=='' && data!==null){
								return data;
							} else {
								return 'N/A';
							}
						}
					}
				],
				"dom": "<'row'<'col-sm-12 dataTables_main'rt>>",
				"initComplete": function(settings, json){
					// To resize columns with the greater item size
					var api = new $.fn.dataTable.Api( settings );
					var data = api.rows( {page:'current'} ).data();
					if(data){
						row = data[0];
						
						var width = data.map( function(row) {
							var nRow = [];
							Object.values(row).forEach( function(val) {
								if(val != null){
									nRow.push(val.toString().length);
								}
							});
							return Math.max.apply( null, nRow);
						});
					}
					$("#datamatchUserDataTable_wrapper").find("th").css({
						'min-width': width[0]*7 +'px'
					});
				}
			});

			// Data table for reservation data
			var tableReservationData = $('#datamatchReservationDataTable').DataTable( {
				"processing": true,
				"serverSide": false,
				"scrollX": true,
				"autoWidth": false,
				"destroy": true,
				"data": [datamatchRow],
				"columns": [
					<?php foreach ($columnsArrayDatamatch as $colDataMatchNames) { ?>
						{ 
							"data": "<?php echo $colDataMatchNames ?>",
							"title": "<?php echo $DataMatchUsersLangs[$colDataMatchNames] ?>",
							"searchable": false,
							"orderable": false
						},
					<?php } ?>
				],
				"columnDefs": [
					{"className": "dt-center text-center", "targets": "_all"},
					{ "targets": <?php echo json_encode($visiblesColumnsSchemaReservationIndexs);?>, "visible": true },
					{ "targets": "_all", "visible": false },
					{
						"targets": "_all",
						"render": function(data, type, row){
							if (data!=='' && data!==null){
								return data;
							} else {
								return 'N/A';
							}
						}
					}
				],
				"dom": "<'row'<'col-sm-12 dataTables_main'rt>>",
				"initComplete": function(settings, json){
					// To resize columns with the greater item size
					var api = new $.fn.dataTable.Api( settings );
					var data = api.rows( {page:'current'} ).data();
					if(data){
						row = data[0];
						
						var width = data.map( function(row) {
							var nRow = [];
							Object.values(row).forEach( function(val) {
								if(val != null){
									nRow.push(val.toString().length);
								}
							});
							return Math.max.apply( null, nRow);
						});
					}				
					$("#datamatchReservationDataTable_wrapper").find("th").css({
						'min-width': width[0]*7 +'px'
					});
				}
			});
			
			$('#modal_user_datamatch').modal('show');
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
	});

	
</script>