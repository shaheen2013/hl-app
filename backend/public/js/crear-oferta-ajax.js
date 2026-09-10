function obtenerOffertype(){
	var selected = $("#offerMethod").val();
	$('#offertype').removeAttr("disabled");
	$.ajax({ url: "/lib/webservices/hotel-crear-oferta.php",
		data: 'offerMethod='+ selected +'',
		type: 'POST',
		async : false,
		success: function(output) {
			$('#offertype').empty();
			$('#offertype').append(output);
		}
	});
}

function obtenerCategory(){
	var selected = $("#offertype").val();
	$('#category').removeAttr("disabled");
	$.ajax({ url: "/lib/webservices/hotel-crear-oferta.php",
		data: 'offertype='+ selected +'',
		type: 'POST',
		async : false,
		success: function(output) {
			$('#category').empty();
			$('#category').append(output);
		}
	});
	if (selected != 'chk'){
		obtenerSubcategory();
	}else{
		resetSubcategory();
	}
}

function obtenerSubcategory(){
	var selected = $("#category").val();
	if (selected == 0){
		$('#subCategory').empty();
		$('#subCategory').attr('disabled', true);
	}else{
		$('#subCategory').removeAttr("disabled");
		$.ajax({ url: "/lib/webservices/hotel-crear-oferta.php",
			data: 'category='+ selected +'',
			type: 'POST',
			async : false,
			success: function(output) {
				$('#subCategory').empty();
				$('#subCategory').append(output);
			}
		});
	}
}

function resetSubcategory(){
	$('#subCategory').attr('disabled', true);
	$('#subCategory').empty();
}

$(document).ready(function(){
//----------------------------------------------------------------------------------------
	$('#offerMethod').change(function(){
		obtenerOffertype();
		var ofMet = $("#offerMethod").val();
		obtenerCategory();
		obtenerSubcategory();
		/*if(ofMet == 'adq'){
			puedeCrearAdq();
		}*/
	});
	
	$('#offertype').change(function(){
		obtenerCategory();
	});
	
	$('#category').change(function(){
		obtenerSubcategory();
	});
	
	$('#subCategory').change(function(){
		var selected = $("#subCategory").val();
		$.ajax({ url: "/lib/webservices/hotel-crear-oferta.php",
			data: 'subCategory='+ selected +'',
			type: 'POST',
		});
	});
//-----------------------------------------------------------------------------------------
});