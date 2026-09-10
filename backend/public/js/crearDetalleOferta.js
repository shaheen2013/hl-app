$(document).ready(function() {

    // variable globlal que define si la oferta es publicable y la imagen temporal
    var canPub;
    // chequea todas las áreas para comprobar su estado
    checkText("#offerDesc");
    checkText("#offerCond");
	saveBookinEngineCode($("#bookingEngineCode").val());
    checkMoney($("#money").val());
    checkTop();
    calcularPuntos();

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
		$.ajax({
            url: "/lib/webservices/hotel-crear-detalle-oferta.php",
            data: "croppedImg=" + croppedImg + "",
            type: "POST"
        });
        checkTop();
    })

    //
    if (sessionReq != "") {
        $(".offerSidebarAlert").fadeIn();
    } else {
        $(".offerSidebarAlert").fadeOut();
    };

    // Check módulo donde está el precio ya que se calcula solo
    if (offerMet == "adq") {
        $(".costCheck").css("color", "#0dfe93").addClass("scalar");
    };

    // Date picker Valid From
    $("#offerValidFrom").datepicker({
        dateFormat: "dd-mm-yy",
        minDate: 0
    });

    // Date picker valid till
    $("#offerValidTill").datepicker({
        dateFormat: "dd-mm-yy",
        minDate: 0
    });

    //
    $(".editBtn").click(function() {
        $(this).prev(".offerInputs").focus();
    });

    // Al añadir dinero CHECK
    $("#money").change(function() {
        var money = $(this).val();
        if (money >= 0) {
            checkMoney(money);
            checkCircles(offerMet);
        }
    });

    //
    $("#requeriments").change(function() {
        if ($(this).val() != "") {
            $(".offerSidebarAlert").fadeIn();
        } else {
            $(".offerSidebarAlert").fadeOut();
        }
    });

    // CHECK AND SAVE cada vez que escribe en el campo de nombre
    $("#offerName").on('keyup change', function() {
	//$("#offerName").keyup(function() {
		saveNameOffer();
    });
	
	//Check de seguridad
	/*$("#offerName").change(function() {
		saveNameOffer();
	});*/

    // SAVE fecha
    $("#offerValidFrom").change(function() {
        var selected = $("#offerValidFrom").val();
        $.ajax({
            url: "/lib/webservices/hotel-crear-detalle-oferta.php",
            data: "inicio=" + selected + "",
            type: "POST",
            success: function(output) {
                $(".offerDetailsCost strong").empty();
                $(".offerDetailsCost strong").append(output);
            }
        });
        $.ajax({
            url: "/lib/webservices/hotel-crear-detalle-oferta.php",
            data: "inicio_verif=" + selected + "",
            type: "POST",
            success: function(output) {
                if (output == " " || output == "") {
                    $("#hotelClosed").val(0);
                } else {
                    $("#hotelClosed").val(1);
                    $(".feedback ").empty();
                    $(".feedback ").removeClass("warning-action");
                    $(".feedback ").addClass("error-action");
                    $(".feedback ").append(output);
                    msgError();
                }
            }
        });
        $.ajax({
            url: "/lib/webservices/hotel-crear-detalle-oferta.php",
            data: "inicio_correcta=" + selected + "",
            type: "POST",
            success: function(output) {
                if (output == " " || output == "") {
                    $("#fechaIncorrecta").val(0);
                } else {
                    $("#fechaIncorrecta").val(1);
                    $(".feedback ").empty();
                    $(".feedback ").removeClass("warning-action");
                    $(".feedback ").addClass("error-action");
                    $(".feedback ").append(output);
                    msgError();
                }
            }
        });
        checkTop();
    });

    // SAVE fecha
    $("#offerValidTill").change(function() {
        var selected = $("#offerValidTill").val();
        $.ajax({
            url: "/lib/webservices/hotel-crear-detalle-oferta.php",
            data: "fin=" + selected + "",
            type: "POST",
            success: function(output) {
                $(".offerDetailsCost strong").empty();
                $(".offerDetailsCost strong").append(output);
            }
        });
        $.ajax({
            url: "/lib/webservices/hotel-crear-detalle-oferta.php",
            data: "fin_verif=" + selected + "",
            type: "POST",
            success: function(output) {
                if (output != "") {
                    $("#hotelClosed").val(1);
                    $(".feedback ").empty();
                    $(".feedback ").removeClass("warning-action");
                    $(".feedback ").addClass("error-action");
                    $(".feedback ").append(output);
                    msgError();
                } else {
                    $("#hotelClosed").val(0);
                }
            }
        });
        $.ajax({
            url: "/lib/webservices/hotel-crear-detalle-oferta.php",
            data: "fin_correcta=" + selected + "",
            type: "POST",
            success: function(output) {
                if (output != "") {
                    $("#fechaIncorrecta").val(1);
                    $(".feedback ").empty();
                    $(".feedback ").removeClass("warning-action");
                    $(".feedback ").addClass("error-action");
                    $(".feedback ").append(output);
                    msgError();
                } else {
                    $("#fechaIncorrecta").val(0);
                }
            }
        });
        checkTop();
    });

    // CHECK AND SAVE descripción de la oferta
    $("#offerDesc").keyup(function() {
        var descContent = encodeURIComponent($("#offerDesc").html());
        $("#descripcion").val(descContent);
        insertDescription();
        var descContentClean = cleanHtml(descContent);
        if (descContentClean.length == 0 || !$(".descriptionCheck").hasClass("scalar")) {
            checkLang();
        }
        checkText("#offerDesc");
        checkCircles(offerMet);
    });

    //CHECK AND SAVE condiciones de la oferta
    $("#offerCond").keyup(function() {
        var condContent = encodeURIComponent($("#offerCond").html());
        $("#conditions").val(condContent);
        insertConditions();
        var condContentClean = cleanHtml(condContent);
        if (condContentClean.length == 0 || !$(".conditionsCheck").hasClass("scalar")) {
            checkLang();
        }
        checkText("#offerCond");
        checkCircles(offerMet);
    });

    //CHECK AND SAVE discount
    $("#discount").change(function() {
        var descuento = $(this).val();
        if (descuento >= 0 && descuento <= 100) {
            $(".offerBorder strong").empty().text(descuento);
            $.ajax({
                url: "/lib/webservices/hotel-crear-detalle-oferta.php",
                data: "descuento=" + descuento + "",
                type: "POST",
                success: function(output) {
                    $(".offerDetailsCost strong").empty();
                    $(".offerDetailsCost strong").append(output);
                }
            });
        }
    });

    //Calcular los rubies necesarios en función del dinero
    $("#money").change(function() {
        calcularPuntos();
    });

    //Calcular puntos en función de la divisa
    $("#divisa").change(function() {
        calcularPuntos();
    });

    //Calcular la cuota
    $("#quota").on("keyup", function() {
        var selected = $("#quota").val();
        if (selected >= 0) {
            $.ajax({
                url: "/lib/webservices/hotel-crear-detalle-oferta.php",
                data: "cupo=" + selected + "",
                type: "POST",
                success: function(output) {
                    $(".quotaText").empty();
                    if (selected > 0) {
                        $(".quotaText").append(selected + " left");
                    } else {
                        $(".quotaText").append("unlimited seats");
                    }
                }
            });
        }
    });

    //CHECK requerimientos
    $("#requeriments").change(function() {
        var selected = $("#requeriments").val();
        if (selected >= 0) {
            $(".requerimentsAlert span").text(selected);
            $.ajax({
                url: "/lib/webservices/hotel-crear-detalle-oferta.php",
                data: "requerimientos=" + selected + "",
                type: "POST",
                success: function(output) {
                    $(".offerDetailsCost strong").empty();
                    $(".offerDetailsCost strong").append(output);
                }
            });
        }
    });

    // Strip descripción
    $(".refreshDescription").click(function() {
        var descContent = $("#offerDesc").html();
        $("#descripcion").val(descContent);
        insertDescription();
    });

    // Strip condiciones
    $(".refreshConditions").click(function() {
        var condContent = $("#offerCond").html();
        $("#conditions").val(condContent);
        insertConditions();
    });
	
    //SAVE booking engine code
    $("#bookingEngineCode").on("keyup", function() {
        var bookingEngineCode = $("#bookingEngineCode").val();
        saveBookinEngineCode(bookingEngineCode);
		checkCircles(offerMet);
    });

    //
    $(".langLia").click(function(e) {
        e.preventDefault();
    });

    //
    $('.costAndQuotaBtn').click(function() {
        $('.costAndQuotaExplanation').fadeToggle();
    });

    //
    $('.btnPublishOffer, .btnSaveOffer').popover({
        trigger: 'hover'
    });

    //Publicar oferta
    $('.btnPublishOffer').click(function(e) {
        e.preventDefault();
        comprobarPublicable();
        if (canPub != '0') {
            window.location = pubUrl;
        } else {
            $('#cantPublishOfferModal').modal({
                show: true
            })
        }
    });

    //Guardar oferta
    $('.btnSaveOffer').click(function(e) {
        e.preventDefault();
        comprobarGuardable(function(val) {
            canSave = val;
        });
        if (canSave == '0') {
            $('#offerSaveErrorModal').modal({
                show: true
            })
        } else {
            window.location = pubSave;
        }
    });

    //Ver video tutorial
    $('.btnVideotutorial').click(function(e) {
        e.preventDefault();
        $('#createOfferVideoModal').modal({
            show: true
        });
    });

    //WYSIWYG
    initToolbarBootstrapBindings();
    $('#offerDesc').wysiwyg({
        toolbarSelector: '[data-role=editor1-toolbar]'
    });

    //WYSIWYG
    $('#offerCond').wysiwyg({
        toolbarSelector: '[data-role=editor2-toolbar]'
    });

    //Divisas btn
    $('.a-divisa').click(function(e) {
        e.preventDefault();
        var divisa = $(this).data('value');
        $('.input-divisa').val(divisa);
        $('.divisa-toggle').text(divisa);
        calcularPuntos();
    });

    //CHECK todo cuando carque la página
    checkCircles(offerMet);
});

