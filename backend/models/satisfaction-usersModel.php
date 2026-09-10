<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function getStaffWhoMarkSatisfactionFinished($hotel_id, $itemsPage, $page, $research, $order, $sort, $paginate = true) {
	
	if($page == null){
	    $page = 1;
    }
	$inicio = $itemsPage * $page - $itemsPage;
	$search = '';
	if ($research) {
		if ($research != '') {
            $research = sqlEscape($research);
            $search .= " AND nombre LIKE '%" . $research . "%' ";

        }
	}

	$sql2 = "FROM hotel_staff  
				LEFT JOIN hotel_staff_hotels 
					ON hotel_staff.id=hotel_staff_hotels.hotel_staff_id
						LEFT JOIN user_satisfaction 
							ON user_satisfaction.id_hotel = hotel_staff_hotels.hotel_id AND user_satisfaction.who_has_been_seen = hotel_staff.id
			where hotel_staff_hotels.hotel_id = $hotel_id AND hotel_staff.id_role != 1 $search";
	
	$sql = "SELECT nombre, count(who_has_been_seen) AS number_reviews_warning_email
			$sql2
			group by nombre
			ORDER BY $order $sort
			LIMIT $inicio,$itemsPage";
	
	$row = lecturaArray($sql);

	if ($paginate) {
		$sql0 = "SELECT COUNT(DISTINCT(nombre)) AS N ";
		paginacion2($sql0 . $sql2, $page, $itemsPage);
	}

 	return $row;
}

function getMainAccountWhoMarkSatisfactionFinished($hotel_id) {
	$sql = "SELECT count(who_has_been_seen) AS warning_finished
			FROM user_satisfaction
			where id_hotel = $hotel_id AND has_been_seen = 1 AND who_has_been_seen = -1";


	
	$row = lectura($sql);

 	return $row['warning_finished'];

}

?>