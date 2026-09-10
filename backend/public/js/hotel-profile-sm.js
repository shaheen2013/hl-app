function changeLang(lang) {
	$.ajax({
		url: "/lib/webservices/hotel-profile-sm-ws.php",
		data: 'changeLang=' + lang + '',
		type: 'POST',
        success: function(output) {
			var obj = jQuery.parseJSON(output);
			//Actual lang
			$("#actualLang").val('');
			$("#actualLang").val(lang);
			//Pre
			$("#sharePreText").val('');
			$("#sharePreText").val(obj["pre"]);
			//Stay
			$("#shareStayText").val('');
			$("#shareStayText").val(obj["stay"]);
			//Post
			$("#sharePostText").val('');
			$("#sharePostText").val(obj["post"]);
			//Flag
			var langDiv = obj['lang'];
            $('#langSected').empty();
            $('#langSected').append(langDiv);
            //Check if all stay text are filled
            checkShareTexts();
		}
	});
}

function checkShareTexts(){
	$.ajax({
        url: "/lib/webservices/hotel-profile-sm-ws.php",
        data: 'checkShareTexts=1',
        type: 'POST',
        success: function(output) {
            var obj = jQuery.parseJSON(output);
            obj.forEach(function(data) {
                if (data['code'] == '200') {
                    $('#circle-' + data['lang']).empty();
                    $('#circle-' + data['lang']).append('<i class="fa fa-check-circle-o verde lang-ok">');
                } else {
                    $('#circle-' + data['lang']).empty();
                }
            });
        }
    });
}

$(document).ready(function() {
    $(".langLia").click(function(e) {
        e.preventDefault();
    });
    changeLang($("#actualLang").val());
});