function saveNameOffer(){
	var name = encodeURIComponent($("#offerName").val());
	$.ajax({
		url: "/lib/webservices/hotel-crear-detalle-oferta.php",
		data: "name=" + name + "",
		type: "POST",
		success: function() {
			if (name.length == 0 || !$(".dragFileCheck").hasClass("scalar")) {
				checkLang();
				checkTop();
			}
		}
	});
};
	
function saveBookinEngineCode(bookingEngineCode){
	$.ajax({
		url: "/lib/webservices/hotel-crear-detalle-oferta.php",
		data: "bookingEngineCode=" + bookingEngineCode + "",
		type: "POST"
	});
	
	if(bookingEngineCode!=''){
		$("#BECCheck").css("color", "#0dfe93").addClass("scalar");
	}else{
		$("#BECCheck").css("color", "#D7D7D7").removeClass("scalar");
	}
};

function insertConditions() {
    var selected = $("#conditions").val();
    $.ajax({
        url: "/lib/webservices/hotel-crear-detalle-oferta.php",
        data: "condiciones=" + selected + "",
        type: "POST"
    });
};

function insertDescription() {
    var selected = $("#descripcion").val();
    $.ajax({
        url: "/lib/webservices/hotel-crear-detalle-oferta.php",
        data: "descripcion=" + selected + "",
        type: "POST"
    });
};

