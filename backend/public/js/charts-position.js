$(document).ready(function(){
	var container = document.querySelector('#homeContainer');
	var pckry = new Packery( container, {
	  // options
	  itemSelector: '.item',
	  "columnWidth" : ".column-width",
	  "rowHeight" : 305
	});

	//order From server
	function getOrderItems(dashboard){
		$.ajax({ url: "/lib/webservices/charts-position.php",
			data: 'pag='+dashboard,
			type: 'POST',
			async: false,
			success: function(data){
				data = $.parseJSON(data);
				sortOrder = data;
			}
		});
		return sortOrder
	}
	var dashboard = $('body').attr('class');
	var sortOrder = getOrderItems(dashboard);
	//var sortOrder = ["14", "4", "3", "12", "6", "5", "1", "8", "10", "9", "7", "2", "0", "11", "13"];
	// create a hash of items by their tabindex
	var itemsByTabIndex = {};
	for ( var i=0, len = pckry.items.length; i < len; i++ ) {
		var item = pckry.items[i];
		var tabIndex = item.element.getAttribute('tabindex');
		itemsByTabIndex[ tabIndex ] = item;
	}
	// overwrite pckry item order
	i = 0; var len = sortOrder.length;
	for (; i < len; i++ ) {
		var tabIndex = sortOrder[i];
		pckry.items[i] = itemsByTabIndex[ tabIndex ];
	}

	//drag
	var itemElems = $('#homeContainer').find('.item');
	// make item elements draggable
	itemElems.draggable({
		handle: '.dragger-btn'
	});

	// bind Draggable events to Packery
	pckry.bindUIDraggableEvents(itemElems);

	//order items again
	function orderItems(sortOrder) {
		sortOrder = [];
		var itemElems = pckry.getItemElements();
		for (var i=0; i< itemElems.length; i++) {
			sortOrder[i] = itemElems[i].getAttribute("tabindex");
		}
		$.ajax({ url: "/lib/webservices/charts-position.php",
			data: 'pag='+dashboard+'&sortOrder='+sortOrder,
			type: 'POST'
		});
	}
  pckry.on( 'layoutComplete', orderItems );
  pckry.on( 'dragItemPositioned', orderItems );
  pckry.layout();
}); //document ready