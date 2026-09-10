<?php //Miramos si esta definida la variable de control de index.php
use GuzzleHttp\Psr7\Query;

if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<?php include LANG . $_SESSION['userLang'] . '/satisfaction-list.php' ?>
<!-- DatePicker CSS & JS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
			<?php include TEMPLATES . 'satisfaction-list-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<div class="breadcrumbs pull-right">
					<ul>

						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
				<h1 class="pull-left"><i class="fa fa-smile-o"></i> <?php echo $satisfactionLang['Satisfaction surveys'].' ('.$averageScore.")" ?></h1><h3 class="pull-right">Total: <?php echo $totalSatisfactions; ?></h3>
			</div>

		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
                <div class="row">
                    <form name="search-survey" class="form-inline">
                        <div class="col-xs-12 mb2">
							<div class="pull-right">
								<div class="btn-group">
									<p>&nbsp</p> 
									<label id="print-selected" class='btn btn-default'>
										<?php echo $satisfactionLang['Print selected'] ?>
									</label>
									<?php if ($_SESSION['permisos']['trans_comments'] ?? 0): ?>
										<div class="dropdown btn-group">
											<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<?php echo $satisfactionLang['Translate selected'] ?>
												<span class="caret"></span>
											</button>
											
											<ul id="translate-target-language" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
												<?php foreach ($availableLangs as $lang): ?>
													<li><a class="export-query translate-selected" data-value="<?php echo $lang['lang'] ?>"><?php echo array_get($satisfactionLang, 'langs.'.$lang['lang']); ?></a></li>                                        
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endif; ?>
								</div>
								<div class='btn-group' id="chain-hotel" data-toggle='buttons'>
									<div class="dropdown">
										<div style="width:100%;">
											<p><?php echo $satisfactionLang['exportButtonLabel'] ?> </p>
											<select id="export-type" name="export-type" form="export_form" class="form-control selectpicker">
												<option class="export-query" value="export-query" data-brand='<?php echo $brandID ?>' data-params='<?php print_r(htmlspecialchars(json_encode($satisfactionParams))) ?>'> 
													<?php echo $satisfactionLang['Search Results'] ?> 
												</option> 
												<option class="export-all" value="export-all"> 
													<?php echo $satisfactionLang['All Clients'] ?> 
												</option> 
											</select>
											<button type="button" class="btn btn-default col-xs-offset-0" data-toggle="modal" data-target="#inputEmails"> 
												<option class="subscribed" value="subscribed"> 
													<?php echo $satisfactionLang['exportButton'] ?> 
												</option> 
											</button>
										</div>
									</div>
								</div>
							</div>
                        </div>

                        <input type="submit" hidden value="Submit">
                        <div class="col-md-4 col-xs-12">
							<p> <?php echo $satisfactionLang['searchLabel'] ?> </p> 
                            <div class="input-group mb2">
                                <input type="text" class="form-control" name="search" id="search-survey-searcher" placeholder="<?php echo $satisfactionLang['searchLabel'] ?>" value="<?php echo $urlSearch ?>"/>
                                <span id="search-icon" class="input-group-addon"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
						<div id="show_clients" class="btn-group col-md-2 col-xs-12" data-toggle="buttons"> 
							<p> <?php echo $satisfactionLang['showClientsLabel'] ?>  </p> 
							<select id="select_filter_brand" name="chain-hotel" class="form-control selectpicker" style="width:100%"> 
								<option class="hotel" value="hotel"> 
									<?php echo $satisfactionLang['By Hotel'] ?> 
								</option>
								<?php if (isChain()) {?>
									<option class="chain" value="chain" <?php echo array_get($_GET, 'chain-hotel') == 'chain' ? 'selected' : '' ?>> 
										<?php echo $satisfactionLang['By Chain'] ?> 
									</option> 
								<?php } ?>
							</select>
						</div>
                        <div class="col-md-4 col-xs-12">
							<p> <?php echo $satisfactionLang['dateLabel'] ?> </p> 
                            <div class="input-daterange input-group col-md-4 col-xs-12" id="datepicker" style="width:100%">
                                <span class="input-group-addon"><?php echo $satisfactionLang['Dates'] ?></span>
                                <input type="text" autocomplete="off" class=" form-control" name="start" placeholder="<?php echo $satisfactionLang['Start'] ?>" value="<?php echo $urlDataSearchStart ?>"/>
                                <span class="input-group-addon"><?php echo $satisfactionLang['To'] ?></span>
                                <input type="text" autocomplete="off" class=" form-control" name="end"  placeholder="<?php echo $satisfactionLang['End'] ?>" value="<?php echo $urlDataSearchEnd ?>"/>
                            </div>
                        </div>
						<div class="col-md-2 col-xs-12">
							<p>&nbsp</p> 
							<div class="input-daterange input-group col-md-2 col-xs-12" id="datepicker" style="width:100%">
								<button class="btn btn-default btn-primary dataTables_reset col-md-2 col-sm-12" onclick="resetDatapicker()" style="width: 100%;"><?php echo $satisfactionLang['Reset'] ?></button>
							</div>
						</div>
                    </form>
                </div>
                <div class="clearfix"></div>
			<?php if(!empty($satisfactions)){ ?>
				<div class="table-responsive mt relative">
					<table class="table table-striped">
						<tr class="table-header">
							<td></td>
							<?php if(array_get($_SESSION, 'permisos.widget')) { ?>
								<td>
									<span><?php echo $satisfactionLang["favorite"] ?></span> <a href="<?php echo $currentPageUrl . $symbol; ?>ord=favorite" title="sort"><i class="fa fa-sort pl"></i></a>
								</td>
							<?php } ?>
                            <td>
                                <span><?php echo $satisfactionLang["assisted"] ?></span> <a href="<?php echo $currentPageUrl . $symbol ?>ord=has_been_seen" title="sort"><i class="fa fa-sort pl"></i></a>
                            </td>
							<td>
								<span><?php echo $satisfactionLang['Nombre'] ?></span> <a href="<?php echo $currentPageUrl . $symbol ?>ord=nombre" title="sort"><i class="fa fa-sort pl"></i></a>
							</td>
							<?php if(!empty($_SESSION['c_logueado'])) { ?>
							<td>
								<span><?php echo $satisfactionLang['Hotel'] ?></span> <a href="<?php echo $currentPageUrl . $symbol ?>ord=hotelName" title="sort"><i class="fa fa-sort pl"></i></a>
							</td>
							<?php } ?>
							<?php if(isset($_SESSION['permisos']) && isset($_SESSION['permisos']['require_room_num']) && $_SESSION['permisos']['require_room_num'] == 1){ ?>
							<td>
								<span><?php echo $satisfactionLang['id_room'] ?></span> <a href="<?php echo $currentPageUrl . $symbol ?>ord=id_room" title="sort"><i class="fa fa-sort pl"></i></a>
							</td>
							<?php }?>
                            <td>
                                <span><?php echo $satisfactionLang['country']; ?></span> <a href="<?php echo $currentPageUrl . $symbol ?>ord=location" title="sort"><i class="fa fa-sort pl"></i></a>
                            </td>
                            <td>
								<span><?php echo $satisfactionLang['Puntuación'] ?></span> <a href="<?php echo $currentPageUrl . $symbol ?>ord=puntuacion" title="sort"><i class="fa fa-sort pl"></i></a>
							</td>
                            <td>
								<span><?php echo $satisfactionLang['Comentario'] ?></span>
							</td>
                            <td>
								<span><?php echo $satisfactionLang['Response_Time'] ?></span>
							</td>
							<td>
								<span><?php echo $satisfactionLang['Fecha'] ?></span> <a href="<?php echo $currentPageUrl . $symbol ?>ord=fecha_update" title="sort"><i class="fa fa-sort pl"></i></a>
							</td>
							<td>
								<span><?php echo $satisfactionLang['check_in'] ?></span>
							</td>
							<td>
								<span><?php echo $satisfactionLang['check_out'] ?></span>
							</td>
							<td>
								<span><?php echo $satisfactionLang['res_channel'] ?></span>
							</td>
							<td>
								<span><?php echo $satisfactionLang['Actions'] ?></span>
							</td>
						</tr>
						<?php foreach($satisfactions as $satisfaction){ ?>
						<tr class="table-row" id='satisfaction-id-<?php echo $satisfaction->id; ?>'>
							<td><i class="fa hamburguer-btn"><input type="checkbox" class="select-comment" data-satisfaction-id="<?php echo $satisfaction->id; ?>"></i></td>
							<?php if(array_get($_SESSION, 'permisos.widget')) { ?>
								<td>
									<i onclick="hasBeenPromoted(this);"  data-satisfaction="<?php echo data_get($satisfaction, 'id'); ?>" class="fa hamburguer-btn <?php echo data_get($satisfaction, 'favorite')?'fa-star verde':'fa-star-o naranja' ?>"></i>
								</td>
							<?php } ?>
                            <td>
                                <i onclick="hasBeenSeen(this);" data-seen="<?php echo !data_get($satisfaction, 'hasBeenSeen') ?>" data-satisfaction="<?php echo data_get($satisfaction, 'id'); ?>" class="fa hamburguer-btn <?php echo data_get($satisfaction, 'hasBeenSeen')?'fa-circle verde':'fa-circle naranja' ?>"></i>
                            </td>
							<td class="satisfaction-author">
								<?php echo data_get($satisfaction, 'user.name') ?>
							</td>
							<?php if(!empty($_SESSION['c_logueado'])) { ?>
							<td>
								<?php echo data_get($satisfaction, 'hotel.name') ?>
							</td>
							<?php } ?>
							<?php if(isset($_SESSION['permisos']) && isset($_SESSION['permisos']['require_room_num']) && $_SESSION['permisos']['require_room_num'] == 1){ ?>
							<td>
                                <span class="survey-room">
								    <?php echo data_get($satisfaction, 'roomID', 'N/A') ?>
                                </span>
							</td>
							<?php }?>
                            <td>
                                <?php echo data_get($satisfaction, 'user.country') ?>
                            </td>
                            <td>
								<span class="survey-score <?php echo data_get($satisfaction, 'score') < $satisfactionConfig->puntMin ?
									'negativeValoration' :
									''
								?>">
								    <?php echo data_get($satisfaction, 'score') ?>
                                </span>
                            </td>
							<td class="satisfaction-list-comment">
								<span class="original-language"><?php echo data_get($satisfaction, 'comment') ?></span>
                                <span class="translated"></span>
							</td>
							<td>
								<?php if (strtotime(data_get($satisfaction, 'sendDate')) < strtotime('2000-00-00 00:00:00')) {
									echo "N/A";
								}else {
									$timeBetweenResponse = dateDiff(data_get($satisfaction, 'sendDate'), data_get($satisfaction, 'answered'));
									echo $timeBetweenResponse->format('%a '.$satisfactionLang['days'].' '.$satisfactionLang['and'].' %h '.$satisfactionLang['hours']);
								}
								?>
							</td>
                            <td>
                                <span class="survey-answer-date">
								<?php echo data_get($satisfaction, 'answered'); ?>
                                </span>
							</td>
							<td class="satisfaction-author">
								<?php echo data_get($satisfaction, 'visits.0.checkIn') ?>
							</td>
							<td class="satisfaction-author">
								<?php echo data_get($satisfaction, 'visits.0.checkOut') ?>
							</td>
							<td class="satisfaction-author">
								<?php echo data_get($satisfaction, 'visits.0.resChannel') ?>
							</td>
							<td>
								<div class="btn-group-vertical pull-right">
									<a href="mailto:<?php echo data_get($satisfaction, 'user.email') ?>?body=<?php echo rawurlencode(data_get($satisfaction, 'comment')) ?>" class="btn btn-default hasTooltip btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo $satisfactionLang['Enviar un email'] ?>"><i class="fa fa-envelope-o"></i></a>
									<a class="btn btn-default hasTooltip btn-sm"
										title="<?php echo $satisfactionLang['incidents']?>"
										data-lang='<?php echo $_SESSION['userLang'] ?>'
										data-toggle="modal"
										data-target="#modalIncidents"
										data-incidents-reviewed="<?php echo data_get($satisfaction, 'incidents_reviewed'); ?>"
										data-satisfaction-id="<?php echo data_get($satisfaction, 'userSurveyId'); ?>"
										data-incidents="<?php echo print_r(htmlspecialchars(json_encode(data_get($satisfaction, 'incidents')), ENT_QUOTES)) ?>"
										>
										<i data-seen="<?php echo !data_get($satisfaction, 'hasBeenSeen') ?>" data-satisfaction="<?php echo data_get($satisfaction, 'id'); ?>" class="fa <?php echo data_get($satisfaction, 'incidents_reviewed')?'fa-exclamation-triangle verde' : 'fa-exclamation-triangle naranja' ?>"></i>
									</a>
									<?php if (array_get($_SESSION, 'superadmin')) { ?>
									<a class="btn btn-default hasTooltip btn-sm"
									title="<?php echo $satisfactionLang['delete_survey']?>"
									data-lang='<?php echo $_SESSION['userLang'] ?>'
									data-toggle="modal"
									data-target="#modalDeleteSurvey"
									data-satisfaction-id="<?php echo data_get($satisfaction, 'id'); ?>"
									data-user-survey-id="<?php echo data_get($satisfaction, 'userSurveyId');?>">
									<i class="fa fa-trash"></i>
									</a>
									<?php } ?>
									<?php if (data_get($satisfaction, 'reviewSend') != 1) { ?>
									<!-- color trip advisor rgb(108, 163, 70) -->
										<a class="btn btn-tripadvisor hasTooltip btn-sm"
											title="<?php echo $satisfactionLang['Send comment to tripadvisor']; ?>"
											data-toggle="modal"
											data-toggle="tooltip"
											data-placement="left"
											data-target="#modalConfirmTripadvisor"
											data-user-id="<?php echo data_get($satisfaction, 'user.id'); ?>"
											data-hotel-id="<?php echo data_get($satisfaction, 'hotel.id'); ?>"
											data-score="<?php echo data_get($satisfaction, 'score'); ?>"
											data-comment="<?php echo trim(data_get($satisfaction, 'comment')); ?>"
											data-satisfaction-id="<?php echo data_get($satisfaction, 'id'); ?>">
											<i class="fa fa-tripadvisor" aria-hidden="true"></i>
										</a>
									<?php
									}
									if (data_get($satisfaction, 'satisfactionAnswers')) {
									?>
										<a id="satAnsw" class="btn btn-default hasTooltip btn-sm"
											title="<?php echo $satisfactionLang['detailed review'] ?>"
											data-toggle="modal"
											data-toggle="tooltip"
											data-placement='left'
											data-target="#modalDetailedReview"
											data-questions='<?php print_r(htmlspecialchars(json_encode($questions), ENT_QUOTES)) ?>'
											data-answers='<?php print_r(htmlspecialchars(json_encode(data_get($satisfaction, 'satisfactionAnswers')), ENT_QUOTES)) ?>'
											data-lang='<?php echo $_SESSION['userLang'] ?>'>
											<i class="fa fa-info" aria-hidden="true"></i>
										</a>
										
									<?php } ?> 
								</div>
							</td>
						</tr>
						<?php } ?>
					</table>
					<div class="pull-left">
						<div style="display: inline-block;" data-toggle="buttons"> 
							<span class="btn-group"><?php echo $satisfactionLang['show']; ?> &nbsp</span>
							<select style="width:auto" id="per_page" name="per_page" class="btn-group form-control"> 
								<option <?php echo $itemsPage == "10" ? 'selected="selected"': '' ?> value="10">10</option>
								<option <?php echo $itemsPage == "50" ? 'selected="selected"': '' ?> value="50">50</option>
								<option <?php echo $itemsPage == "100" ? 'selected="selected"': '' ?> value="100">100</option>
							</select>
							<span class="btn-group">&nbsp <?php echo $satisfactionLang['results']; ?></span>
						</div>
					</div>

                    <ul class="pull-right mt0 pagination">
                        <?php
                        $queryString['pag'] = '%d';
                        $currentUrl = $pathUrl . '?' . Query::build($queryString, false);

                        echo (is_string($paginate['prev'])) ?
                            '<li><span class="disabled disabledAnchor icon item paginateItem">&laquo;</span></li>' :
                            '<li><a href="' . sprintf($currentUrl, $paginate['prev']) . '" class="icon item paginateItem">&laquo;</a></li>';

                        foreach($paginate['pages'] as $curr_page) {
                            echo (is_string($curr_page)) ?
                                '<li><span class="active active-page item paginateItem">' . $curr_page . '</span></li>' :
                                '<li><a href="' . sprintf($currentUrl, $curr_page) . '" class="item paginateItem">' . $curr_page . '</a></li>';
                        }

                        echo (is_string($paginate['next'])) ?
                            '<li><span class="disabled disabledAnchor icon item paginateItem">&raquo;</span></li>' :
                            '<li><a href="' . sprintf($currentUrl, $paginate['next']) . '" class="icon item paginateItem">&raquo;</a></li>';
                        ?>
                    </ul>

				</div>
			<?php } else { ?>
					<div class="text-center mt2 container no-data-msg">
						<i class="fa fa-envelope grisClaro fa-5x"></i>
						<?php if($_SESSION['permisos']['satisfaction'] != 1) { ?>
							<h2><?php echo $satisfactionLang['There is no satisfaction surveys at this moment']; ?></h2>
							<h4><?php echo $satisfactionLang['Sending automate satisfaction emails will solve the problem']; ?></h4>
						<?php } else { ?>
							<h2><?php echo $satisfactionLang['No surveys found']; ?></h2> 
						<?php } ?>
					</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>

<!-- Modal Confirmation send mail Tripadvsor -->
<div class="modal fade" id="modalConfirmTripadvisor" role="dialog">
	<div class="modal-dialog">
      <!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo $satisfactionLang['modal_body'] ?></h4>
			</div>
			<div class="modal-body">
				<p><?php echo $satisfactionLang['modal_title'] ?></p>
			</div>
			<div class="modal-footer">
				<button id="sendNowModalConfirmTripadvisor" type="button" class="btn btn-secondary" data-dismiss="modal" onclick="forceReview(modalReview, 'now')">
					<?php echo $satisfactionLang['modal_button_yes_now'] ?>
				</button>
				<?php if($sendingDays > 1){ ?>
					<button id="sendLaterModalConfirmTripadvisor" type="button" class="btn btn-primary" data-dismiss="modal" onclick="forceReview(modalReview, 'default')">
						<?php echo $satisfactionLang['modal_button_send_later'].' '.$sendingDays.' '.$satisfactionLang['days'] ?>
					</button>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<!-- Modal Detailed Review -->
<div class="modal fade" id="modalDetailedReview" role="dialog">
	<div class="modal-dialog modal-lg">
      <!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="detailedReviewModalTitle" class="modal-title"><?php echo $satisfactionLang['detailed review'] ?></h4>
			</div>
			<div id="satisfactionAnswers" class="modal-body">
				
			</div>
		</div>
	</div>
</div>

<!-- Modal Incidents Comments -->
<div class="modal fade" id="modalIncidents" role="dialog">
	<div class="modal-dialog modal-lg">
      <!-- Modal Body -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick="closeModalIncidents()"></button>
				<h4 id="detailedReviewModalTitle" class="modal-title"> <?php echo $satisfactionLang['incidents_comments'] ?></h4>
			</div>
			<div id="incidents-body" class="modal-body">
				
			</div>
			<div id="new_incident_body" class="modal-body" style="visibility: hidden;"> 
				<form id="addNewCommentForm" method="post">
					<label for="new_comment"> Nuevo comentario: </label>
					<input id="user_survey_id_hidden" name="user_survey_id_hidden" type="hidden">
					<input id="new_comment" name="new_comment" class="form-control" type="text" required="true" placeholder="Introduzca el nuevo comentario...">
				</form>
			</div>
			<div class="panel-body">
                <div class="addLang-group" onclick="addNewComment()">
					<label>
						<a id="buttonAddIncident"><i class="clicable fa fa-plus-square-o"></i></a>
					</label>
				</div>
			</div>
			<div class="modal-footer">
					<div class="pull-left">
						<form id="setIncidentsReviewed" method="post">
							<input id="incidents_reviewed" name="incidents_reviewed" type="checkbox"> 
							<label for="incidents_reviewed"><?php echo $satisfactionLang['reviewed'] ?></label>
							<input name="user_survey_id_hidden" type="hidden">
							<input name="update_user_survey" type="hidden">
						</form>
					</div>
				<input name="create_incident" type="hidden" value="create_incident">
				<input id="send_create_incident_form" type="submit" value="<?php echo $satisfactionLang['create_comment'] ?>" disabled class="btn btn-primary" onclick="submitForm(event,'addNewCommentForm')">
				<button type="button" class="btn btn-danger" onclick="closeModalIncidents()"> <?php echo $satisfactionLang['close'] ?> </button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Delete Survey -->
<div class="modal fade" id="modalDeleteSurvey" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"></button>
				<h4 class="modal-title"><?php echo $satisfactionLang['delete_survey'] ?></h4>
			</div>
			<div class="panel-body">
					<h4><?php echo $satisfactionLang['confirm_delete'] ?></h4>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="getSatisfactionId" value="">
				<input type="hidden" name="userSurveyIdToDelete" value="">
				<input name="delete_survey" id="delete_survey" type="submit" value="<?php echo $satisfactionLang['delete'] ?>" class="btn btn-primary" onclick="deleteSurvey()">
				<button type="button" class="btn btn-danger" onclick="closeDeleteSurveyModal()"> <?php echo $satisfactionLang['close'] ?> </button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Confirmation Without Saving -->
<div class="modal fade" id="modalSavingConfirmation" role="dialog">
	<div class="modal-dialog modal-lg">
      <!-- Modal Body -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick="closeConfirmationModal()"></button>
				<h4 class="modal-title"> <?php echo $satisfactionLang['warning'] ?> </h4>
			</div>

			<div class="modal-body"> 
				<p> <?php echo $satisfactionLang['confirmation_saving_text'] ?> </p>
			</div>

			<div class="modal-footer">
				<input value="<?php echo $satisfactionLang['close_and_save'] ?>" class="btn btn-danger" onclick="submitForm(event,'addNewCommentForm')">
				<button type="button" class="btn btn-primary" onclick="closeConfirmationModal()"> <?php echo $satisfactionLang['close_without_saving'] ?> </button>
			</div>
		</div>
	</div>
</div>

<div id="inputEmails" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><?php echo $satisfactionLang['csvModalHeader'] ?> </h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<p><?php echo $satisfactionLang['csvModalText'] ?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<textarea id="emails_textarea" rows="5" name="email" method="post" form="export_form" class="form-control" placeholder="email@example.org..."></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" id="modalExportButton" value="<?php echo $satisfactionLang['exportButton'] ?>"
					class="btn btn-primary" />
				<button type="button" class="btn btn-default" data-dismiss="modal"> <?php echo $satisfactionLang['close'] ?> </button>
			</div>
		</div>  
	</div>   
</div>


<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script src="<?php echo DIR_JS ?>print-comments.js"></script>
<script>

	var modalReview = {};

	$(document).ready(function(){
		$('#search-icon').on('click', function() {
			submitSearchSurveyForm();
		});

        $('#search-date-icon').on('click', function() {
            submitSearchSurveyForm();
        });

        PrintComments.init();
	});

	$('#modalConfirmTripadvisor').on('show.bs.modal', function (event){

		modalReview.button = event.relatedTarget;
		modalReview.data = event.relatedTarget.dataset;

	});

	$('#modalDeleteSurvey').on('show.bs.modal', function (event){
		$("input[name='userSurveyIdToDelete']").val(event.relatedTarget.dataset.userSurveyId);
		$("input[name='getSatisfactionId']").val(event.relatedTarget.dataset.satisfactionId);
	});

	$('#modalExportButton').on('click', function(e){
		var params = $("#export-type").val() == "export-all" ? 
			"{}" :
			<?php print_r(json_encode($satisfactionParams)) ?>;
			
		$.ajax({
			"url": '<?php echo(SECURE_BASE_PATH . LIB . 'webservices/satisfaction-list-ws.php') ?>',
			"data": Object.assign(params, {action: "exportCSV", emails: $('#emails_textarea').val(), brandId: <?php echo $brandID ?> }),
			type: 'POST',
			success: function(response) {
				$.ajax({
					url: "/lib/webservices/msgFeedback.php",
					data: "nError=2039&lang=<?php echo $_SESSION['userLang'] ?>",
					type: 'POST',
					success: function(output) {
						$('#inputEmails').modal('toggle');
						data = $.parseJSON(output);
						showError(data, 5000);
					}
				});
			}, 
			error: function(response) {
				$.ajax({
					url: "/lib/webservices/msgFeedback.php",
					data: "nError=4077&lang=<?php echo $_SESSION['userLang'] ?>",
					type: 'POST',
					success: function(output) {
						data = $.parseJSON(output);
						showError(data, 5000);
					}
				});
			}
		});
	});

	$('#modalDetailedReview').on('show.bs.modal', function (event) {
		$(".customized-response").remove();
		$(".customized-response-time").remove();
		$("#satisfactionAnswers").empty();

		var customizedSatisfactionAnswersResponses = JSON.parse(event.relatedTarget.dataset.answers);
		var customizedQuestions = JSON.parse(event.relatedTarget.dataset.questions);
		var lang = event.relatedTarget.dataset.lang || 'en';

		// Filter questions to include only those with corresponding answers
		var filteredQuestions = [];
		customizedQuestions.forEach(function(customQuestion) {
			var questionsWithAnswers = customQuestion.questions.filter(function(question) {
				return customizedSatisfactionAnswersResponses.some(function(answer) {
					return answer.id === question.id;
				});
			});

			if (questionsWithAnswers.length > 0) {
				filteredQuestions.push({
					category_text: customQuestion.category_text,
					questions: questionsWithAnswers
				});
			}
		});

		var answers = "";
		filteredQuestions.forEach(function(customfilterQuestions) {
			var categoryText = customfilterQuestions.category_text[lang];

			answers += '<div class="table-responsive mt relative">' +
							'<table class="table table-striped">' +
								'<tr class="table-header">' +
									'<td>' +
										'<span>' + categoryText + '</span>' +
									'</td>' +
								'</tr>'

			customfilterQuestions.questions.forEach(function(question) {
				var questionText = question.question_text[lang] || question.question_text.en;

				var customizedAnswer = customizedSatisfactionAnswersResponses.filter(function(obj) {
					return obj.id == question.id;
				})[0];

				var answer = customizedAnswer ?
					(customizedAnswer.answer || customizedAnswer.answer === 0 ?
						typeof customizedAnswer.answer === "number" ? 
							customizedAnswer.answer :
							customizedAnswer.answer[lang] :
						"<?php echo $satisfactionLang['NR/DK'] ?>") :
					"<?php echo $satisfactionLang['N/A'] ?>"

				var answerColor = answer < <?php echo $satisfactionConfig->puntMin ?? 10 ?> ? 'negativeValoration' : '';

				var comment = customizedAnswer && customizedAnswer.comment ?
					customizedAnswer.comment :
					(answer == "<?php echo $satisfactionLang['NR/DK'] ?>" ?
						"<?php echo $satisfactionLang['NR/DK'] ?>" :
						"<?php echo $satisfactionLang['N/A'] ?>" );

				answer = question.type == "Open Question" ? comment : answer;
				answerColor = question.type == "Open Question" ? '' : answerColor;
				var commentHtml =question.type !== "Open Question" 
					? '<strong><?php echo $satisfactionLang["comment"] ?>:</strong> <span>' + comment + '</span><br>' 
					: '';

				answers += '<tr class="table-row customized-response">' +
						'<td>' +
							'<strong><?php echo $satisfactionLang["question"] ?>:</strong> <span>' + questionText + '</span><br>' +
							'<strong><?php echo $satisfactionLang["answer"] ?>:</strong> <span class="' + answerColor + '">' + answer + '</span><br>'  +
							commentHtml +
						'</td>' +
					'</tr>';
			});

			answers += '</table></div>';

		});

		$("#satisfactionAnswers").append(answers);
		$("#detailedReviewModalTitle").append('<span class="customized-response-time"> (' + customizedSatisfactionAnswersResponses[0].createdAt + ') </span>');
	});
	
    $('.input-daterange').datepicker({
        "format": 'yyyy-mm-dd'
    }).on(
        'changeDate', function(e){
            if($('#datepicker > input[name="start"] ').val()<$('#datepicker > input[name="end"] ').val()){
                submitSearchSurveyForm();
            }
        }
    );

	$('[data-toggle="buttons"] > label').on('change', function(e){
		submitSearchSurveyForm();
    });
	
	$('#select_filter_brand').on('change', function(e){
		submitSearchSurveyForm();
    });

	$("[name='incidents_reviewed']").on('change', function(e){
		submitForm(event, 'setIncidentsReviewed');
	});

	$('#search-survey-searcher').keypress(function (e) {
		if (e.which == 13) {
			submitSearchSurveyForm();
			return false;
		}
	});
	
	$('#per_page').on('change', function(e) {
		var url = "<?php echo preg_replace('~(\?|&)per_page=[^&]*~', '', $currentPageUrl); ?>"
		var symbol = url.indexOf('?') !== -1 ? "&" : "?"

		location.href =  url + symbol + "per_page=" + this.value;
    });

	function submitSearchSurveyForm() {
		var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "per_page").val(<?php echo $itemsPage ?>);
		$('form[name="search-survey"').append(input);
		
		$('form[name="search-survey"').submit();
	}

    function resetDatapicker(){
        $('#datepicker > input ').val("");
        $('#search-survey-searcher').val("");
        $('#select_filter_brand').val("hotel");
		submitSearchSurveyForm();
    }

	function forceReview(modalReview, sending_days){

		$.ajax({
			url: "/lib/webservices/force-review-ws.php",
			data: 'user_id=' + modalReview.data.userId +
					'&hotel_id=' + modalReview.data.hotelId +
					'&score=' + modalReview.data.score +
					'&comment=' + modalReview.data.comment +
					'&satisfaction_id=' + modalReview.data.satisfactionId +
					'&sending_days=' + sending_days +
					'&lang=<?php echo $_SESSION['userLang'] ?>',
			type: 'POST',
			async: false,
			success: function(output) {
				if(output['send_review']){
					$(modalReview.button).remove();
					$(".tooltip.fade.left.in").removeClass( "in" );
					//Feedback
					if (output['msgStatus'] !== "") {
						showError(output['msgStatus'], 4000);
					}
				} else {
					//Feedback
					if (output['msgStatus'] !== "") {
						showError(output['msgStatus'], 4000);
					}
				}
			}

		});
	}

	function hasBeenPromoted(model){
        $.ajax({
            url: "/lib/webservices/satisfaction-list-ws.php",
            data: 'action=hasBeenPromoted&satisfaction=' + model.dataset.satisfaction +
            '&favorite=' + model.dataset.favorite,

            type: 'POST',
            async: false,
            success: function (output) {
                if(output){
                    $(model).attr('class', 'fa hamburguer-btn fa-star verde');
                    model.dataset.favorite="0";
                }
                else{
                    $(model).attr('class', 'fa hamburguer-btn fa-star-o naranja');
                    model.dataset.favorite="1";
                }
            }
        });
    }

	function deleteSurvey() {
			var brandId = <?php echo $brandID ?>;
			var userSurveyId = $('[name="userSurveyIdToDelete"]').val();
			var satisfactionId = $('[name="getSatisfactionId"]').val();

			const data = {
				action: 'deleteSurvey',
				satisfactionId: userSurveyId,
				brandId: brandId
			}			
			$.ajax({
				url: '/lib/webservices/satisfaction-list-ws.php',
				type: 'POST',
				data: data,
				success: function(response) {
					$('#satisfaction-id-' + satisfactionId).remove();
				},
				error: function(response) {
					$.ajax({
					url: "/lib/webservices/msgFeedback.php",
					data: "nError=4070&lang=<?php echo $_SESSION['userLang'] ?>",
					type: 'POST',
					success: function(output) {
						var parsedData = $.parseJSON(output);
						showError(parsedData, 5000);
					}
				});
				} 
			})
			closeDeleteSurveyModal();
		}


    function hasBeenSeen(model){
        $.ajax({
            url: "/lib/webservices/satisfaction-list-ws.php",
            data: 'action=hasBeenSeen&satisfaction=' + model.dataset.satisfaction +
            '&hasBeenSeen=' + model.dataset.seen,

            type: 'POST',
            async: false,
            success: function (output) {
                if(output){
                    $(model).attr('class', 'fa hamburguer-btn fa-circle verde');
                    model.dataset.seen="0";
                }
                else{
                    $(model).attr('class', 'fa hamburguer-btn fa-circle naranja');
                    model.dataset.seen="1";
                }
            }

        });
    }

	function addNewComment() {
		$('#new_incident_body').css('visibility', 'visible');
		$('#send_create_incident_form').removeAttr('disabled');
	}

	function submitForm(event, formName) {
		formNameCorrected = '#' + formName;
		$(formNameCorrected).submit();
	}

	// Dynamic Modal Incidents
	$(document).on('show.bs.modal', '#modalIncidents', function (event) {

		$('#incidents-body').empty();
		$('#new_incident_body').css('visibility', 'hidden');

		user_satisfaction_id = event.relatedTarget.dataset.satisfactionId;
		incidentsReviewed = event.relatedTarget.dataset.incidentsReviewed;

		$('[name="user_survey_id_hidden"]').val(user_satisfaction_id);
		$('#incidents_reviewed').prop('checked', incidentsReviewed === "1");
		
		dataIncidentsString = event.relatedTarget.dataset.incidents.substring(0, event.relatedTarget.dataset.incidents.length - 1);

		var dataIncidents = JSON.parse(dataIncidentsString);

		incidentsDivToPrint = "";
		if (dataIncidents != null) {
			dataIncidents.forEach(function (incident) {
			var dateSplitted = incident.created_at.split(".");
			
			if (incident.staff.name){
				var deleted = incident.staff.deleted ? " (<?php echo $satisfactionLang['disabled'] ?>)" : ""
				incidentsDivToPrint += '<div id"main-div-incidents"> <label> ' + incident.staff.name  + deleted + '  | ' + dateSplitted[0] + ' </label> <p>' + incident.incident_text + ' </p> </div>'
			} else {
				incidentsDivToPrint += '<div id"main-div-incidents"> <label> <?php echo $satisfactionLang['Main Account'] ?> | ' + dateSplitted[0] + ' </label> <p>' + incident.incident_text + ' </p> </div>'
			}
		});
			$("#incidents-body").append(incidentsDivToPrint);
		}
	});

	function closeModalIncidents() {
		var isDisabled = $('#send_create_incident_form').prop('disabled');

		if(isDisabled) {
			$('#modalIncidents').modal('toggle'); 
		} else {
			$('#modalSavingConfirmation').modal('toggle'); 
		}
	}

	function closeConfirmationModal(){
		$('#modalSavingConfirmation').modal('toggle'); 
		$('#modalIncidents').modal('toggle');
	}

	function closeDeleteSurveyModal(){
		$('#modalDeleteSurvey').modal('toggle');
	}

</script>

<?php
if ($_SESSION['permisos']['trans_comments'] ?? 0):
?>
<script src="<?php echo DIR_JS ?>translate-comments.js"></script>
<?php
endif;
