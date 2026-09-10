<?php include_once LANG . $_SESSION['userLang'] . '/satisfaction-survey.php'; ?>
<div class="stay-overlayer"></div>
<!-- <img class="ss-bg-img"src="<?php echo SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL ?><?php echo $hotel ?>/fotoBg/big_<?php echo $datosHotel['fotoBg'] ?>" alt="Background image of hotel" class="rs-bg"> -->
<img class="ss-bg-img" src="<?php echo $datosHotel['fotoBg'] ?>" alt="Background image of hotel" class="rs-bg">
<div class="satisfaction-survey-content text-center vertical-align">
	<img src="<?php echo $datosHotel['logo'] ?>"alt="Hotel logo" class="img-thumbnail img-circle animated zoomIn anim1" width="100" height="100">
	
	<div class="satisfaction-text">
		<h3 class="animated zoomIn anim2"><?php echo $sSLang['Leave your opinión about your experience at'] ?> <strong><?php echo array_get($datosHotel, 'hotelName') ?></strong> <?php echo $sSLang['to improve'] ?> </h3>
		<p class="animated zoomIn anim3"><?php echo $sSLang['Move the slider and give us an 0 to 10 score in order to show your satisfaction'] ?></p>
	</div>
	
	<form action="" method="post" id="survey-form">

		<div class="slider sliderRating animated zoomIn anim4" id="colorSlider">
            <img class="face_icon icon_00" src="<?php echo BASE_PATH . DIR_IMG . 'smiles/00.svg'?>" alt="Angry icon" width="32">
            <img class="face_icon icon_25" src="<?php echo BASE_PATH . DIR_IMG . 'smiles/25.svg'?>" alt="not so angry icon" width="32">
            <img class="face_icon icon_50" src="<?php echo BASE_PATH . DIR_IMG . 'smiles/50.svg'?>" alt="meehh icon" width="32">
            <img class="face_icon icon_75" src="<?php echo BASE_PATH . DIR_IMG . 'smiles/75.svg'?>" alt="happy icon" width="32">
            <img class="face_icon icon_100" src="<?php echo BASE_PATH . DIR_IMG . 'smiles/100.svg'?>" alt="the happiest icon" width="32">
        </div>
		<input type="hidden" name="rating" id="hotelRating" value="5">
		<input type="hidden" id="survey-comment-form" name="comments">
		<div class="clearfix"></div>
		<button class="btn btn-primary btn-survey animated zoomIn anim5"><?php echo $sSLang['Finish survey'] ?></button>
	</form>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="commentsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $sSLang['There is any comment you want to address us?'] ?></h4>
            </div>
            <div class="modal-body">
                <label><?php echo $sSLang['Comments'] ?></label>
                <textarea class="form-control survey-comment" name="comment"
                          placeholder="<?php echo $sSLang['Some comments...'] ?>"  minlength="25"></textarea>
                <div><p class="error-text" id="error-msg"></p></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-send-survey"><i class="fa fa-smile-o"
                                                                                 aria-hidden="true"></i> <?php echo $sSLang['Send survey'] ?>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="sliderTouchWarning">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <?php if (isset($user_name) && $user_name) : ?>
                    <h3><?php echo $sSLang['Hi'] . ' ' . $user_name ?></h3>
                <?php endif; ?>
                <h4><?php echo $sSLang['No has cambiado tu puntuación'] ?></h4>
            </div>
            <div class="modal-body">
                <button class="btn btn-warning closeSliderTouchWarning"><?php echo $sSLang['Si'] ?></button>
                <button class="btn btn-primary" data-dismiss="modal"><?php echo $sSLang['No'] ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(document).ready(function () {
        var sliderTouch = false;
        sliderTooltip = function (event, ui) {
            refreshSwatch();
            curValue = ui.value;
            tooltip = '<div class="rangeValue">' + curValue + '</div>';
            $('.ui-slider-handle').html(tooltip);
            $('#hotelRating').val(curValue);
            sliderTouch = true;
        };

        $('.btn-survey').click(function (e) {
            e.preventDefault();
            if (!sliderTouch) {
                $('#sliderTouchWarning').modal('show');
            } else {
                $('#commentsModal').modal('show');
            }
        });

        $('.closeSliderTouchWarning').click(function () {
            $('#sliderTouchWarning').modal('hide');
            setTimeout(function () {
                $('#commentsModal').modal('show');
            }, 500);
        });

        createTooltip = function () {
            tooltip = '<div class="rangeValue">' + 5 + '</div>';
            $('.ui-slider-handle').html(tooltip);
        };

        $('.sliderRating').slider({
            range: "min",
            min: 0,
            max: 10,
            value: 5,
            step: 0.1,
            create: createTooltip,
            slide: sliderTooltip
        });

		//If comment is empty add error notification, or send the form
		$('.btn-send-survey').click(function(){
			comment = $('.survey-comment').val();
            var sectionToCheck  = comment;
            var specialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{6}/gi;
            var allFoundCharacters = sectionToCheck.match(specialChars);
			if(comment == '' || comment.replace(/\s+/, "").length < 25 || allFoundCharacters != null && allFoundCharacters.length > 0){
                $('.survey-comment').addClass('has-error');
                if(comment == '' || comment.replace(/\s+/, "").length < 25) {
                    $('#error-msg').text('<?php echo $sSLang['Error min char'] ?>');
                }
                else if( allFoundCharacters.length > 0 ){
                    $('#error-msg').text('<?php echo $sSLang['Error false comment'] ?>');
                }
                else{
                    $('#error-msg').text('');
                }
            }else{
                $('#survey-comment-form').val(comment);
                $('#survey-form').submit();
            }
        });
        //On click on comments remove errors notification
        $('.survey-comment').click(function () {
            $('.survey-comment').removeClass('has-error');
        });

        function getTheColor( colorVal ) {
            colorVal = colorVal * 10;
            var theColor = "";
            if ( colorVal < 50 ) {
                myRed = 255;
                myGreen = parseInt( ( ( colorVal * 2 ) * 255 ) / 100 );
            }
            else  {
                myRed = parseInt( ( ( 100 - colorVal ) * 2 ) * 255 / 100 );
                myGreen = 255;
            }
            theColor = "rgb(" + myRed + "," + myGreen + ",0)";
            return( theColor );
        }

        function refreshSwatch() {
            var coloredSlider = $( "#colorSlider" ).slider( "value" ),
                myColor = getTheColor( coloredSlider );

            $( "#colorSlider .ui-slider-range" ).css( "background-color", myColor );

            $( "#colorSlider .ui-state-default, .ui-widget-content .ui-state-default" ).css( "background-color", myColor );
        }
    })
</script>