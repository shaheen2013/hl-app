// JavaScript Document

//Fx para mostrar un msg feedback desde js
//error: texto de error
function showError(error, timeShow)
{
	if(!timeShow){ timeShow = 5000; }

	$(".feedback ").empty();

	$(".feedback ").removeClass("warning-action");
	$(".feedback ").removeClass("error-action");
	$(".feedback ").removeClass("success-action");
	
	if (error.indexOf("fa-ban") >= 0){
		$(".feedback ").addClass("error-action");
	}else if (error.indexOf("fa-check") >= 0){
		$(".feedback ").addClass("success-action");
	}else if (error.indexOf("fa-exclamation-triangle") >= 0){
		$(".feedback ").addClass("warning-action");
	}
    
    $(".feedback ").append(error);
    msgError(timeShow);
}