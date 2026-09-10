<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/hotel-profile-datos-de-reserva.php' ?>
<?php include LANG . $_SESSION['userLang'] . '/hotel-crear-detalle-oferta.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i
                            class="fa fa-building"></i> <?php echo $HotelProfileReservaLang['Direct reservation details'] ?>
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
                <div class="col-lg-8 col-lg-offset-2">
                    <?php if (isset($errorMsg)) { ?>
                        <div class="alert alert-danger fade in text-center">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <p><?php echo $errorMsg; ?></p>
                        </div>
                    <?php } ?>
                    <div class="col-lg-12 mb">
                        <div class="row">
                            <h4 class="mt4"><?php echo $HotelProfileReservaLang['Edit reservation details'] ?></h4>
                        </div>
                    </div>

                    <form role="form" class="mt2 validation-form" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="emailReserva"><?php echo $HotelProfileReservaLang['Reservations e-mail'] ?></label>
                                    <input type="email" class="form-control" id="emailReserva" name="emailReserva"
                                           placeholder="<?php echo $HotelProfileReservaLang['reservations e-mail here...'] ?>"
                                           required
                                           value="<?php echo(!empty($arrayDatosReserva['emailReserva']) ? $arrayDatosReserva['emailReserva'] : '') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mt2">
                            <div class="col-lg-12">
                                <div class="panel panel-default noPadding">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?php echo $HotelProfileReservaLang['Booking engine'] ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                        <?php 
                                            foreach($languages as $lang) {?>
                                                <label for="hotelWebsite-<?php echo $lang->id ?>"> <?php echo $HotelCrearDetalleOfertaLang['offerLangs'][$lang->name] ?> </label>
                                                <input type="text" class="form-control" id="hotelWebsite-<?php echo $lang->id ?>"
                                                    name="hotelWebsite[<?php echo $lang->name ?>]"
                                                    placeholder="<?php echo $HotelProfileReservaLang['Direct booking website here...'] ?>"
                                                    value="<?php foreach($brandLanguages as $brandLang) {?><?php echo $brandLang->name == $lang->name ? $brandLang->url : '' ?><?php } ?>"/>
                                                   
                                        <?php } ?>
                                             
                                    </div>
                                    <div class="panel-heading  second-heading">
                                        <h3 class="panel-title"><?php echo $HotelProfileReservaLang['secondary urls by country'] ?></h3>
                                    </div>

                                    <div class="panel-body">
                                        <?php foreach($arrayHotelCountryLangs as $hotelCountryLang){
                                            if($hotelCountryLang['country_lang_id']!=1){?>

                                        <div class="form-group" style = "position:relative">
                                                <a class="delete_url_btn" data-country-lang-id="<?php echo $hotelCountryLang['country_lang_id']?>" data-toggle="modal" data-target="#deleteLangUrlModal"><i class="clicable fa fa-minus-square-o"></i>
                                                    </a>

                                            <label for="hotelWebsite_<?php echo $hotelCountryLang['country_lang_id']?>"><?php echo $hotelCountryLang['country']?> website </label>
                                            <input type="text" class="form-control" id="hotelWebsite_<?php echo $hotelCountryLang['country_lang_id']?>"
                                                   name="<?php echo $hotelCountryLang['country_lang_id']?>"
                                                   placeholder="<?php echo $HotelProfileReservaLang['Direct booking website here...'] ?>"
                                                   value="<?php echo $hotelCountryLang['url']?>">

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
                        </div>
                        <div class="row mt2">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="telefonoReservas"><?php echo $HotelProfileReservaLang['Call center phone number'] ?></label>
                                    <input type="telf" class="form-control" id="telefonoReservas"
                                           name="telefonoReservas"
                                           placeholder="<?php echo $HotelProfileReservaLang['Direct booking phone number here (inlude country code)...'] ?>"
                                           required
                                           value="<?php echo(!empty($arrayDatosReserva['telefonoReservas']) ? $arrayDatosReserva['telefonoReservas'] : '') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mt2">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="bookingEngine"><?php echo $HotelProfileReservaLang['Select your booking engine'] ?></label>
                                    <select class="form-control" id="bookingEngine" name="bookingEngine" onchange="togglePromoCodeInput()">
                                        <option value="0" <?php echo($arrayDatosReserva['booking_engine'] == null ? 'selected' : '') ?>>
                                            No booking engine
                                        </option>
                                        <?php foreach ($bookingEngines as $booking_engine) {
                                            if ($arrayDatosReserva['booking_engine'] == $booking_engine['id']) {
                                                echo '<option value="' . $booking_engine['id'] . '" selected>' . $booking_engine['name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $booking_engine['id'] . '">' . $booking_engine['name'] . '</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt2" id="promoCodeParamContainer" style="display:none;">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="promoCodeParam"><?php echo $HotelProfileReservaLang['Promo code parameter'] ?></label>
                                    <input class="form-control" id="promoCodeParam"
                                           name="promoCodeParam"
                                           placeholder="<?php echo $HotelProfileReservaLang['Introduce param for promo code'] ?>"
                                           value="<?php echo(!empty($arrayDatosReserva['promo_code_param']) ? $arrayDatosReserva['promo_code_param'] : '') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-5 mb">
                            <input type="submit" value="<?php echo $HotelProfileReservaLang['Save changes button'] ?>"
                                   class="btn btn-success btn-lg mt2 btn-block" name="hotelConfirmButton">
                        </div>
                    </form>
                    <div class="clearfix"></div>
                </div>
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
                <h4 class="modal-title"><?php echo $HotelProfileReservaLang['add new language to booking'] ?></h4>
            </div>
            <div class="modal-body">
                <label><?php echo $HotelProfileReservaLang['Lang'] ?></label>
                <select class="form-control survey-comment" name="country" >
                    <?php foreach($arrayCountryLangs as $countryLang){
                        if($countryLang['locale']!='NULL'){6?>

                    <option value="<?php echo $countryLang['id']?>"><?php echo $countryLang['country']?></option>
                    <?php }
}?>
                </select>
            </div>
            <div class="modal-footer">
                <input type="submit" value="<?php echo  $HotelProfileReservaLang['Create'] ?>"
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
                    <h4 class="modal-title"><?php echo $HotelProfileReservaLang['warning'] ?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="countryLangId" name="country_lang_id"/>
                    <p><?php echo $HotelProfileReservaLang['sure'] ?></p>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="<?php echo  $HotelProfileReservaLang['delete'] ?>"
                           class="btn btn-success btn-lg mt2 btn-block" name="deleteLang">
                    <input type="button" value="<?php echo  $HotelProfileReservaLang['Cancel'] ?>"
                           class="btn .btn-danger btn-lg mt2 btn-block"  data-dismiss="modal" aria-label="Close">
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

    function togglePromoCodeInput(){
        var bookingEngine = document.getElementById('bookingEngine').value;
        var promoCodeContainer = document.getElementById('promoCodeParamContainer');
        if(bookingEngine == "0"){
            promoCodeContainer.style.display = '';
        } else {
            promoCodeContainer.style.display = 'none';
        }
    }
    $(document).on("click", ".delete_url_btn", function () {
        var myBookId = $(this).data('countryLangId');
        $("#countryLangId").val( myBookId );
    });

    document.addEventListener('DOMContentLoaded', function() {
    togglePromoCodeInput();
    });

</script>