function calcularPuntos() {
    var money = $("#money").val();
    var currency = $("#divisa").val();
    if (money != "" && currency != "") {
        $.ajax({
            url: "/lib/webservices/hotel-crear-detalle-oferta.php",
            data: "coste=" + money + "&currency=" + currency,
            type: "POST",
            success: function(output) {
                $("#pointsCost").empty();
                $("#pointsCost").append(output);
            }
        });
    }
};

function checkMoney(money) {
    if (money != "") {
        $(".costCheck").css("color", "#0dfe93").addClass("scalar");
    } else {
        $(".costCheck").css("color", "#D7D7D7").removeClass("scalar");
    }
};

function checkTop() {
    var croppedImg = $('.cropHolder > img').attr('src') || '';
    if (offerMet == "ref" && croppedImg.length > 0 && $("#offerName").val() != "") {
        $(".dragFileCheck").css("color", "#0dfe93").addClass("scalar");
    } else if (offerMet != "ref" && croppedImg.length > 0 && $("#offerName").val() != "" && $("#offerValidFrom").val() != "" && $(".offerBorder strong").text() != "XX" && $("#hotelClosed").val() == "0" && $("#fechaIncorrecta").val() == "0") {
        $(".dragFileCheck").css("color", "#0dfe93").addClass("scalar");
    } else {
        $(".dragFileCheck").css("color", "#D7D7D7").removeClass("scalar");
    }
    checkCircles(offerMet);
};

