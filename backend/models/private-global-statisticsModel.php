<?php 

function getAllHotelStatistics(){
	$sql = "SELECT id_hotel, hoteles.hotelName, iframe_opens, share_btn_clicks, iframe_modal_open, iframe_modal_share FROM hotel_statistics
			LEFT JOIN hoteles ON hoteles.id = hotel_statistics.id_hotel";
	$rs = mysqli_query (conectar(1), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$rows[] = $row;
	}
	liberar($rs);
	return $rows;
}