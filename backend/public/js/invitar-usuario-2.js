// JavaScript Document
function desactivarSave() {
    $(".saveListBtn").addClass("dnone");
}

function obtenerField2(field1) {
    $.ajax({
        url: "/lib/webservices/invitar-usuarios-2-ws.php",
        data: "field1=" + field1 + "",
        type: "POST",
        async: false,
        success: function(output) {
            $("#invite-field-2").removeAttr("disabled");
            $("#invite-field-2").empty();
            $("#invite-field-2").append(output);
            // vaciamos los siguientes fields
            $("#invite-field-3").empty().prop("disabled", true);
            $("#invite-field-4").empty().prop("disabled", true);
            $("#invite-field-5").empty().prop("disabled", true);
            $("#invite-field-6").empty().prop("disabled", true);
            desactivarSave();
        }
    });
}

function obtenerField3(field2) {
    $.ajax({
        url: "/lib/webservices/invitar-usuarios-2-ws.php",
        data: "field2=" + field2 + "",
        type: "POST",
        async: false,
        success: function(output) {
            $("#invite-field-3").removeAttr("disabled");
            $("#invite-field-3").empty();
            $("#invite-field-3").append(output);
            // vaciamos los siguientes fields
            $("#invite-field-4").empty().prop("disabled", true);
            $("#invite-field-5").empty().prop("disabled", true);
            $("#invite-field-6").empty().prop("disabled", true);
            desactivarSave();
        }
    });
}

function obtenerField4(field3) {
    $.ajax({
        url: "/lib/webservices/invitar-usuarios-2-ws.php",
        data: "field3=" + field3 + "",
        type: "POST",
        async: false,
        success: function(output) {
            $("#invite-field-4").removeAttr("disabled");
            $("#invite-field-4").empty();
            $("#invite-field-4").append(output);
            // vaciamos los siguientes fields
            $("#invite-field-5").empty().prop("disabled", true);
            $("#invite-field-6").empty().prop("disabled", true);
            desactivarSave();
        }
    });
}

function obtenerField5(field4) {
    $.ajax({
        url: "/lib/webservices/invitar-usuarios-2-ws.php",
        data: "field4=" + field4 + "",
        type: "POST",
        async: false,
        success: function(output) {
            $("#invite-field-5").removeAttr("disabled");
            $("#invite-field-5").empty();
            $("#invite-field-5").append(output);
            // vaciamos los siguientes fields
            $("#invite-field-6").empty().prop("disabled", true);
            desactivarSave();
        }
    });
}

function obtenerField6(field5) {
    $.ajax({
        url: "/lib/webservices/invitar-usuarios-2-ws.php",
        data: "field5=" + field5 + "",
        type: "POST",
        async: false,
        success: function(output) {
            $("#invite-field-6").removeAttr("disabled");
            $("#invite-field-6").empty();
            $("#invite-field-6").append(output);
            desactivarSave();
        }
    });
}

function guardarListaUsuarios() {
    $("#saveGuestListModal").modal("hide");
    var ruta = $("#ruta").val();
    var archivo = $("#archivo").val();
    var nombre = $("#listName").val();
    var field1 = $("#invite-field-1").val();
    var field2 = $("#invite-field-2").val();
    var field3 = $("#invite-field-3").val();
    var field4 = $("#invite-field-4").val();
    var field5 = $("#invite-field-5").val();
    var field6 = $("#invite-field-6").val();
    $.ajax({
        url: "/lib/webservices/invitar-usuarios-2-ws.php",
        data: "ruta=" + ruta + "&archivo=" + archivo + "&nombre=" + nombre + "&f1=" + field1 + "&f2=" + field2 + "&f3=" + field3 + "&f4=" + field4 + "&f5=" + field5 + "&f6=" + field6,
        type: "POST"
    });
}

function closeModal() {
    $("#saveGuestListModal").modal("hide");
}

/*function showError(errorN) {
    $(".feedback ").empty();
    $(".feedback ").removeClass("warning-action");
    $(".feedback ").addClass("error-action");
    $(".feedback ").append(errorN);
    msgError();
}*/