function checkText(textArea) {
    if ($(textArea).text().length == 0) {
        $(textArea).parent().children(".textCheck").css("color", "#D7D7D7").removeClass("scalar");
    } else {
        $(textArea).parent().children(".textCheck").css("color", "#0dfe93").addClass("scalar");
    }
    checkCircles(offerMet);
};

function checkCircles(offerMet) {
    var croppedImg = $('.cropHolder > img').attr('src') || '';
	var bookingEngineCode = $('#bookingEngineCode ').val() ;
	bookingEngineCode = bookingEngineCode.replace(/ /g,'');//delete BEC spaces
    var rs = checkLangs();
    if (offerMet == "ref" && croppedImg.length > 0 && rs == 200 && bookingEngineCode!="") {
        $(".btnPublishOffer").removeClass("disabled").addClass("btn-publish-campaign");
    } else if (offerMet != "ref" && croppedImg.length > 0 && rs == 200 && $(".costCheck").hasClass("scalar"))  {
        $(".btnPublishOffer").removeClass("disabled").addClass("btn-publish-campaign");
    } else {
        $(".btnPublishOffer").addClass("disabled").removeClass("btn-publish-campaign");
    }
};

function checkLang() {
    var offerLang = $("#offerLang").val();
    $.ajax({
        url: "/lib/webservices/hotel-crear-detalle-oferta.php",
        data: "checkLang=1&langToCheck=" + offerLang + "",
        type: "POST",
       	async: false,
        success: function(output) {
            var obj = jQuery.parseJSON(output);
            if (obj["code"] == "200") {
                $("#circle-" + offerLang).empty();
                $("#circle-" + offerLang).append('<i class="fa fa-check-circle-o verde lang-ok"></i>');
            } else {
                $("#circle-" + offerLang).empty();
            }
        }
    });
};

// All langs must be OK
function checkLangs() {
	var langs=0;
	var langsOk=0;
	//Check if langLi has <i class="fa fa-check-circle-o verde lang-ok">
	$(".langLi").each(function() {
        langs++;
		$(this).find("i").each(function() {
			langsOk++;
        });
    });
	if(langsOk == langs){
		result=200;
        $("#checkLang").css("color", "#0dfe93").addClass("scalar");
	}else{
		result=400;
        $("#checkLang").css("color", "#D7D7D7").removeClass("scalar");
		
	}
    return result;
};

