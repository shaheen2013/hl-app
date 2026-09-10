<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function get_roles($lang){
	$select_roles = "SELECT id, role_".$lang." AS role FROM hotel_staff_roles ORDER BY role ASC";
	return lecturaArray($select_roles);
}

//Return the hotels that the staff have permissions to see
function get_actual_staff_hotels ($staff_id) {
	$select_staff_hotels = "SELECT hotel_id FROM hotel_staff_hotels WHERE hotel_staff_id = $staff_id";
	return lecturaArray($select_staff_hotels);
}

function get_actual_staff_role ($staff_id) {
	$select_staff_role = "SELECT id_role, email FROM hotel_staff WHERE id = $staff_id";
	return lectura($select_staff_role);
}

function update_staff ($staff_hotels, $staff_role, $staff_id) {

	//Update staff role
	$update_role = "UPDATE hotel_staff SET id_role=$staff_role WHERE id=$staff_id";
	escritura($update_role);

	//Update staff hotels
	$delete_actual_hotels = "DELETE FROM hotel_staff_hotels WHERE hotel_staff_id=$staff_id";
	escritura($delete_actual_hotels);

	$hotel_staff_hotels_values = array_map(function($hotel_id) use ($staff_id) {
        return " ($hotel_id,$staff_id)";
    }, $staff_hotels);

	$insert_staff_hotels = "INSERT INTO hotel_staff_hotels (hotel_id, hotel_staff_id) VALUES ".join(', ', $hotel_staff_hotels_values);
	escritura($insert_staff_hotels);
}
?>