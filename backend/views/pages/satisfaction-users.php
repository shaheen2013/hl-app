<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<?php include LANG . $_SESSION['userLang'] . '/satisfaction-users.php' ?>
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
		<div class="utility-bar mt">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-smile-o"></i> <?php echo $satisfactionUserLang['Satisfaction users'] ?></h1> 
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
                <form name="search-survey" action="<?php echo $urlActual ?>" >
                    <input type="submit" hidden value="Submit">
					<div class="input-group" style="width:45%;">
						<input type="text" class="form-control" name="search" id="search-survey-searcher" placeholder=<?php echo $satisfactionUserLang["Search by name"] ?> value="<?php echo $urlSearch ?>"/>
						<span id="search-icon" class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
				</form>
			<?php if(!empty($users_warnings_finished)){ ?>
				<div class="table-responsive mt relative">
					<table class="table table-striped">
						<tr class="table-header">
							<td>
								<span><?php echo $satisfactionUserLang['Nombre'] ?></span> <a href="<?php echo "$urlActual?$urlOldGet" ?>ord=nombre" title="sort"><i class="fa fa-sort pl"></i></a>
							</td>
							<!--<td>
								<span><?php //echo $satisfactionUserLang['AVGOpenRate'] ?></span> <a href="<?php //echo "$urlActual?$urlOldGet" ?>ord=AVGOpenRate" title="sort"><i class="fa fa-sort pl"></i></a>
							</td>-->
                            <td>
								<span><?php echo $satisfactionUserLang['ReviewsFinished'] ?></span> <a href="<?php echo "$urlActual?$urlOldGet" ?>ord=number_reviews_warning_email" title="sort"><i class="fa fa-sort pl"></i></a>
							</td>
						</tr>
						
						<tr class="table-row">
							<td>
								Main Account
							</td>
							<!--<td>
								<?php //echo "N/A" ?>
							</td>-->
							<td>
								<?php echo $main_account_warnings_finished ?>
							</td>
						</tr>

						<tr><td></td><td></td></tr>
					
						<?php foreach($users_warnings_finished AS $user_warnings_finished){ ?>
						<tr class="table-row">
							<td>
								<?php echo $user_warnings_finished['nombre'] ?>
							</td>
							<!--<td>
								<?php //echo "N/A" ?>
							</td>-->
							<td>
								<?php echo $user_warnings_finished['number_reviews_warning_email'] ?>
							</td>
						</tr>
						<?php } ?>
					</table>
                    <ul class="pagination pull-right mt0">
                        <?php if( $_SESSION['primeraPagina'] == 1 ){ ?>
                            <li class="disabled"><a href="<?php echo "$urlActual?$urlOldGet"; ?>pag=<?php echo $pagina - 1;?>">&laquo;</a></li>
                        <?php }else{ ?>
                            <li><a href="<?php echo "$urlActual?$urlOldGet"; ?>pag=<?php echo $pagina - 1;?>">&laquo;</a></li>
                        <?php } ?>
                        <?php for ($i=0; $i < $_SESSION['paginas']; $i++) { ?>
                            <li
                                <?php if ($pagina == $i+1){
                                    echo 'class="active"';
                                }?>
                            ><a href="<?php echo "$urlActual?$urlOldGet"; ?>pag=<?php echo $i + 1;?>"><?php echo $i +1; ?></a></li>
                        <?php } ?>
                        <?php if( $_SESSION['ultimaPagina'] == 1){ ?>
                            <li class="disabled"><a href="<?php echo "$urlActual?$urlOldGet"; ?>pag=<?php echo $pagina + 1;?>">&raquo;</a></li>
                        <?php }else{ ?>
                            <li><a href="<?php echo "$urlActual?$urlOldGet"; ?>pag=<?php echo $pagina + 1;?>">&raquo;</a></li>
                        <?php } ?>
                    </ul>
				</div>
			<?php }else{ ?>
					<div class="text-center mt2 container no-data-msg">
						<i class="fa fa-envelope grisClaro fa-5x"></i>
						<h2><?php echo $satisfactionUserLang['There is no users at this moment'] ?></h2>
					</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>

	var modalReview = {};

	$(document).ready(function(){
		$('#search-icon').on('click', function(){
			$('form[name="search-survey"').submit();
		})
	});

    $(document).ready(function(){
        $('#search-date-icon').on('click', function(){
            $('form[name="search-survey"').submit();
        })
    });

	

    
</script>
