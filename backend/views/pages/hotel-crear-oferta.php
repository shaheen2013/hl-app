<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-crear-oferta.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-crear-oferta-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="rubies rubix3">rubies</i> <?php echo $HotelCrearOfertaLang['Create new rewards campaign'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt2">
				<div class="col-lg-8 col-lg-offset-2 col-md-12">
					<form action="<?php echo $url['dir1'] ?>" method="POST" name="createNewOffer">
						<div class="alert alert-info">
							<div class="pull-left">
								<i class="fa fa-lightbulb-o fa-2x pr"></i>
							</div>
							<p><?php echo $HotelCrearOfertaLang['Follow the steps to create a new campaign, selecting options will cause another new options appear dinamically'] ?></p>
						</div>
						<h3 class="pl"><?php echo $HotelCrearOfertaLang['Select an option for your reward campaign'] ?></h3>
						<div class="col-lg-6 col-md-6 mt2">
							<label for="offerMethod"><?php echo $HotelCrearOfertaLang['Is your goal to attract existing customers or new customers?'] ?> <button class="azul btn-helper" data-toggle="modal" data-target="#ret-adq-mod-hel"><i class="fa fa-question-circle"></i></button></label>
							<select name="offerMethod" id="offerMethod" class="form-control">	
                            	<option value="0"><?php echo $HotelCrearOfertaLang['Select an option'] ?></option>
								<?php foreach($arrayOfferMethod as $key => $value){?>
                                    <option value="<?php echo $key?>" <?php echo (!empty($_SESSION['offerMethod']) &&  $_SESSION['offerMethod'] == $key ? 'selected' : '')?>><?php echo $HotelCrearOfertaLang[$value] ?></option>
                                <?php }?>
                            </select>
						</div>
						<div class="col-lg-6 col-md-6 mt2">
							<label for="offertype"><?php echo $HotelCrearOfertaLang['Campaign type'] ?> <button class="azul btn-helper"title="ayuda" data-toggle="modal" data-target="#tip-ofe-hel"><i class="fa fa-question-circle"></i></button></label>
							<select name="offertype" id="offertype" class="form-control" <?php echo ((empty($_SESSION['offertype']) ? 'disabled' : '')) ?>>
								<?php foreach ($arrayAdqRet as $option) { ?>
								<option value="<?php echo $option['id'] ?>"><?php echo $option['adq_ret'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-lg-6 col-md-6 mt2">
							<label for="category"><?php echo $HotelCrearOfertaLang['Category'] ?> <button class="azul btn-helper"title="ayuda" data-toggle="modal" data-target="#cat-hel"><i class="fa fa-question-circle"></i></button></label>
							<select name="category" id="category" class="form-control" <?php echo ((empty($_SESSION['category']) ? 'disabled' : '')) ?>>
							</select>
						</div>
						<div class="col-lg-6 col-md-6 mt2 mb2">
							<label for="subCategory"><?php echo $HotelCrearOfertaLang['Sub-category'] ?><button class="azul btn-helper"title="ayuda" data-toggle="modal" data-target="#cat-hel"><i class="fa fa-question-circle"></i></button></label>
							<select name="subCategory" id="subCategory" class="form-control" <?php echo ((empty($_SESSION['subCategory']) ? 'disabled' : '')) ?>>
							</select>
						</div>
						<div class="clearfix"></div>
						<div class="col-lg-6 col-md-6 offerContinueBtn"> </div>
					</form>
				</div>
			</div>
		</div>
</div>
<?php
// include TEMPLATES . 'retencion-adquisicion-explanation-modal.php';
// include TEMPLATES . 'tipo-oferta-explanation-modal.php';
// include TEMPLATES . 'categorias-explanation-modal.php';
// include TEMPLATES . 'error-creating-adquisition-modal.php';
?>
<script type="text/javascript" src="<?php echo DIR_JS?>crear-oferta-ajax.min.js"></script>
<script>
	var puedeCrear;
	function puedeCrearAdq(){
		$.ajax({ url: "/lib/webservices/hotel-crear-oferta.php",
			data: 'puedeCrearAdq=1',
			type: 'POST',
			async : false,
			success: function(output) {
				puedeCrear=output;
			}
		});
	}
	function comprobarSiPuedeAdq (offerMethod){
puedeCrearAdq(); // <-- Webservice
if(puedeCrear == 0 && offerMethod=='adq'){
	$('#error-creating-adquisition-modal').modal('show');
	$('#offertype').prop('disabled', true);
	$('.btnCrearOferta').addClass('disabled');
}else{
	$('.btnCrearOferta').removeClass('disabled');
	$('.offerContinueBtn').empty().append("<a href='<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>' title='crear el detalle de la oferta' class='btn btn-lg btn-primary'><?php echo $HotelCrearOfertaLang['Go to campaign details button'] ?></a>");
}
}
$(document).ready(function(){
	<?php if(!empty($_SESSION['offerMethod'])){ ?>
		$('.offerContinueBtn').empty().append("<a href='<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>' title='crear el detalle de la oferta' class='btn btn-lg btn-primary'><?php echo $HotelCrearOfertaLang['Go to campaign details button'] ?></a>");
		<?php if ($_SESSION['offerMethod']=='adq'){?>

			var offerMethod = $('#offerMethod').val();
			comprobarSiPuedeAdq (offerMethod);

			<?php }?>
			obtenerOffertype();
			<?php }
			if(!empty($_SESSION['offertype'])){ ?>
				obtenerCategory();
				<?php
				if ($_SESSION['offertype'] != 'chk'){?>
					obtenerSubcategory();
					<?php
				}
			}
			if(!empty($_SESSION['category'])){ ?>
				obtenerSubcategory();
				$('.offerContinueBtn').empty().append("<a href='<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>' title='crear el detalle de la oferta' class='btn btn-lg btn-primary'><?php echo $HotelCrearOfertaLang['Go to campaign details button'] ?></a>");
				<?php
			}?>
			$('#offerMethod').change(function(){
				var offerMethod = $('#offerMethod').val();
				if (offerMethod=='adq'){
					comprobarSiPuedeAdq (offerMethod);
				}else{
					$('.btnCrearOferta').removeClass('disabled');
					$('.offerContinueBtn').empty().append("<a href='<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>' title='crear el detalle de la oferta' class='btn btn-lg btn-primary'><?php echo $HotelCrearOfertaLang['Go to campaign details button'] ?></a>");
				}
			});
		});
	</script>