// Ajax para Update de cambios en los inputs
$(document).on("blur", ".table input", function() {
    var selected = $(this).val();
    var idTipo = $(this).data("type");
    var wsSec = $("#wsSec").val();
    var hotelId = $("#hotelId").val();
    var idList = $("#idList").val();
    $.ajax({
        url: "/lib/webservices/invitar-usuarios-2-ws.php",
        data: "valor=" + selected + "&idTipo=" + idTipo + "&hotelId=" + hotelId + "&idList=" + idList + "&wsSc=" + wsSec + "",
        type: "POST",
        success: function(output) {
            var obj = jQuery.parseJSON(output);
            var objectId = obj["id"];
            // Email
            if (obj["type"] == "email" && obj["code"] == "200") {
                $("#" + objectId).removeClass("has-error");
            } else if (obj["type"] == "email") {
                $("#" + objectId).addClass("has-error");
            }
            if (obj["type"] == "marcado") {
                if ($("#" + objectId).val() == 0) {
                    $("#" + objectId).val(1);
                } else {
                    $("#" + objectId).val(0);
                }
            }
            // points, total spent, total nights
            if ((obj["type"] == "puntos" || obj["type"] == "total_spent" || obj["type"] == "total_noches") && (obj["value"] != 0 && obj["value"] != "")) {
                $("#" + objectId).removeClass("has-warning");
            } else if (obj["type"] == "puntos" || obj["type"] == "total_spent" || obj["type"] == "total_noches") {
                $("#" + objectId).addClass("has-warning");
            }
            $(".nValidEmails").text(obj["validEmails"]);
            $("#nInvalidEmails").text(obj["invalidEmails"]);
            if (obj["invalidEmails"] == "0") {
                $("#alertInvalidEmails").hide();
            } else {
                $("#alertInvalidEmails").show();
            }
            // Show feedback msg
            if (obj["msgError"] != "") {
                showError(obj["msgError"]);
            }
        }
    });
});

function addNewLine() {
    //Añadir linea en blanco con los ids para guardar cambios en la BD
    var prod = $("#prod").val();
    var wsSec = $("#wsSec").val();
    var hotelId = $("#hotelId").val();
    var idList = $("#idList").val();
    $.ajax({
        url: "/lib/webservices/invitar-usuarios-2-ws.php",
        data: "addNewLine=1&hotelId=" + hotelId + "&idList=" + idList + "&wsSc=" + wsSec + "&prod=" + prod,
        type: "POST",
        success: function(output) {
            var obj = jQuery.parseJSON(output);
            $("#usersListIU2").append(obj["row"]);
        }
    });
}

//
$("#invite-field-1").change(function() {
    var field1 = $("#invite-field-1").val();
    obtenerField2(field1);
});

$("#invite-field-2").change(function() {
    var field2 = $("#invite-field-2").val();
    obtenerField3(field2);
});

$("#invite-field-3").change(function() {
    var field3 = $("#invite-field-3").val();
    if ($("#prod").val() == "RF") {
        // Activamos el boton save
        $(".saveListBtn").removeClass("dnone");
    } else {
        obtenerField4(field3);
    }
});

$("#invite-field-4").change(function() {
    var field4 = $("#invite-field-4").val();
    obtenerField5(field4);
});

$("#invite-field-5").change(function() {
    var field5 = $("#invite-field-5").val();
    obtenerField6(field5);
});

$("#invite-field-6").change(function() {
    // Activamos el boton save
    $(".saveListBtn").removeClass("dnone");
});

//Send filter form
$(".btn-filter").click(function() {
    var data = $(this).data("filter");
    $(".btn-filter").removeClass("active");
    $(this).addClass("active");
    $(".filtersForm .filterInput").remove();
    $(".filtersForm").append('<input type="hidden" class="filterInput" name="' + data + '" value="' + data + '">');
});

$(".langFilter").change(function() {
    var data = $(this).find(":selected").data("filter");
    console.log(data);
    $(".filtersForm .filterLangInput").remove();
    $(".filtersForm").append('<input type="hidden" class="filterLangInput" name="lang" value="' + data + '">');
});

$(".applyFilterBtn").click(function() {
    $(".filtersForm").submit();
});

$(".hideFiltersBtn").click(function() {
    $("#inviteFilters").fadeOut();
    $(".showFiltersBtn").fadeIn();
});

$(".showFiltersBtn").click(function() {
    $("#inviteFilters").fadeIn();
    $(".showFiltersBtn").fadeOut();
});