function saveLang(lang) {
    $.ajax({
        url: "/lib/webservices/hotel-crear-detalle-oferta.php",
        data: "lang=" + lang + "",
        type: "POST",
        success: function(output) {
            var obj = jQuery.parseJSON(output);
            var langDiv = obj["lang"];
            $("#langSected").empty();
            $("#langSected").append(langDiv);
            var name = obj["nombre"];
            $("#offerName").val(name);
            var descripcion = obj["descripcion"];
            $("#offerDesc").empty();
            $("#offerDesc").append(descripcion);
            var condiciones = obj["condiciones"];
            $("#offerCond").empty();
            $("#offerCond").append(condiciones);
            $("#offerLang").val(lang);
            checkText("#offerDesc");
            checkText("#offerCond");
            checkTop();
        }
    });
};

function cleanHtml(string) {
    var regex = /(<([^>]+)>)/gi;
    var body = string;
    var result = body.replace(regex, "");
    return result;
};

function initToolbarBootstrapBindings() {
    $('.btn-toolbar a[title]').tooltip({
        container: 'body'
    });
    $('.dropdown-menu input').click(function() {
            return false;
        })
        .change(function() {
            $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
        })
        .keydown('esc', function() {
            this.value = '';
            $(this).change();
        });
};

function showErrorAlert(reason, detail) {
    var msg = '';
    if (reason === 'unsupported-file-type') {
        msg = "Unsupported format " + detail;
    } else {
        console.log("error uploading file", reason, detail);
    }
    $('&lt;div class="alert"&gt; &lt;button type="button" class="close" data-dismiss="alert"&gt;&amp;times;&lt;/button&gt;' +
        '&lt;strong&gt;File upload error&lt;/strong&gt; ' + msg + ' &lt;/div&gt;').prependTo('#alerts');
};

function comprobarGuardable(fun) {
    $.ajax({
        url: "/lib/webservices/hotel-crear-detalle-oferta.php",
        data: 'guardable=check',
        type: 'POST',
        async: false,
        success: function(output) {
            fun(output);
        }
    });
};

function comprobarPublicable() {
    //console.log('-comprobarPublicable-')
	$.ajax({
        url: "/lib/webservices/hotel-crear-detalle-oferta.php",
        data: 'publicable=check',
        type: 'POST',
        async: false,
        success: function(output) {
			canPub = output;
        }
    });
};

function uploadImg() {
    var file_data = $('#imgCropUploadForm').prop('files')[0];
    var form_data = new FormData();
    form_data.append('uploadImg', file_data);
    $.ajax({
        url: '../../lib/webservices/img_save_to_file.php', // point to server-side PHP script 
        dataType: "json", // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(response) {
            //Si la imagen se sube correctamente
            if (response.status === "success") {
                $('#cropper-example-2 > img').attr('src', response.url);
                window.$image = $('#cropper-example-2 > img');
                $('#cropper-example-2-modal').on('shown.bs.modal', function() {
                    $image.cropper({
                        strict: true,
                        autoCropArea: 0.9,
                        guides: true,
                        moveable: true,
                        highlight: true,
                        dragCrop: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        built: function() {
                            $('.cropperBtns .btn').click(function(e) {
                                e.preventDefault();
                                var method = $(this).data('method');
                                var option = $(this).data('option');
                                $image.cropper(method, option);
                            })
                        }
                    });
                });
                $('#cropper-example-2-modal').modal('show');
            };
            //Si la imagen no se sube correctamente
            if (response.status === "error") {
                if (response.code === "imgSmall") {
                    alert('Image is too small. Please, upload at least an 710px width per 500px height image');
                }
                if (response.code === "imgNotAllowed") {
                    alert('Image format is not allowed. You can upload those extensions: GIF, JPG, PNG.');
                }
                if (response.code === "imgNotUploaded") {
                    alert('Select an image before upload and edit it');
                }
            }
        },
        error: function(response){
            alert('Uppss.. error al intentar subir la imagen, por favor, inténtalo de nuevo, Error: ' + JSON.stringify(response));
        }
    });
};
