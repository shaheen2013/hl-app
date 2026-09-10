// JavaScript Document

// FX para simular logins a FB
function simularLoginFB(){
	
	/*var response = ' FB.__globalCallbacks.f3bb0a08e79ad42({"name":"Testo Dest","email":"newhotelinking@gmail.com"
,"friends":{"data":[{"name":"Jaume Cabrer","id":"535959519885421"},{"name":"Desto Testo","id":"1621952034744994"
}],"paging":{"next":"https:\\graph.facebook.com\v2.4\1469619193344930\/friends?access_token=CAABcutLKgykBADZBoVQEpuEQhdkR4Ammts30IKUNpZBxSaTmM3ZB9YpmJtd514gSaL4D4b2fX7oiGjAw91ZAZCgwWNaAaPD63ZALZB5gegBuyh8UQ8A37qXvcInmDC6uQStawateWzqImAkVva7eZCZAZCHGhcInEK2H3kXIUR8w7CaNb6oqqT0z966R2bakKMWLYvP4x0dZCooZB7a4ZBgZCE4FBn
&limit=25&offset=25&__after_id=enc_AdA5VZARE7ttYBQOAdAyBQ2EYyKsQGZBO9RRO6CQHbTJW6wD8c4wUl1F1jqxiK5NxZAR2VBKncZAOUHSQjIAEPmyaDZBF"
},"summary":{"total_count":3}},"id":"1469619193344930"});';*/
	
	//var response = '{"name":"Testo Dest","email":"newhotelinking@gmail.com","friends":{"summary":{"total_count":3}},"id":"1469619193344930"} ';
	
	$.ajax({
		url: "/lib/webservices/FBSimulator-ws.php",
		data: "getUser=1",
		type: 'POST',
		success: function(output) {
			console.log(data);
			data = $.parseJSON(output);
			return data;
		}
	});
};